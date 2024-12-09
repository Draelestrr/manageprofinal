<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resumen de Ingreso de Stock</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
    </style>
</head>
<body>
    <h2>Resumen de Ingreso de Stock</h2>
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($entries as $entry)
                <tr>
                    <td>{{ $entry->product->name }}</td>
                    <td>{{ $entry->quantity }}</td>
                    <td>${{ number_format($entry->purchase_price, 2) }}</td>
                    <td>${{ number_format($entry->quantity * $entry->purchase_price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <h3>Total: ${{ number_format($total, 2) }}</h3>
</body>
</html>
