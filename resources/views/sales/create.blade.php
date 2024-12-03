@extends('layouts.app')

@section('page-title', 'Ingresar Venta')

@section('content')
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header pb-0">
            <h6>Ingresar Venta</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('sales.store') }}" method="POST">
                @csrf
                <div class="row mb-3">
                    <label for="customer_id" class="col-md-2 col-form-label">Cliente</label>
                    <div class="col-md-10">
                        <select name="customer_id" id="customer_id" class="form-control" required>
                            <option value="">Seleccione un Cliente</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio de Venta</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>
                                        <input type="number"
                                               name="products[{{ $product->id }}][quantity]"
                                               min="1"
                                               value="1"
                                               class="form-control"
                                               required
                                               max="{{ $product->stock }}">
                                    </td>
                                    <td>
                                        <input type="number"
                                               name="products[{{ $product->id }}][sale_price]"
                                               value="{{ $product->sale_price }}"
                                               class="form-control"
                                               readonly>
                                    </td>
                                    <td>
                                        <input type="number"
                                               name="products[{{ $product->id }}][subtotal]"
                                               value="{{ $product->sale_price }}"
                                               class="form-control"
                                               readonly>
                                    </td>
                                    <input type="hidden" name="products[{{ $product->id }}][product_id]" value="{{ $product->id }}">
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="row mt-3">
                    <label for="total" class="col-md-2 col-form-label">Total</label>
                    <div class="col-md-10">
                        <input type="text" id="total" name="total" class="form-control" value="0" readonly>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mt-4">Registrar Venta</button>
            </form>
        </div>
    </div>
</div>

<script>
    // Script para calcular el subtotal y el total
    document.querySelectorAll('input[name^="products["]').forEach(input => {
        input.addEventListener('input', updateTotals);
    });

    function updateTotals() {
        let total = 0;
        document.querySelectorAll('tr').forEach(row => {
            const quantity = row.querySelector('input[name*="quantity"]').value;
            const salePrice = row.querySelector('input[name*="sale_price"]').value;
            const subtotalField = row.querySelector('input[name*="subtotal"]');

            if (quantity && salePrice) {
                const subtotal = quantity * salePrice;
                subtotalField.value = subtotal.toFixed(2);
                total += subtotal;
            }
        });

        document.getElementById('total').value = total.toFixed(2);
    }
</script>
@endsection
