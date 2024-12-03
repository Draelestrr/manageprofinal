<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    /**
     * Mostrar la lista de ventas.
     */
    public function index()
    {
        $sales = Sale::with('products', 'customer', 'user')->get();
        return view('sales.index', compact('sales'));
    }

    /**
     * Mostrar el formulario para crear una nueva venta.
     */
    public function create()
    {
        $customers = Customer::all();
        $products = Product::all();
        return view('sales.create', compact('customers', 'products'));
    }

    /**
     * Guardar una nueva venta en la base de datos.
     */
    public function store(Request $request)
{
    $request->validate([
        'customer_id' => 'nullable|exists:customers,id',
        'products' => 'required|array',
        'products.*.product_id' => 'required|exists:products,id',
        'products.*.quantity' => 'required|integer|min:1',
        'products.*.unit_price' => 'required|numeric|min:0',
    ]);

    // Calcular el total de la venta
    $total = collect($request->products)->sum(fn($p) => $p['quantity'] * $p['unit_price']);

    // Crear la venta
    $sale = Sale::create([
        'user_id' => auth()->id(),
        'customer_id' => $request->customer_id,
        'total' => $total,
    ]);

    // Iterar sobre los productos vendidos
    foreach ($request->products as $product) {
        // Obtener el producto
        $productModel = Product::find($product['product_id']);

        // Verificar si hay suficiente stock
        if ($productModel->stock >= $product['quantity']) {
            // Descontar la cantidad del inventario
            $productModel->decrement('stock', $product['quantity']);

            // Registrar el producto en la venta
            $sale->products()->attach($product['product_id'], [
                'quantity' => $product['quantity'],
                'unit_price' => $product['unit_price'],
                'subtotal' => $product['quantity'] * $product['unit_price'],
            ]);
        } else {
            return redirect()->back()->with('error', "No hay suficiente stock para el producto {$productModel->name}. Stock disponible: {$productModel->stock}");
        }
    }

    // Redirigir al listado de ventas
    return redirect()->route('sales.index')->with('success', 'Venta registrada con éxito.');
    }


    /**
     * Mostrar el formulario para editar una venta existente.
     */
    public function edit(Sale $sale)
    {
        $customers = Customer::all();
        $products = Product::all();
        return view('sales.edit', compact('sale', 'customers', 'products'));
    }

    /**
     * Actualizar una venta existente.
     */
    public function update(Request $request, Sale $sale)
{
    $request->validate([
        'customer_id' => 'nullable|exists:customers,id',
        'products' => 'required|array',
        'products.*.product_id' => 'required|exists:products,id',
        'products.*.quantity' => 'required|integer|min:1',
        'products.*.unit_price' => 'required|numeric|min:0',
    ]);

    // Primero, reponer el stock de los productos previamente vendidos
    foreach ($sale->products as $product) {
        $productModel = Product::find($product->pivot->product_id);
        $productModel->increment('stock', $product->pivot->quantity);  // Devolver al stock
    }

    // Descartar los productos anteriores de la venta
    $sale->products()->detach();

    // Calcular el nuevo total de la venta
    $total = collect($request->products)->sum(fn($p) => $p['quantity'] * $p['unit_price']);
    $sale->update([
        'customer_id' => $request->customer_id,
        'total' => $total,
    ]);

    // Iterar sobre los nuevos productos y actualizar el inventario
    foreach ($request->products as $product) {
        $productModel = Product::find($product['product_id']);

        if ($productModel->stock >= $product['quantity']) {
            // Descontar el stock de los productos vendidos
            $productModel->decrement('stock', $product['quantity']);

            // Añadir los productos actualizados a la venta
            $sale->products()->attach($product['product_id'], [
                'quantity' => $product['quantity'],
                'unit_price' => $product['unit_price'],
                'subtotal' => $product['quantity'] * $product['unit_price'],
            ]);
        } else {
            return redirect()->back()->with('error', "No hay suficiente stock para el producto {$productModel->name}. Stock disponible: {$productModel->stock}");
        }
    }

    // Redirigir a la lista de ventas
    return redirect()->route('sales.index')->with('success', 'Venta actualizada con éxito.');
}


    /**
     * Eliminar una venta.
     */
    public function destroy(Sale $sale)
    {
        $sale->products()->detach();
        $sale->delete();

        return redirect()->route('sales.index')->with('success', 'Venta eliminada con éxito.');
    }
}
