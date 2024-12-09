<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::all();
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        // Validación de los datos
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:255',
        ]);

        // Crear el cliente
        $customer = Customer::create($request->all());

        return response()->json(['success' => 'Cliente creado exitosamente.', 'customer' => $customer]);
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $customer->update($request->all());

        return redirect()->route('customers.index')->with('success', 'Cliente actualizado con éxito.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Cliente eliminado con éxito.');
    }

    public function search(Request $request)
    {
        $search = $request->input('search');

        if (!$search) {
            return response()->json([], 400);
        }

        $customers = Customer::where('name', 'like', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%")
            ->orWhere('telephone', 'like', "%{$search}%")
            ->limit(10)
            ->get();

        return response()->json($customers);
    }
}
