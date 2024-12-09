<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Mostrar una lista de todos los proveedores.
     * Soporta solicitudes normales y solicitudes AJAX.
     */
    public function index(Request $request)
    {
        // Si es una solicitud AJAX, retorna un JSON con los proveedores
        if ($request->ajax()) {
            return response()->json(Supplier::all());
        }

        // Puedes optar por no cargar los productos si no los necesitas inmediatamente
        $suppliers = Supplier::with('products')->get(); // Traer productos si es necesario

        return view('suppliers.index', compact('suppliers'));
    }

    /**
     * Mostrar el formulario para crear un nuevo proveedor.
     */
    public function create()
    {
        return view('suppliers.create');
    }

    /**
     * Guardar un nuevo proveedor.
     * Soporta solicitudes normales y solicitudes AJAX.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $supplier = Supplier::create($validated);

        // Si es una solicitud AJAX, retorna una respuesta JSON
        if ($request->ajax()) {
            return response()->json(['success' => true, 'supplier' => $supplier]);
        }

        return redirect()->route('suppliers.index')->with('success', 'Proveedor creado con éxito.');
    }

    /**
     * Mostrar el formulario para editar un proveedor existente.
     */
    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    /**
     * Actualizar un proveedor existente.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $supplier->update($validated);

        return redirect()->route('suppliers.index')->with('success', 'Proveedor actualizado con éxito.');
    }

    /**
     * Eliminar un proveedor existente.
     */
    public function destroy(Supplier $supplier)
    {
    $supplier->delete(); // Esto automáticamente establecerá 'supplier_id' a null en productos
    return redirect()->route('suppliers.index')->with('success', 'Proveedor eliminado con éxito.');
    }

}
