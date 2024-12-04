@extends('layouts.app')

@section('page-title', 'Crear Producto')

@push('styles')
<style>
  .form-control, .form-select {
    border: 2px solid #ccc;
    border-radius: 8px;
    transition: all 0.3s ease;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  }

  .form-control:focus, .form-select:focus {
    border-color: #ff4081;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  }

  .text-danger {
    color: red;
    font-size: 0.85em;
    margin-top: 5px;
  }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
  <div class="card card-body mx-2 mx-md-4 mt-4">
    <div class="row gx-4 mb-2">
      <div class="col-auto my-auto">
        <h5 class="mb-1">Crear Nuevo Producto</h5>
      </div>
    </div>
  </div>

  <div class="container-fluid px-2 px-md-4 mt-4">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header pb-0">
            <h6>Información del Producto</h6>
          </div>
          <hr class="dark horizontal my-0">
          <div class="card-body">
            <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
              @csrf
              <!-- Nombre -->
              <div class="mb-3">
                <label for="name" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                @error('name')
                  <div class="text-danger">{{ $message }}</div>
                @enderror
              </div>

              <!-- Categoría -->
              <div class="mb-3">
                <label for="category_id" class="form-label">Categoría</label>
                <div class="input-group">
                  <select class="form-select" id="category_id" name="category_id" required>
                    <option value="" selected disabled>Selecciona una categoría</option>
                    @foreach($categories as $category)
                      <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                  </select>
                  <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                    <i class="fas fa-plus"></i> Agregar
                  </button>
                </div>
                @error('category_id')
                  <div class="text-danger">{{ $message }}</div>
                @enderror
              </div>

              <!-- Proveedor -->
              <div class="mb-3">
                <label for="supplier_id" class="form-label">Proveedor</label>
                <div class="input-group">
                  <select class="form-select" id="supplier_id" name="supplier_id" required>
                    <option value="" selected disabled>Selecciona un proveedor</option>
                    @foreach($suppliers as $supplier)
                      <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                    @endforeach
                  </select>
                  <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
                    <i class="fas fa-plus"></i> Agregar
                  </button>
                </div>
                @error('supplier_id')
                  <div class="text-danger">{{ $message }}</div>
                @enderror
              </div>

              <!-- Precios y Stock -->
              <div class="row mb-3">
                <!-- Precio de Compra -->
                <div class="col-md-4">
                  <label for="purchase_price" class="form-label">Precio de Compra</label>
                  <input type="number" step="0.01" class="form-control" id="purchase_price" name="purchase_price" value="{{ old('purchase_price') }}" required>
                  @error('purchase_price')
                    <div class="text-danger">{{ $message }}</div>
                  @enderror
                </div>

                <!-- Precio de Venta -->
                <div class="col-md-4">
                  <label for="sale_price" class="form-label">Precio de Venta</label>
                  <input type="number" step="0.01" class="form-control" id="sale_price" name="sale_price" value="{{ old('sale_price') }}" required>
                  @error('sale_price')
                    <div class="text-danger">{{ $message }}</div>
                  @enderror
                </div>

                <!-- Stock -->
                <div class="col-md-4">
                  <label for="stock" class="form-label">Stock</label>
                  <input type="number" class="form-control" id="stock" name="stock" value="{{ old('stock') }}" required>
                  @error('stock')
                    <div class="text-danger">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <!-- Stock Mínimo y Imagen -->
              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="stock_min" class="form-label">Stock Mínimo</label>
                  <input type="number" class="form-control" id="stock_min" name="stock_min" value="{{ old('stock_min') }}" required>
                  @error('stock_min')
                    <div class="text-danger">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-6">
                  <label for="image" class="form-label">Imagen del Producto</label>
                  <input type="file" class="form-control" id="image" name="image" accept="image/*">
                  @error('image')
                    <div class="text-danger">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <button type="submit" class="btn btn-primary">Guardar Producto</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Agregar Categoría -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addCategoryModalLabel">Agregar Nueva Categoría</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="categoryForm">
          @csrf
          <div class="mb-3">
            <label for="category_name" class="form-label">Nombre de Categoría</label>
            <input type="text" class="form-control" id="category_name" name="name" required>
          </div>
          <div class="mb-3">
            <label for="category_description" class="form-label">Descripción</label>
            <textarea class="form-control" id="category_description" name="description"></textarea>
          </div>
          <button type="submit" class="btn btn-primary">Guardar Categoría</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal Agregar Proveedor -->
<div class="modal fade" id="addSupplierModal" tabindex="-1" aria-labelledby="addSupplierModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addSupplierModalLabel">Agregar Nuevo Proveedor</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="supplierForm">
          @csrf
          <div class="mb-3">
            <label for="supplier_name" class="form-label">Nombre del Proveedor</label>
            <input type="text" class="form-control" id="supplier_name" name="name" required>
          </div>
          <div class="mb-3">
            <label for="supplier_contact" class="form-label">Contacto</label>
            <input type="text" class="form-control" id="supplier_contact" name="contact_name" required>
          </div>
          <div class="mb-3">
            <label for="supplier_email" class="form-label">Correo Electrónico</label>
            <input type="email" class="form-control" id="supplier_email" name="email" required>
          </div>
          <div class="mb-3">
            <label for="supplier_phone" class="form-label">Teléfono</label>
            <input type="text" class="form-control" id="supplier_phone" name="phone" required>
          </div>
          <button type="submit" class="btn btn-primary">Guardar Proveedor</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Función para manejar el envío de formularios ajax
        function handleFormSubmit(formId, route, updateFunction) {
            const form = document.getElementById(formId);
            form.addEventListener('submit', function (event) {
                event.preventDefault();
                const formData = new FormData(this);

                fetch(route, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en la solicitud');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Llamar a la función de actualización
                        updateFunction();

                        // Resetear el formulario
                        form.reset();

                        // Cerrar el modal
                        const modalElement = form.closest('.modal');
                        const modal = bootstrap.Modal.getInstance(modalElement);
                        if (modal) {
                            modal.hide();
                        }

                        // Mostrar notificación de éxito
                        showNotification(data.message || 'Operación realizada con éxito', 'success');
                    } else {
                        // Mostrar error
                        showNotification(data.message || 'Error en la operación', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Ocurrió un error', 'error');
                });
            });
        }

        // Función para actualizar categorías
        function updateCategories() {
            fetch('/categories', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                const categorySelect = document.getElementById('category_id');
                categorySelect.innerHTML = '<option value="" selected disabled>Selecciona una categoría</option>';

                data.forEach(category => {
                    const option = document.createElement('option');
                    option.value = category.id;
                    option.textContent = category.name;
                    categorySelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error al actualizar categorías:', error);
                showNotification('Error al cargar categorías', 'error');
            });
        }

        // Función para actualizar proveedores
        function updateSuppliers() {
            fetch('/suppliers', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                const supplierSelect = document.getElementById('supplier_id');
                supplierSelect.innerHTML = '<option value="" selected disabled>Selecciona un proveedor</option>';

                data.forEach(supplier => {
                    const option = document.createElement('option');
                    option.value = supplier.id;
                    option.textContent = supplier.name;
                    supplierSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error al actualizar proveedores:', error);
                showNotification('Error al cargar proveedores', 'error');
            });
        }

        // Configurar manejadores de formularios
        handleFormSubmit('categoryForm', '/categories', updateCategories);
        handleFormSubmit('supplierForm', '/suppliers', updateSuppliers);

        // Función para calcular precio de venta automáticamente
        document.getElementById('purchase_price').addEventListener('input', function() {
            const purchasePrice = parseFloat(this.value);
            if (!isNaN(purchasePrice)) {
                const salePrice = purchasePrice * 1.3; // Margen de ganancia del 30%
                document.getElementById('sale_price').value = salePrice.toFixed(2);
            }
        });

        // Función de notificación (asegúrate de que esté definida en tu layout)
        function showNotification(message, type = 'success') {
            // Implementación de notificación
            // Puedes usar toastr, una función personalizada o alert como fallback
            if (window.toastr && window.toastr[type]) {
                window.toastr[type](message);
            } else {
                alert(message);
            }
        }
    });
</script>
@endpush
