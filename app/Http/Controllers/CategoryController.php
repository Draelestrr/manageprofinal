<?php
namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Mostrar una lista de todas las categorías.
     * Soporta solicitudes normales y solicitudes AJAX.
     */
    public function index(Request $request)
    {
        // Si es una solicitud AJAX, retorna un JSON con las categorías
        if ($request->ajax()) {
            return response()->json(Category::all(), 200);
        }

        // Recuperar todas las categorías para mostrar en la vista
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }

    /**
     * Mostrar el formulario para crear una nueva categoría.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Guardar una nueva categoría.
     * Soporta solicitudes normales y solicitudes AJAX.
     */
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        try {
            // Crear la nueva categoría
            $category = Category::create($validated);

            // Si es una solicitud AJAX, devolver la nueva categoría como JSON
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'category' => $category,
                ]);
            }

            // Si no es una solicitud AJAX, redirigir con un mensaje de éxito
            return redirect()->route('categories.index')->with('success', 'Categoría creada con éxito.');
        } catch (\Exception $e) {
            // Manejo de errores en caso de que la creación falle
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al guardar la categoría.',
                ], 500);
            }

            return redirect()->route('categories.index')->withErrors('Error al guardar la categoría.');
        }
    }

    /**
     * Mostrar una categoría específica.
     */
    public function show(Category $category)
    {
        return view('categories.show', compact('category'));
    }

    /**
     * Mostrar el formulario para editar una categoría existente.
     */
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * Actualizar una categoría existente.
     * Soporta solicitudes normales y solicitudes AJAX.
     */
    public function update(Request $request, Category $category)
    {
        // Validar los datos del formulario
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Actualizar la categoría con los nuevos datos
        $category->update($validated);

        // Si es una solicitud AJAX, devolver JSON
        if ($request->ajax()) {
            return response()->json(['success' => true, 'category' => $category], 200);
        }

        // Respuesta estándar para solicitudes tradicionales
        return redirect()->route('categories.index')->with('success', 'Categoría actualizada con éxito');
    }

    /**
     * Eliminar una categoría.
     * Soporta solicitudes normales y solicitudes AJAX.
     */
    public function destroy(Request $request, Category $category)
    {
        // Eliminar la categoría
        $category->delete();

        // Si es una solicitud AJAX, devolver JSON
        if ($request->ajax()) {
            return response()->json(['success' => true], 200);
        }

        // Respuesta estándar para solicitudes tradicionales
        return redirect()->route('categories.index')->with('success', 'Categoría eliminada con éxito');
    }
    public function getProducts(Category $category)
{
    // Obtener los productos asociados a la categoría
    $products = $category->products;  // Asegúrate de que tu relación entre Category y Product esté bien definida

    return response()->json([
        'products' => $products
    ]);
}

}
