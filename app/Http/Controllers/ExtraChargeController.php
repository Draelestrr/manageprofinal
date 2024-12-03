<?php

namespace App\Http\Controllers;

use App\Models\ExtraCharge;
use Illuminate\Http\Request;

class ExtraChargeController extends Controller
{
    /**
     * Mostrar una lista de todos los cargos adicionales.
     */
    public function index()
    {
        $extraCharges = ExtraCharge::all();
        return view('extra-charges.index', compact('extraCharges'));
    }

    /**
     * Mostrar el formulario para crear un nuevo cargo adicional.
     */
    public function create()
    {
        return view('extra-charges.create');
    }

    /**
     * Guardar un nuevo cargo adicional.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'expense_id' => 'required|exists:expenses,id',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        ExtraCharge::create($validated);

        return redirect()->route('extra-charges.index')->with('success', 'Cargo adicional creado con éxito');
    }

    /**
     * Mostrar un cargo adicional específico.
     */
    public function show(ExtraCharge $extraCharge)
    {
        return view('extra-charges.show', compact('extraCharge'));
    }

    /**
     * Mostrar el formulario para editar un cargo adicional existente.
     */
    public function edit(ExtraCharge $extraCharge)
    {
        return view('extra-charges.edit', compact('extraCharge'));
    }

    /**
     * Actualizar un cargo adicional existente.
     */
    public function update(Request $request, ExtraCharge $extraCharge)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        $extraCharge->update($validated);

        return redirect()->route('extra-charges.index')->with('success', 'Cargo adicional actualizado con éxito');
    }

    /**
     * Eliminar un cargo adicional.
     */
    public function destroy(ExtraCharge $extraCharge)
    {
        $extraCharge->delete();

        return redirect()->route('extra-charges.index')->with('success', 'Cargo adicional eliminado con éxito');
    }
}
