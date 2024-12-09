@extends('layouts.app')

@section('page-title', 'Productos')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <!-- Header de la Tarjeta -->
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Lista de Productos</h6>
                        <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-2"></i> Crear Producto
                        </a>
                    </div>
                </div>

                <!-- Cuerpo de la Tarjeta con Alertas y Tabla -->
                <div class="card-body px-0 pt-0 pb-2">
                    <!-- Alertas de Sesión -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mx-4" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show mx-4" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                        </div>
                    @endif

                    <!-- Tabla de Productos -->
                    <div class="table-responsive p-0 mx-4">
                        <table id="productsTable" class="table table-striped table-hover align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Imagen</th>
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
                                @forelse($products as $product)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <img src="{{ $product->image_path ? asset('storage/' . $product->image_path) : asset('assets/img/producto-sin-imagen.jpg') }}" class="avatar me-3" alt="Imagen de {{ $product->name }}">
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $product->name }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $product->category->name }}</p>
                                    </td>
                                    <td>
                                        @if($product->supplier)
                                            <span class="badge bg-secondary text-light badge-supplier">{{ $product->supplier->name }}</span>
                                        @else
                                            <span class="badge bg-warning text-dark">No asignado</span>
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
                                        <!-- Botón Ver -->
                                        <button class="btn btn-info btn-sm view-button"
                                                data-bs-toggle="modal"
                                                data-bs-target="#viewProductModal"
                                                data-id="{{ $product->id }}"
                                                data-name="{{ $product->name }}"
                                                data-category="{{ $product->category->name }}"
                                                data-supplier="{{ $product->supplier ? $product->supplier->name : '' }}"
                                                data-purchase_price="{{ number_format($product->purchase_price, 2) }}"
                                                data-sale_price="{{ number_format($product->sale_price, 2) }}"
                                                data-stock="{{ $product->stock }}"
                                                data-stock_min="{{ $product->stock_min }}"
                                                data-image_path="{{ $product->image_path ? $product->image_path : '' }}">
                                            <i class="fas fa-eye custom-icon-size"></i>
                                        </button>


                                        <!-- Botón Editar -->
                                        <button class="btn btn-success btn-sm edit-button"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editProductModal"
                                                data-id="{{ $product->id }}"
                                                data-name="{{ $product->name }}"
                                                data-category_id="{{ $product->category_id }}"
                                                data-purchase_price="{{ $product->purchase_price }}"
                                                data-sale_price="{{ $product->sale_price }}"
                                                data-stock="{{ $product->stock }}"
                                                data-stock_min="{{ $product->stock_min }}"
                                                data-supplier_id="{{ $product->supplier ? $product->supplier->id : '' }}"
                                                data-image_path="{{ $product->image_path ? $product->image_path : '' }}">
                                            <i class="fas fa-edit custom-icon-size"></i>
                                        </button>


                                        <!-- Botón Eliminar -->
                                        <button class="btn btn-danger btn-sm delete-button"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteProductModal"
                                                data-id="{{ $product->id }}"
                                                data-name="{{ $product->name }}">
                                            <i class="fas fa-trash custom-icon-size"></i>
                                        </button>
                                    </td>


                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No hay productos registrados.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Ver Producto -->
<div class="modal fade" id="viewProductModal" tabindex="-1" aria-labelledby="viewProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles del Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <!-- Imagen del Producto -->
                    <div class="col-md-4 text-center">
                        <img id="view_image" src="" class="img-fluid rounded mb-3" alt="Imagen del Producto">
                    </div>
                    <!-- Detalles del Producto -->
                    <div class="col-md-8">
                        <h4 id="view_name"></h4>
                        <p><strong>Categoría:</strong> <span id="view_category"></span></p>
                        <p><strong>Proveedor:</strong> <span id="view_supplier"></span></p>
                        <p><strong>Precio de Compra:</strong> $<span id="view_purchase_price"></span></p>
                        <p><strong>Precio de Venta:</strong> $<span id="view_sale_price"></span></p>
                        <p><strong>Stock:</strong> <span id="view_stock"></span></p>
                        <p><strong>Stock Mínimo:</strong> <span id="view_stock_min"></span></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Editar Producto -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="editProductForm" method="POST" action="{{ route('products.update', 0) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Editar Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <!-- Nombre del Producto -->
                        <div class="col-md-6">
                            <label for="edit_name" class="form-label">Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>

                        <!-- Categoría -->
                        <div class="col-md-6">
                            <label for="edit_category_id" class="form-label">Categoría <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit_category_id" name="category_id" required>
                                <option value="" selected disabled>Selecciona una categoría</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Proveedor -->
                        <div class="col-md-6">
                            <label for="edit_supplier_id" class="form-label">Proveedor <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit_supplier_id" name="supplier_id" required>
                                <option value="" selected disabled>Selecciona un proveedor</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Precio de Compra -->
                        <div class="col-md-6">
                            <label for="edit_purchase_price" class="form-label">Precio de Compra <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control" id="edit_purchase_price" name="purchase_price" required>
                        </div>

                        <!-- Precio de Venta -->
                        <div class="col-md-6">
                            <label for="edit_sale_price" class="form-label">Precio de Venta <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control" id="edit_sale_price" name="sale_price" required>
                        </div>

                        <!-- Stock -->
                        <div class="col-md-6">
                            <label for="edit_stock" class="form-label">Stock <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="edit_stock" name="stock" required>
                        </div>

                        <!-- Stock Mínimo -->
                        <div class="col-md-6">
                            <label for="edit_stock_min" class="form-label">Stock Mínimo <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="edit_stock_min" name="stock_min" required>
                        </div>

                        <!-- Imagen Actual -->
                        <div class="col-md-6">
                            <label class="form-label">Imagen Actual</label>
                            <div>
                                <img id="current_image" src="" class="img-fluid mb-2 rounded" alt="Imagen Actual">
                            </div>
                        </div>

                        <!-- Subir Nueva Imagen -->
                        <div class="col-md-6">
                            <label for="edit_image" class="form-label">Subir Nueva Imagen</label>
                            <input type="file" class="form-control" id="edit_image" name="image" accept="image/*">
                            <img id="edit_imagePreview" class="image-preview" src="" alt="Vista previa de imagen">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary btn-save">
                        <i class="fas fa-save me-2"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Eliminar Producto -->
