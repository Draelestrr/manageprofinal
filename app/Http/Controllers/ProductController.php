<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Traits\ImageUploadTrait;

class ProductController extends Controller
{
    use ImageUploadTrait;

    /**
     * Mostrar todos los productos.
     */
    public function index()
    {
        $products = Product::with('category', 'supplier')->paginate(15);
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
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0|gt:purchase_price',
            'stock' => 'required|integer|min:0',
            'stock_min' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048|mimes:jpeg,png,jpg,gif'
        ]);

        // Verificar si el archivo de imagen está presente
        if ($request->hasFile('image')) {
            \Log::info('Imagen encontrada en la solicitud.');
            $imagePath = $this->uploadImage($request->file('image'));

            if ($imagePath) {
                \Log::info('Imagen subida a: ' . $imagePath);

                if (Storage::disk('public')->exists($imagePath)) {
                    \Log::info('La imagen existe en el sistema de archivos.');
                } else {
                    \Log::warning('La imagen no se encontró en el sistema de archivos después de la subida.');
                }
            } else {
                \Log::warning('El método uploadImage devolvió null.');
            }
        } else {
            \Log::info('No se ha subido ninguna imagen.');
            $imagePath = null;
        }

        // Crear el producto y asociar el proveedor
        $product = Product::create(array_merge(
            $request->only(['name', 'category_id', 'purchase_price', 'sale_price', 'stock', 'stock_min', 'supplier_id']),
            ['image_path' => $imagePath]
        ));

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
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0|gt:purchase_price',
            'stock' => 'required|integer|min:0',
            'stock_min' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048|mimes:jpeg,png,jpg,gif'
        ]);

        // Si se sube una nueva imagen
        if ($request->hasFile('image')) {
            \Log::info('Nueva imagen encontrada en la solicitud.');

            // Eliminar imagen anterior
            if ($this->deleteExistingImage($product->image_path)) {
                \Log::info('Imagen anterior eliminada: ' . $product->image_path);
            } else {
                \Log::warning('No se pudo eliminar la imagen anterior: ' . $product->image_path);
            }

            // Guardar nueva imagen
            $imagePath = $this->uploadImage($request->file('image'));

            if ($imagePath) {
                \Log::info('Nueva imagen subida a: ' . $imagePath);

                if (Storage::disk('public')->exists($imagePath)) {
                    \Log::info('La nueva imagen existe en el sistema de archivos.');
                } else {
                    \Log::warning('La nueva imagen no se encontró en el sistema de archivos después de la subida.');
                }

                $validatedData['image_path'] = $imagePath;
            } else {
                \Log::warning('El método uploadImage devolvió null para la nueva imagen.');
            }
        } else {
            \Log::info('No se ha subido ninguna nueva imagen.');
        }

        // Actualizar datos del producto
        $product->update($validatedData);

        return redirect()->route('products.index')->with('success', 'Producto actualizado correctamente.');
    }

    /**
     * Eliminar un producto de la base de datos.
     */
    public function destroy(Product $product)
    {
        // Eliminar la imagen del producto si existe
        if ($this->deleteExistingImage($product->image_path)) {
            \Log::info('Imagen eliminada: ' . $product->image_path);
        } else {
            \Log::warning('No se pudo eliminar la imagen: ' . $product->image_path);
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
