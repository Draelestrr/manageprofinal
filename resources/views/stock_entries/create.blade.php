@extends('layouts.app')

@section('page-title', 'Ingresar Stock')

@section('content')
<div class="container-fluid py-4">
    <div class="card card-body mx-2 mx-md-4 mt-4">
        <div class="row gx-4 mb-2">
            <div class="col-auto my-auto">
                <div class="h-100">
                    <h5 class="mb-1">Ingresar Stock de Producto</h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulario para Ingresar Stock -->
    <div class="container-fluid px-2 px-md-4 mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h6>Buscar Producto para Ingresar Stock</h6>
                        <input type="text" id="productSearch" class="form-control mt-3" placeholder="Buscar producto por nombre o categoría...">
                        <div id="productSuggestions" class="list-group mt-2" style="display:none;"></div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-body">
                        <form id="stockForm" action="{{ route('stock_entries.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Categoría</th>
                                        <th>Cantidad</th>
                                        <th>Precio de Compra</th>
                                        <th>Subtotal</th>
                                        <th>Eliminar</th>
                                    </tr>
                                </thead>
                                <tbody id="productList">
                                    <!-- Aquí se agregarán los productos seleccionados -->
                                </tbody>
                            </table>

                            <div class="mt-4">
                                <h6>Total: $<span id="total">0.00</span></h6>
                            </div>

                            <div class="mt-3">
                                <label for="document" class="form-label">Subir Boleta o Factura (opcional)</label>
                                <input type="file" id="document" name="document" class="form-control" accept="image/*,application/pdf">
                            </div>

                            <button type="button" id="previewModalButton" class="btn btn-warning mt-4" data-bs-toggle="modal" data-bs-target="#confirmModal">Previsualizar</button>
                            <button type="submit" class="btn btn-primary mt-4">Guardar Stock</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Confirmar Ingreso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>El total de este ingreso es: $<span id="modalTotal">0.00</span></p>
                <p>¿Deseas confirmar el ingreso de stock?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" id="confirmButton" class="btn btn-success">Confirmar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
    let total = 0;

    // Buscar productos
    $('#productSearch').on('input', function () {
    const query = $(this).val();
    if (query.length > 2) {
        $.ajax({
            url: '/buscar-productos',
            type: 'GET',
            data: { query },
            success: function (response) {
                if (response && Array.isArray(response)) {
                    $('#productSuggestions').empty().show();
                    response.forEach(product => {
                        // Formatear correctamente la categoría en la sugerencia
                        const category = product.category ? product.category : 'Sin categoría';
                        $('#productSuggestions').append(`
                            <div class="list-group-item suggestion"
                                 data-id="${product.id}"
                                 data-name="${product.name}"
                                 data-category="${category}"
                                 data-price="${product.purchase_price}">
                                ${product.name} (${category})
                            </div>
                        `);
                    });
                }
            },
            error: function (error) {
                console.error('Error al buscar productos:', error);
            }
        });
    } else {
        $('#productSuggestions').hide();
    }
});


    // Agregar producto a la lista
    $('#productSuggestions').on('mousedown', '.suggestion', function () {
        const id = $(this).data('id');
        const name = $(this).data('name');
        const category = $(this).data('category');
        let price = parseFloat($(this).data('price')); // Convertir el precio a número

        if (isNaN(price)) price = 0; // Si el precio no es válido, asignar 0 por defecto

        $('#productList').append(`
            <tr data-id="${id}">
                <td>${name}</td>
                <td>${category}</td>
                <td><input type="number" name="products[${id}][quantity]" class="form-control quantity" min="1" value="1"></td>
                <td><input type="number" name="products[${id}][purchase_price]" class="form-control price" min="0" step="0.01" value="${price.toFixed(2)}"></td>
                <td class="subtotal">$${price.toFixed(2)}</td>
                <td><button type="button" class="btn btn-danger btn-sm remove-product">Eliminar</button></td>
            </tr>
        `);

        calculateTotal();
        $('#productSearch').val('');
        $('#productSuggestions').hide();
    });

    // Calcular total
    function calculateTotal() {
        total = 0;
        $('#productList tr').each(function () {
            const quantity = parseInt($(this).find('.quantity').val()) || 0;
            const price = parseFloat($(this).find('.price').val()) || 0;
            const subtotal = quantity * price;
            $(this).find('.subtotal').text(`$${subtotal.toFixed(2)}`);
            total += subtotal;
        });
        $('#total').text(total.toFixed(2));
        $('#modalTotal').text(total.toFixed(2));
    }

    // Actualizar total al cambiar cantidad o precio
    $(document).on('input', '.quantity, .price', calculateTotal);

    // Eliminar producto
    $(document).on('click', '.remove-product', function () {
        $(this).closest('tr').remove();
        calculateTotal();
    });

    // Confirmar ingreso
    $('#confirmButton').on('click', function () {
        $('#stockForm').submit();
    });
});

</script>
@endpush