<div class="modal fade" id="deleteProductModal" tabindex="-1" aria-labelledby="deleteProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="deleteProductForm" method="POST" action="{{ route('products.destroy', 0) }}">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title">Eliminar Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas eliminar el producto <strong id="delete_product_name"></strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Inicializar DataTable
        $('#productsTable').DataTable({
            "paging": true,
            "pageLength": 10,
            "info": true,
            "searching": true,
            "columnDefs": [
                { "orderable": false, "targets": [0,3,7] }
            ],
            "language": {
                "search": "Buscar:",
                "paginate": {
                    "first": "Primero",
                    "last": "Último",
                    "next": "Siguiente",
                    "previous": "Anterior"
                },
                "emptyTable": "No hay datos disponibles en la tabla",
                "zeroRecords": "No se encontraron coincidencias",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                "infoEmpty": "Mostrando 0 registros",
                "infoFiltered": "(filtrado de _MAX_ registros totales)"
            }
        });

        // Modal de Ver Producto
        var viewProductModal = document.getElementById('viewProductModal');
        viewProductModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var name = button.getAttribute('data-name');
            var category = button.getAttribute('data-category');
            var supplier = button.getAttribute('data-supplier');
            var purchase_price = button.getAttribute('data-purchase_price');
            var sale_price = button.getAttribute('data-sale_price');
            var stock = button.getAttribute('data-stock');
            var stock_min = button.getAttribute('data-stock_min');
            var image_path = button.getAttribute('data-image_path');

            // Generar la URL de la imagen
            var imageUrl = image_path ? '{{ asset('storage') }}/' + image_path : '{{ asset('assets/img/producto-sin-imagen.jpg') }}';

            viewProductModal.querySelector('#view_name').textContent = name;
            viewProductModal.querySelector('#view_category').textContent = category;

            var supplierSpan = viewProductModal.querySelector('#view_supplier');
            if(supplier){
                supplierSpan.innerHTML = `<span class="badge bg-secondary text-light me-1">${supplier}</span>`;
            } else {
                supplierSpan.innerHTML = `<span class="badge bg-warning text-dark">No asignado</span>`;
            }

            viewProductModal.querySelector('#view_purchase_price').textContent = purchase_price;
            viewProductModal.querySelector('#view_sale_price').textContent = sale_price;
            viewProductModal.querySelector('#view_stock').textContent = stock;
            viewProductModal.querySelector('#view_stock_min').textContent = stock_min;
            viewProductModal.querySelector('#view_image').src = imageUrl;
        });

        // Modal de Editar Producto
        var editProductModal = document.getElementById('editProductModal');
        editProductModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            var name = button.getAttribute('data-name');
            var category_id = button.getAttribute('data-category_id');
            var purchase_price = button.getAttribute('data-purchase_price');
            var sale_price = button.getAttribute('data-sale_price');
            var stock = button.getAttribute('data-stock');
            var stock_min = button.getAttribute('data-stock_min');
            var supplier_id = button.getAttribute('data-supplier_id');
            var image_path = button.getAttribute('data-image_path');

            // Generar la URL de la imagen
            var imageUrl = image_path ? '{{ asset('storage') }}/' + image_path : '{{ asset('assets/img/producto-sin-imagen.jpg') }}';

            var form = editProductModal.querySelector('#editProductForm');
            form.action = "{{ url('products') }}/" + id;

            form.querySelector('#edit_name').value = name;
            form.querySelector('#edit_category_id').value = category_id;
            form.querySelector('#edit_purchase_price').value = purchase_price;
            form.querySelector('#edit_sale_price').value = sale_price;
            form.querySelector('#edit_stock').value = stock;
            form.querySelector('#edit_stock_min').value = stock_min;
            form.querySelector('#current_image').src = imageUrl;

            // Seleccionar el proveedor correspondiente
            var supplierSelect = form.querySelector('#edit_supplier_id');
            supplierSelect.value = supplier_id;

            // Resetear la previsualización de la nueva imagen
            form.querySelector('#edit_image').value = '';
            form.querySelector('#edit_imagePreview').style.display = 'none';
            form.querySelector('#edit_imagePreview').src = '';
        });

        // Previsualización de Nueva Imagen en el Modal de Editar
        var editImageInput = document.getElementById('edit_image');
        var editImagePreview = document.getElementById('edit_imagePreview');

        editImageInput.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    editImagePreview.src = e.target.result;
                    editImagePreview.style.display = 'block';
                };
                reader.readAsDataURL(this.files[0]);
            } else {
                editImagePreview.style.display = 'none';
                editImagePreview.src = '';
            }
        });

        // Modal para Eliminar Producto
        var deleteProductModal = document.getElementById('deleteProductModal');
        deleteProductModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            var name = button.getAttribute('data-name');

            var form = deleteProductModal.querySelector('#deleteProductForm');
            form.action = "{{ url('products') }}/" + id;

            deleteProductModal.querySelector('#delete_product_name').textContent = name;
        });
    });
</script>
@endpush
