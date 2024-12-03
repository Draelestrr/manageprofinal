<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
