<?php

namespace App\Http\Controllers;

use App\Models\StockEntry;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class StockEntryController extends Controller
{
    /**
     * Mostrar la lista de entradas de stock.
     */
    public function index()
    {
        $stockEntries = StockEntry::with('product', 'product.category')->latest()->get();
        return view('stock_entries.index', compact('stockEntries'));
    }

    /**
     * Mostrar el formulario para crear una nueva entrada de stock.
     */
    public function create()
    {
        return view('stock_entries.create');
    }

    /**
     * Buscar productos por nombre o categoría.
     */
    public function searchProducts(Request $request)
    {
        $query = $request->get('query');
        $products = Product::where('name', 'like', "%{$query}%")
            ->with('category') // Relación de categoría
            ->get();

        $formattedProducts = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'category' => $product->category ? $product->category->name : 'Sin categoría',
                'purchase_price' => $product->purchase_price,
            ];
        });

        return response()->json($formattedProducts);
    }

    /**
     * Guardar una nueva entrada de stock en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.purchase_price' => 'required|numeric|min:0',
            'document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $total = 0;
        $entries = [];
        $documentPath = null;

        // Procesar cada producto
        foreach ($request->products as $productData) {
            $product = Product::findOrFail($productData['id']);
            $subtotal = $productData['quantity'] * $productData['purchase_price'];
            $total += $subtotal;

            // Crear la entrada de stock
            $entry = StockEntry::create([
                'product_id' => $product->id,
                'quantity' => $productData['quantity'],
                'purchase_price' => $productData['purchase_price'],
                'user_id' => auth()->id(),
            ]);

            // Actualizar el stock del producto
            $product->increment('stock', $productData['quantity']);

            $entries[] = $entry->load('product');

        }

        // Guardar el archivo de boleta o factura (si existe)
        if ($request->hasFile('document')) {
            $documentPath = $request->file('document')->store('public/stock-documents/' . now()->format('Y-m-d'));
        }

        // Generar el PDF
        $pdfData = [
            'entries' => $entries,
            'total' => $total,
            'date' => now()->format('Y-m-d H:i'),
            'user' => auth()->user()->name,
        ];

        $pdf = Pdf::loadView('pdf.stock-entry', $pdfData);
        $pdfPath = 'public/stock-pdfs/' . now()->format('Y-m-d') . '/stock-entry-' . now()->timestamp . '.pdf';
        Storage::put($pdfPath, $pdf->output());

        return response()->json([
            'success' => true,
            'message' => 'Ingreso de stock registrado correctamente.',
            'pdf_url' => Storage::url($pdfPath),
            'document_url' => $documentPath ? Storage::url($documentPath) : null,
        ]);
    }
}
