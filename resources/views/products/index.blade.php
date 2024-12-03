@extends('layouts.app')

@section('page-title', 'Productos')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Lista de Productos</h6>
                        <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm">Crear Producto</a>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nombre</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Categoría</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Proveedor</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Precio de Compra</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Precio de Venta</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Stock</th>
                                    <th class="text-secondary opacity-7">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div>
                                                @if($product->image_path)
                                                <img src="{{ asset('storage/' . $product->image_path) }}" class="avatar avatar-sm me-3" alt="product_image">
                                                @endif
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $product->name }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $product->category->name }}</p>
                                    </td>
                                    <td>
                                        @if($product->suppliers->isNotEmpty())
                                        @foreach($product->suppliers as $supplier)
                                        <p class="text-xs font-weight-bold mb-0">{{ $supplier->name }}</p>
                                        @endforeach
                                        @else
                                        <p class="text-xs font-weight-bold mb-0">No asignado</p>
                                        @endif
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">${{ number_format($product->purchase_price, 2) }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">${{ number_format($product->sale_price, 2) }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $product->stock }}</p>
                                    </td>
                                    <td class="align-middle text-center">
                                        <!-- Botón Editar -->
                                        <button class="btn btn-success btn-sm edit-button" data-bs-toggle="modal" data-bs-target="#editProductModal" data-id="{{ $product->id }}" data-name="{{ $product->name }}" data-category_id="{{ $product->category_id }}" data-purchase_price="{{ $product->purchase_price }}" data-sale_price="{{ $product->sale_price }}" data-stock="{{ $product->stock }}" data-stock_min="{{ $product->stock_min }}" data-image_path="{{ asset('storage/' . $product->image_path) }}">
                                            Editar
                                        </button>

                                        <!-- Botón Eliminar -->
                                        <button class="btn btn-danger btn-sm delete-button" data-bs-toggle="modal" data-bs-target="#deleteProductModal" data-id="{{ $product->id }}" data-name="{{ $product->name }}">
                                            Eliminar
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-center">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Editar Producto -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editProductForm" method="POST" action="" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductModalLabel">Editar Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <!-- Campos del formulario de edición -->
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_category_id" class="form-label">Categoría</label>
                        <select class="form-select" id="edit_category_id" name="category_id" required>
                            <option value="" selected disabled>Selecciona una categoría</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_image" class="form-label">Imagen Actual</label>
                        <div>
                            <img id="current_image" src="" class="img-fluid mb-2" alt="product_image">
                        </div>
                        <label for="edit_image" class="form-label">Subir Nueva Imagen</label>
                        <input type="file" class="form-control" id="edit_image" name="image">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var editProductModal = document.getElementById('editProductModal');
        editProductModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            var name = button.getAttribute('data-name');
            var category_id = button.getAttribute('data-category_id');
            var image_path = button.getAttribute('data-image_path');

            var form = editProductModal.querySelector('#editProductForm');
            form.action = '/products/' + id;

            form.querySelector('#edit_name').value = name;
            form.querySelector('#edit_category_id').value = category_id;
            form.querySelector('#current_image').src = image_path;
        });
    });
</script>
@endpush
