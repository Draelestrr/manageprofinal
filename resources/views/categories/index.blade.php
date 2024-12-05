@extends('layouts.app')

@section('page-title', 'Categorías')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Categorías</h6>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCategoryModal">Nueva Categoría</button>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table id="categoriesTable" class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nombre</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Descripción</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categories as $category)
                                    <tr>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $category->name }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $category->description }}</p>
                                        </td>
                                        <td class="align-middle text-center">
                                            <!-- Ver Categoría -->
                                            <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewCategoryModal" data-id="{{ $category->id }}" data-name="{{ $category->name }}" data-description="{{ $category->description }}">
                                                Ver
                                            </button>
                                            <!-- Editar Categoría -->
                                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editCategoryModal" data-id="{{ $category->id }}" data-name="{{ $category->name }}" data-description="{{ $category->description }}">
                                                Editar
                                            </button>
                                            <!-- Eliminar Categoría -->
                                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteCategoryModal" data-id="{{ $category->id }}" data-name="{{ $category->name }}">
                                                Eliminar
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Crear Categoría -->
@include('categories.create-modal')

<!-- Modal Ver Categoría -->
@include('categories.view-modal')

<!-- Modal Editar Categoría -->
@include('categories.edit-modal')

<!-- Modal Eliminar Categoría -->
@include('categories.delete-modal')

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Inicializar DataTable para categorías
        $('#categoriesTable').DataTable({
            "paging": true, // Activar paginación
            "info": true,    // Activar información de la tabla
            "searching": true, // Activar búsqueda
            "ordering": true,  // Activar ordenamiento
            "language": {
                "search": "Buscar:",
                "emptyTable": "No hay datos disponibles en la tabla",
                "zeroRecords": "No se encontraron coincidencias",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                "infoEmpty": "Mostrando 0 registros",
                "infoFiltered": "(filtrado de _MAX_ registros totales)",
                "paginate": {
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            }
        });

        // Rellenar los datos en el modal "Ver Categoría"
        $('#viewCategoryModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var name = button.data('name');
            var description = button.data('description');

            var modal = $(this);
            modal.find('.modal-body #category_name').text(name);
            modal.find('.modal-body #category_description').text(description);
        });

        // Rellenar los datos en el modal "Editar Categoría"
        $('#editCategoryModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var name = button.data('name');
            var description = button.data('description');

            var modal = $(this);
            modal.find('#edit_category_id').val(id);
            modal.find('#edit_name').val(name);
            modal.find('#edit_description').val(description);
        });

        // Modal de Eliminar Categoría
        $('#deleteCategoryModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var name = button.data('name');
            var modal = $(this);

            // Configura el formulario para eliminar
            modal.find('#delete_category_name').text(name);
            modal.find('#deleteCategoryForm').attr('action', '/categories/' + id);

            // Verificar si la categoría tiene productos asociados
            $.ajax({
                url: '/api/categories/' + id + '/products', // Se espera que esta ruta retorne los productos asociados
                method: 'GET',
                success: function(response) {
                    if (response.products.length > 0) {
                        // Mostrar los productos asociados
                        $('#delete_products_message').show();
                        $('#delete_products_list').empty();
                        response.products.forEach(function(product) {
                            $('#delete_products_list').append('<li>' + product.name + '</li>');
                        });
                    } else {
                        $('#delete_products_message').hide();
                    }
                },
                error: function() {
                    $('#delete_products_message').hide();
                }
            });
        });
    });
</script>
@endpush
