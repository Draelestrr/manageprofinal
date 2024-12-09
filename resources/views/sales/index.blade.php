@extends('layouts.app')

@section('page-title', 'Lista de Ventas')

@section('content')
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header pb-0">
            <h6>Lista de Ventas</h6>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sales as $sale)
                        <tr>
                            <td>{{ $sale->customer->name }}</td> <!-- Nombre del cliente -->
                            <td>{{ $sale->created_at->format('Y-m-d H:i') }}</td>
                            <td>${{ number_format($sale->total, 2) }}</td>
                            <td>
                                <!-- Ver Detalles -->
                                <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#viewSaleModal" data-id="{{ $sale->id }}">Ver Detalles</button>

                                <!-- Editar Venta -->
                                <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editSaleModal" data-id="{{ $sale->id }}">Editar</button>

                                <!-- Ver PDF -->
                                <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#viewPdfModal" data-pdf="{{ Storage::url('sale/' . now()->format('Y-m-d') . '/sale-' . $sale->id . '.pdf') }}">Ver PDF</button>

                                <!-- Eliminar Venta -->
                                <button class="btn btn-danger btn-sm" data-id="{{ $sale->id }}" onclick="deleteSale({{ $sale->id }})">Eliminar</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: Ver Detalles -->
<div class="modal fade" id="viewSaleModal" tabindex="-1" aria-labelledby="viewSaleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles de la Venta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="saleDetailsBody">
                <!-- Aquí se mostrarán los detalles de la venta -->
                <p>Cargando detalles...</p>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Editar Venta -->
<div class="modal fade" id="editSaleModal" tabindex="-1" aria-labelledby="editSaleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!-- Hacer el modal más grande para contenido extenso -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Venta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="editSaleBody">
                <!-- Aquí se mostrarán los campos para editar la venta -->
                <p>Cargando formulario de edición...</p>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Ver PDF -->
<div class="modal fade" id="viewPdfModal" tabindex="-1" aria-labelledby="viewPdfModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl"> <!-- Hacer el modal extra grande para mejor visualización del PDF -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Factura de Venta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <iframe id="pdfViewer" src="" width="100%" height="600px"></iframe>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Obtener el token CSRF desde el meta tag
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        // Función para escapar HTML y prevenir XSS
        function escapeHtml(text) {
            if (!text) return '';
            return text
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

        // Ver detalles de la venta
        $('#viewSaleModal').on('show.bs.modal', function (event) {
            const button = $(event.relatedTarget);
            const saleId = button.data('id');

            // Mostrar mensaje de carga
            $('#saleDetailsBody').html('<p>Cargando detalles...</p>');

            $.ajax({
                url: `/sales/${saleId}`,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data.sale) {
                        const sale = data.sale;
                        let detailsHtml = `
                            <p><strong>Cliente:</strong> ${escapeHtml(sale.customer.name)}</p>
                            <p><strong>Fecha de la venta:</strong> ${new Date(sale.created_at).toLocaleString()}</p>
                            <p><strong>Total:</strong> $${parseFloat(sale.total).toFixed(2)}</p>
                            <h5>Productos:</h5>
                            <ul>`;

                        sale.products.forEach(product => {
                            detailsHtml += `
                                <li>${escapeHtml(product.name)} - ${product.pivot.quantity} x $${parseFloat(product.pivot.unit_price).toFixed(2)}</li>
                            `;
                        });

                        detailsHtml += '</ul>';
                        $('#saleDetailsBody').html(detailsHtml);
                    } else {
                        $('#saleDetailsBody').html('<p>No se encontraron detalles para esta venta.</p>');
                    }
                },
                error: function(xhr) {
                    console.error(xhr);
                    $('#saleDetailsBody').html('<p>Error al cargar los detalles de la venta.</p>');
                }
            });
        });

        // Editar detalles de la venta
        $('#editSaleModal').on('show.bs.modal', function (event) {
            const button = $(event.relatedTarget);
            const saleId = button.data('id');

            // Mostrar mensaje de carga
            $('#editSaleBody').html('<p>Cargando formulario de edición...</p>');

            $.ajax({
                url: `/sales/${saleId}/edit`,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data.sale) {
                        const sale = data.sale;
                        let editHtml = `
                            <form action="/sales/${sale.id}" method="POST">
                                <input type="hidden" name="_token" value="${csrfToken}">
                                <input type="hidden" name="_method" value="PUT">
                                <div class="form-group">
                                    <label for="customer">Cliente</label>
                                    <input type="text" class="form-control" id="customer" name="customer" value="${escapeHtml(sale.customer.name)}" required>
                                </div>
                                <div class="form-group">
                                    <label for="total">Total</label>
                                    <input type="number" step="0.01" class="form-control" id="total" name="total" value="${parseFloat(sale.total).toFixed(2)}" required>
                                </div>
                                <h5>Productos:</h5>
                                <ul id="products-list" class="list-unstyled">`;

                        sale.products.forEach(product => {
                            editHtml += `
                                <li id="product-${product.id}" class="mb-2">
                                    <div class="form-row align-items-center">
                                        <div class="col-5">
                                            <p class="mb-0">${escapeHtml(product.name)}</p>
                                        </div>
                                        <div class="col-3">
                                            <input type="number" class="form-control" name="products[${product.id}][quantity]" value="${product.pivot.quantity}" min="1">
                                        </div>
                                        <div class="col-3">
                                            <button type="button" class="btn btn-danger btn-sm remove-product" data-product-id="${product.id}">Eliminar</button>
                                        </div>
                                    </div>
                                </li>
                            `;
                        });

                        editHtml += `
                                </ul>
                                <button type="submit" class="btn btn-primary mt-3">Guardar cambios</button>
                            </form>
                        `;

                        $('#editSaleBody').html(editHtml);

                        // Agregar funcionalidad para eliminar productos en el modal
                        $('.remove-product').click(function() {
                            const productId = $(this).data('product-id');
                            $(`#product-${productId}`).remove();
                        });
                    } else {
                        $('#editSaleBody').html('<p>No se encontraron datos para esta venta.</p>');
                    }
                },
                error: function(xhr) {
                    console.error(xhr);
                    $('#editSaleBody').html('<p>Error al cargar el formulario de edición.</p>');
                }
            });
        });

        // Ver el PDF de la venta
        $('#viewPdfModal').on('show.bs.modal', function (event) {
            const button = $(event.relatedTarget);
            const pdfUrl = button.data('pdf');
            $('#pdfViewer').attr('src', pdfUrl);
        });
    });

    // Eliminar venta y reponer stock
    function deleteSale(saleId) {
        if (confirm('¿Estás seguro de que quieres eliminar esta venta?')) {
            $.ajax({
                url: `/sales/${saleId}`,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    alert('Venta eliminada correctamente.');
                    location.reload(); // Recargar la página para reflejar los cambios
                },
                error: function(xhr) {
                    alert('Error al eliminar la venta.');
                }
            });
        }
    }
</script>
@endpush
