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
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
    ]);

    try {
        $category = Category::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'category' => $category,
            ]);
        }

        return redirect()->route('categories.index')->with('success', 'Categoría creada con éxito.');
    } catch (\Exception $e) {
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

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
        $category->delete();

        // Si es una solicitud AJAX, devolver JSON
        if ($request->ajax()) {
            return response()->json(['success' => true], 200);
        }

        // Respuesta estándar para solicitudes tradicionales
        return redirect()->route('categories.index')->with('success', 'Categoría eliminada con éxito');
    }
}
