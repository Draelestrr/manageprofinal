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
                        ->with('category') // Cargar la relación de categoría
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
        // Validar los productos, cantidades y precios
        $request->validate([
            'products' => 'required|array|min:1', // Asegura que al menos un producto sea agregado
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.purchase_price' => 'required|numeric|min:0',
            'document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048', // Documento opcional
        ]);

        $total = 0;
        $entries = [];

        // Procesar cada producto
        foreach ($request->products as $productData) {
            $product = Product::find($productData['id']);
            $total += $productData['quantity'] * $productData['purchase_price'];

            // Crear la entrada de stock
            $entry = StockEntry::create([
                'product_id' => $product->id,
                'quantity' => $productData['quantity'],
                'purchase_price' => $productData['purchase_price'],
                'user_id' => auth()->id(),
            ]);

            // Actualizar el stock del producto
            $product->increment('stock', $productData['quantity']);

            $entries[] = $entry;
        }

        // Guardar el archivo de boleta o factura (opcional)
        $documentPath = null;
        if ($request->hasFile('document')) {
            $documentPath = $request->file('document')->store('public/stock-documents/' . now()->format('Y-m-d'));
        }


        // Generar y guardar el PDF del ingreso
        $pdfData = [
            'entries' => $entries,
            'total' => $total,
        ];
        $pdf = Pdf::loadView('pdf.stock-entry', $pdfData);
        $pdfPath = 'public/stock-pdfs/' . now()->format('Y-m-d') . '/stock-entry-' . now()->timestamp . '.pdf';
        Storage::put($pdfPath, $pdf->output());

        // Redirigir con la URL del PDF y archivo subido
        return response()->json([
            'success' => true,
            'message' => 'Ingreso de stock registrado correctamente.',
            'pdf_url' => Storage::url($pdfPath),
            'document_url' => $documentPath ? Storage::url($documentPath) : null,
        ]);
    }

    /**
     * Mostrar el formulario para editar una entrada de stock.
     */
    public function edit(StockEntry $stockEntry)
    {
        $products = Product::all();
        return view('stock_entries.edit', compact('stockEntry', 'products'));
    }

    /**
     * Actualizar una entrada de stock existente.
     */
    public function update(Request $request, StockEntry $stockEntry)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'purchase_price' => 'nullable|numeric|min:0',
        ]);

        $stockEntry->update($request->only(['quantity', 'purchase_price']));

        return redirect()->route('stock_entries.index')->with('success', 'Entrada de stock actualizada.');
    }

    /**
     * Eliminar una entrada de stock.
     */
    public function destroy(StockEntry $stockEntry)
    {
        // Antes de eliminar, reducir el stock del producto.
        $product = $stockEntry->product;
        $product->decrement('stock', $stockEntry->quantity);

        $stockEntry->delete();

        return redirect()->route('stock_entries.index')->with('success', 'Entrada de stock eliminada.');
    }
}
