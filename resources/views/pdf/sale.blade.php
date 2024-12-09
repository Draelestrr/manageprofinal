<!-- resources/views/sales/invoice.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Comprobante de Venta</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; }
        .details, .products { width: 100%; margin-bottom: 20px; }
        .details th, .products th { text-align: left; }
        .products th, .products td { border-bottom: 1px solid #ddd; padding: 8px; }
        .total { text-align: right; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Factura de Venta</h1>
        <p>Fecha: {{ $sale->created_at->format('d/m/Y') }}</p>
    </div>
    <table class="details">
        <tr>
            <th>Cliente:</th>
            <td>{{ $sale->customer->name }}</td>
        </tr>
        <tr>
            <th>Email:</th>
            <td>{{ $sale->customer->email }}</td>
        </tr>
    </table>
    <table class="products">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->products as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ $product->pivot->quantity }}</td>
                <td>${{ number_format($product->pivot->unit_price, 2) }}</td>
                <td>${{ number_format($product->pivot->subtotal, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <p class="total">Total: ${{ number_format($sale->total, 2) }}</p>
</body>
</html>
