<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    /**
     * Mostrar la lista de gastos.
     */
    public function index()
    {
        $expenses = Expense::with('extraCharges')->get();
        return view('expenses.index', compact('expenses'));
    }

    /**
     * Mostrar el formulario para crear un nuevo gasto.
     */
    public function create()
    {
        return view('expenses.create');
    }

    /**
     * Guardar un nuevo gasto en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'extra_charges' => 'nullable|array',
            'extra_charges.*.description' => 'required|string|max:255',
            'extra_charges.*.amount' => 'required|numeric|min:0',
        ]);

        $expense = Expense::create([
            'description' => $request->description,
            'amount' => $request->amount,
            'date' => $request->date,
            'user_id' => auth()->id(),
            'receipt_image_path' => $request->receipt_image_path,
        ]);

        if (!empty($request->extra_charges)) {
            foreach ($request->extra_charges as $charge) {
                $expense->extraCharges()->create($charge);
            }
        }

        return redirect()->route('expenses.index')->with('success', 'Gasto registrado con éxito.');
    }

    /**
     * Mostrar el formulario para editar un gasto existente.
     */
    public function edit(Expense $expense)
    {
        return view('expenses.edit', compact('expense'));
    }

    /**
     * Actualizar un gasto existente.
     */
    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
        ]);

        $expense->update($request->all());

        return redirect()->route('expenses.index')->with('success', 'Gasto actualizado con éxito.');
    }

    /**
     * Eliminar un gasto.
     */
    public function destroy(Expense $expense)
    {
        $expense->delete();

        return redirect()->route('expenses.index')->with('success', 'Gasto eliminado con éxito.');
    }
}
