<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Mostrar todos los productos.
     */
    public function index()
    {
        $products = Product::with('category', 'suppliers')->paginate(10); // Ajusta el número de productos por página
        $categories = Category::all(); // Obtener todas las categorías
        $suppliers = Supplier::all();  // Obtener todos los proveedores
        return view('products.index', compact('products', 'categories', 'suppliers'));
    }

    /**
     * Mostrar el formulario para crear un nuevo producto.
     */
    public function create()
    {
        $categories = Category::all();
        $suppliers = Supplier::all();
        return view('products.create', compact('categories', 'suppliers'));
    }

    /**
     * Guardar un nuevo producto en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'suppliers' => 'nullable|array', // Proveedores seleccionados
            'suppliers.*' => 'exists:suppliers,id', // Validar que los proveedores existen
            'purchase_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'stock_min' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048', // Validar imágenes
        ]);

        // Procesar imagen (si está presente)
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        // Crear el producto
        $product = Product::create(array_merge(
            $request->only(['name', 'category_id', 'purchase_price', 'sale_price', 'stock', 'stock_min']),
            ['image_path' => $imagePath]
        ));

        // Asociar proveedores
        if ($request->has('suppliers')) {
            $product->suppliers()->sync($request->suppliers);
        }

        return redirect()->route('products.index')->with('success', 'Producto creado con éxito.');
    }

    /**
     * Mostrar el formulario para editar un producto existente.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        $suppliers = Supplier::all();
        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

    /**
     * Actualizar un producto en la base de datos.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'suppliers' => 'nullable|array', // Proveedores seleccionados
            'suppliers.*' => 'exists:suppliers,id', // Validar que los proveedores existen
            'purchase_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'stock_min' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048', // Validar imágenes
        ]);

        // Procesar imagen (si está presente)
        if ($request->hasFile('image')) {
            // Eliminar la imagen anterior si existe
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            $imagePath = $request->file('image')->store('products', 'public');
            $product->image_path = $imagePath;
        }

        // Actualizar los datos del producto
        $product->update($request->only([
            'name', 'category_id', 'purchase_price', 'sale_price', 'stock', 'stock_min'
        ]));

        // Actualizar relación con proveedores
        if ($request->has('suppliers')) {
            $product->suppliers()->sync($request->suppliers);
        }

        return redirect()->route('products.index')->with('success', 'Producto actualizado correctamente.');
    }

    /**
     * Eliminar un producto de la base de datos.
     */
    public function destroy(Product $product)
    {
        // Eliminar la imagen del producto si existe
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Producto eliminado con éxito.');
    }

    /**
     * Buscar productos por nombre o categoría.
     */
    public function search(Request $request)
    {
        $query = $request->get('query', '');

        $products = Product::where('name', 'like', "%{$query}%")
            ->orWhereHas('category', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->with('category') // Incluir datos de la categoría
            ->limit(10) // Limitar resultados
            ->get();

        return response()->json($products);
    }
}
