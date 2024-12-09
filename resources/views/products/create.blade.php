@extends('layouts.app')

@section('page-title', 'Crear Producto')

@push('styles')
<style>
    .form-control, .form-select {
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        transition: all 0.3s ease;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .form-control:focus, .form-select:focus {
        border-color: #ff4081;
        box-shadow: 0 0 0 0.2rem rgba(255, 64, 129, 0.25);
    }

    .text-danger {
        color: red;
        font-size: 0.85em;
        margin-top: 5px;
    }

    .image-preview {
        max-width: 150px; /* Tamaño adecuado para previsualización */
        max-height: 150px;
        margin-top: 10px;
        border-radius: 8px;
        display: none; /* Por defecto no se muestra */
        object-fit: cover; /* Ajusta la imagen para que no se deforme */
        border: 1px solid #ddd;
        transition: opacity 0.3s ease;
    }

    .form-section {
        margin-bottom: 2rem;
    }

    .btn-add {
        display: flex;
        align-items: center;
        justify-content: center; /* Centrar horizontalmente */
    }

    .btn-add i {
        margin-right: 5px;
    }

    /* Ajustar el tamaño del botón si es necesario */
    .btn-add {
        min-width: 100px; /* Ajusta según tus necesidades */
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="card mx-2 mx-md-4 mt-4">
        <div class="card-header pb-0">
            <h6 class="mb-0">Crear Nuevo Producto</h6>
        </div>
        <hr class="dark horizontal my-0">
        <div class="card-body">
            <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data" id="productForm">
                @csrf

                <!-- Detalles del Producto -->
                <div class="form-section">
                    <h5 class="mb-3">Detalles del Producto</h5>
                    <div class="row g-3">
                        <!-- Nombre del Producto -->
                        <div class="col-md-6">
                            <label for="name" class="form-label">Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Categoría -->
                        <div class="col-md-6">
                            <label for="category_id" class="form-label">Categoría <span class="text-danger">*</span></label>
                            <div class="d-flex">
                                <select class="form-select" id="category_id" name="category_id" required>
                                    <option value="" selected disabled>Selecciona una categoría</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-secondary btn-add ms-3" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                                    <i class="fas fa-plus"></i> Agregar
                                </button>
                            </div>
                            @error('category_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Proveedor -->
                        <div class="col-md-6">
                            <label for="supplier_id" class="form-label">Proveedor <span class="text-danger">*</span></label>
                            <div class="d-flex">
                                <select class="form-select" id="supplier_id" name="supplier_id" required>
                                    <option value="" selected disabled>Selecciona un proveedor</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <!-- Botón para agregar un nuevo proveedor -->
                                <button type="button" class="btn btn-secondary btn-add ms-3" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
                                    <i class="fas fa-plus"></i> Agregar
                                </button>
                            </div>
                            @error('supplier_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Otros campos si es necesario -->
                        <!-- Puedes agregar más campos aquí -->
                    </div>
                </div>

                <!-- Precios y Stock -->
                <div class="form-section">
                    <h5 class="mb-3">Precios y Stock</h5>
                    <div class="row g-3">
                        <!-- Precio de Compra -->
                        <div class="col-md-3">
                            <label for="purchase_price" class="form-label">Precio de Compra <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control" id="purchase_price" name="purchase_price" value="{{ old('purchase_price') }}" required>
                            @error('purchase_price')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Precio de Venta -->
                        <div class="col-md-3">
                            <label for="sale_price" class="form-label">Precio de Venta <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control" id="sale_price" name="sale_price" value="{{ old('sale_price') }}" required>
                            @error('sale_price')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Stock -->
                        <div class="col-md-3">
                            <label for="stock" class="form-label">Stock <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="stock" name="stock" value="{{ old('stock') }}" required>
                            @error('stock')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Stock Mínimo -->
                        <div class="col-md-3">
                            <label for="stock_min" class="form-label">Stock Mínimo <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="stock_min" name="stock_min" value="{{ old('stock_min') }}" required>
                            @error('stock_min')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Imagen del Producto -->
                <div class="form-section">
                    <h5 class="mb-3">Imagen del Producto</h5>
                    <div class="row g-3 align-items-center">
                        <div class="col-md-6">
                            <label for="image" class="form-label">Subir Imagen</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            @error('image')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 text-center">
                            <img id="imagePreview" class="image-preview" src="" alt="Vista previa de imagen">
                        </div>
                    </div>
                </div>

                <!-- Botón Guardar -->
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary d-flex align-items-center">
                        <i class="fas fa-save me-2"></i> Guardar Producto
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Agregar Categoría -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="categoryForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryModalLabel">Agregar Nueva Categoría</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="category_name" class="form-label">Nombre de Categoría <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="category_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="category_description" class="form-label">Descripción</label>
                        <textarea class="form-control" id="category_description" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success d-flex align-items-center">
                        <i class="fas fa-plus me-2"></i> Guardar Categoría
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Agregar Proveedor -->
<div class="modal fade" id="addSupplierModal" tabindex="-1" aria-labelledby="addSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="supplierForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addSupplierModalLabel">Agregar Nuevo Proveedor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="supplier_name" class="form-label">Nombre del Proveedor <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="supplier_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="supplier_contact" class="form-label">Contacto <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="supplier_contact" name="contact_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="supplier_email" class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="supplier_email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="supplier_phone" class="form-label">Teléfono <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="supplier_phone" name="phone" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success d-flex align-items-center">
                        <i class="fas fa-plus me-2"></i> Guardar Proveedor
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Referencias a los elementos de imagen
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('imagePreview');

        // Manejar cambio de imagen
        imageInput.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    imagePreview.src = e.target.result; // Establece la previsualización
                    imagePreview.style.display = 'block'; // Muestra la previsualización
                    imagePreview.style.opacity = '1';
                };
                reader.readAsDataURL(this.files[0]); // Lee el archivo de imagen
            } else {
                imagePreview.style.display = 'none'; // Oculta la previsualización
                imagePreview.src = ''; // Limpia la fuente de la imagen
                imagePreview.style.opacity = '0';
            }
        });

        // Manejo de formularios de categorías y proveedores
        function handleFormSubmit(formId, route, updateFunction) {
            const form = document.getElementById(formId);
            form.addEventListener('submit', function (event) {
                event.preventDefault();
                const formData = new FormData(this);

                fetch(route, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateFunction(data);
                        form.reset();
                        const modal = bootstrap.Modal.getInstance(form.closest('.modal'));
                        modal.hide();
                        showNotification(data.message, 'success');
                    } else {
                        showNotification(data.message || 'Error en la operación', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Ocurrió un error en la operación', 'error');
                });
            });
        }

        // Actualizar el select de categorías
        function updateCategories(data) {
            const categorySelect = document.getElementById('category_id');
            const newOption = document.createElement('option');
            newOption.value = data.category.id;
            newOption.textContent = data.category.name;
            newOption.selected = true;
            categorySelect.appendChild(newOption);
        }

        // Actualizar el select de proveedores
        function updateSuppliers(data) {
            const supplierSelect = document.getElementById('supplier_id');
            const newOption = document.createElement('option');
            newOption.value = data.supplier.id;
            newOption.textContent = data.supplier.name;
            newOption.selected = true;
            supplierSelect.appendChild(newOption);
        }

        // Autocalcular el precio de venta basado en el precio de compra
        document.getElementById('purchase_price').addEventListener('input', function () {
            const purchasePrice = parseFloat(this.value);
            if (!isNaN(purchasePrice)) {
                const salePrice = purchasePrice * 1.3;
                document.getElementById('sale_price').value = salePrice.toFixed(2);
            }
        });

        // Inicializar el manejo de formularios
        handleFormSubmit('categoryForm', '{{ route("products.createCategory") }}', updateCategories);
        handleFormSubmit('supplierForm', '{{ route("products.createSupplier") }}', updateSuppliers);
    });

    // Función para mostrar notificaciones
    function showNotification(message, type = 'success') {
        if (window.Swal) {
            Swal.fire({
                title: type === 'success' ? '¡Éxito!' : 'Error',
                text: message,
                icon: type,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
        } else {
            alert(message);
        }
    }
</script>
@endpush
