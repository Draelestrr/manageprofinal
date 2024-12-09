<?php
namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    /**
     * Mostrar el formulario para seleccionar parámetros del reporte.
     */
    public function index(Request $request)
    {
        // Obtiene el usuario autenticado
        $user = Auth::user();
        $reports = [];

        // Verifica el rol del usuario
        if ($user->hasRole('admin') || $user->hasRole('supervisor')) {
            $filters = $request->only(['user_id', 'date_start', 'date_end']);
            if ($filters) {
                $reports = $this->generateGeneralReport($filters);
            }
            return view('reports.index', compact('reports'));
        } else {
            // Estadísticas personales
            $reports = $this->generateUserReport($user->id);
            return view('reports.index', compact('reports'));
        }
    }

    /**
     * Generar reportes generales (administradores y supervisores).
     */
    private function generateGeneralReport($filters)
    {
        $query = [];

        // Aplicar filtros (por usuario y rango de fechas)
        if (isset($filters['user_id'])) {
            $query['user_id'] = $filters['user_id'];
        }
        if (isset($filters['date_start']) && isset($filters['date_end'])) {
            $dateRange = [$filters['date_start'], $filters['date_end']];
        } else {
            $dateRange = null;
        }

        // Reporte de ventas
        $sales = Sale::when($query['user_id'] ?? null, function ($q, $userId) {
            return $q->where('user_id', $userId);
        })
        ->when($dateRange, function ($q) use ($dateRange) {
            return $q->whereBetween('created_at', $dateRange);
        })
        ->get();

        // Reporte de gastos
        $expenses = Expense::when($query['user_id'] ?? null, function ($q, $userId) {
            return $q->where('user_id', $userId);
        })
        ->when($dateRange, function ($q) use ($dateRange) {
            return $q->whereBetween('created_at', $dateRange);
        })
        ->get();

        // Resumen general
        return [
            'sales' => $sales,
            'expenses' => $expenses,
            'total_sales' => $sales->sum('total'),
            'total_expenses' => $expenses->sum('amount'),
        ];
    }

    /**
     * Generar reportes para un usuario específico (trabajadores).
     */
    private function generateUserReport($userId)
    {
        // Reporte de ventas del usuario
        $sales = Sale::where('user_id', $userId)->get();

        // Reporte de gastos del usuario
        $expenses = Expense::where('user_id', $userId)->get();

        // Resumen personal
        return [
            'sales' => $sales,
            'expenses' => $expenses,
            'total_sales' => $sales->sum('total'),
            'total_expenses' => $expenses->sum('amount'),
        ];
    }

    /**
     * Exportar reporte general como PDF.
     */
    public function exportPdf(Request $request)
    {
        $filters = $request->only(['user_id', 'date_start', 'date_end']);
        $reports = $this->generateGeneralReport($filters);

        $pdf = Pdf::loadView('pdf.report', $reports);
        $fileName = 'reporte-general-' . now()->format('Y-m-d') . '.pdf';

        // Guardar en almacenamiento público
        $path = 'public/sale_reports/' . $fileName;
        Storage::put($path, $pdf->output());

        // Enviar el PDF por respuesta
        return response()->download(Storage::path($path));
    }

    /**
     * Exportar reporte general como CSV.
     */
    public function exportCsv(Request $request)
    {
        $filters = $request->only(['user_id', 'date_start', 'date_end']);
        $reports = $this->generateGeneralReport($filters);

        $sales = $reports['sales'];
        $expenses = $reports['expenses'];

        $fileName = 'reporte-general-' . now()->format('Y-m-d') . '.csv';

        // Creación del archivo CSV
        $csvData = [];

        // Agregar encabezados para ventas
        $csvData[] = ['Ventas'];
        $csvData[] = ['ID', 'Nombre del Producto', 'Cantidad', 'Precio Unitario', 'Subtotal'];

        foreach ($sales as $sale) {
            foreach ($sale->products as $product) {
                $csvData[] = [
                    $sale->id,
                    $product->name,
                    $product->pivot->quantity,
                    $product->pivot->unit_price,
                    $product->pivot->subtotal,
                ];
            }
        }

        // Agregar encabezados para gastos
        $csvData[] = [];
        $csvData[] = ['Gastos'];
        $csvData[] = ['ID', 'Descripción', 'Monto'];

        foreach ($expenses as $expense) {
            $csvData[] = [
                $expense->id,
                $expense->description,
                $expense->amount,
            ];
        }

        // Crear archivo CSV en memoria
        $handle = fopen('php://memory', 'w');
        foreach ($csvData as $line) {
            fputcsv($handle, $line);
        }

        // Mover el puntero del archivo al principio
        fseek($handle, 0);

        // Responder con el archivo CSV generado
        return response()->stream(
            function () use ($handle) {
                fpassthru($handle);
            },
            200,
            [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="reporte-general-' . now()->format('Y-m-d') . '.csv"',
            ]
        );
    }
}
