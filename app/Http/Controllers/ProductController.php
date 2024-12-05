<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Traits\ImageUploadTrait;

class ProductController extends Controller
{
    use ImageUploadTrait;

    /**
     * Mostrar todos los productos.
     */
    public function index()
    {
    $products = Product::with('category', 'suppliers')->get();
    $categories = Category::all();
    $suppliers = Supplier::all();
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
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'category_id' => 'required|exists:categories,id',
        'supplier_id' => 'required|exists:suppliers,id',  // Solo un proveedor
        'purchase_price' => 'required|numeric|min:0',
        'sale_price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'stock_min' => 'required|integer|min:0',
        'image' => 'nullable|image|max:2048|mimes:jpeg,png,jpg,gif'
    ]);

    // Procesar imagen
    $imagePath = null;
    if ($request->hasFile('image')) {
        $imagePath = $this->uploadImage($request->file('image'));
    }

    // Crear el producto
    $product = Product::create(array_merge(
        $request->only(['name', 'category_id', 'purchase_price', 'sale_price', 'stock', 'stock_min']),
        ['image_path' => $imagePath]
    ));

    // Asociar un proveedor
    if ($request->has('supplier_id')) {
        $product->suppliers()->sync([$request->supplier_id]);  // Asociar un solo proveedor
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
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'category_id' => 'required|exists:categories,id',
        'supplier_id' => 'required|exists:suppliers,id',  // Solo un proveedor
        'purchase_price' => 'required|numeric|min:0',
        'sale_price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'stock_min' => 'required|integer|min:0',
        'image' => 'nullable|image|max:2048|mimes:jpeg,png,jpg,gif'
    ]);

    // Si se sube una nueva imagen
    if ($request->hasFile('image')) {
        // Eliminar imagen anterior
        $this->deleteExistingImage($product->image_path);

        // Guardar nueva imagen
        $imagePath = $this->uploadImage($request->file('image'));
        $product->image_path = $imagePath;
    }

    // Actualizar datos del producto
    $product->update($request->only([
        'name', 'category_id', 'purchase_price', 'sale_price', 'stock', 'stock_min'
    ]));

    // Asociar un solo proveedor
    if ($request->has('supplier_id')) {
        $product->suppliers()->sync([$request->supplier_id]);  // Asociar un solo proveedor
    }

    return redirect()->route('products.index')->with('success', 'Producto actualizado correctamente.');
    }


    /**
     * Eliminar un producto de la base de datos.
     */
    public function destroy(Product $product)
    {
        // Eliminar la imagen del producto si existe
        $this->deleteExistingImage($product->image_path);

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

    /**
     * Métodos para crear categorías y proveedores (opcional)
     */
    public function createCategory(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string'
        ]);

        $category = Category::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Categoría creada exitosamente',
            'category' => $category
        ]);
    }

    public function createSupplier(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:suppliers,name',
            'contact_name' => 'required|string|max:255',
            'email' => 'required|email|unique:suppliers,email',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:255'
        ]);

        $supplier = Supplier::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Proveedor creado exitosamente',
            'supplier' => $supplier
        ]);
    }
}
