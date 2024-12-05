@extends('layouts.app')

@section('page-title', 'Lista de Proveedores')

@section('content')
<div class="g-sidenav-show bg-gray-100">

        <div class="container-fluid py-4">
            <div class="card card-body mx-2 mx-md-4 mt-4">
                <div class="row gx-4 mb-2">
                    <div class="col-auto my-auto">
                        <div class="h-100">
                            <h5 class="mb-1">
                                Lista de Proveedores
                            </h5>
                        </div>
                    </div>
                    <div class="col-auto ms-auto">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createSupplierModal">
                            Agregar Proveedor
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tabla de Proveedores -->
            <div class="container-fluid px-2 px-md-4 mt-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <h6>Proveedores</h6>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-body">
                        <table id="suppliersTable" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Contacto</th>
                                    <th>Email</th>
                                    <th>Teléfono</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($suppliers as $supplier)
                                    <tr>
                                        <td>{{ $supplier->id }}</td>
                                        <td>{{ $supplier->name }}</td>
                                        <td>{{ $supplier->contact_name }}</td>
                                        <td>{{ $supplier->email }}</td>
                                        <td>{{ $supplier->phone }}</td>
                                        <td>
                                            <!-- Botón Ver -->
                                            <button class="btn btn-info btn-sm view-button"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#viewSupplierModal"
                                                    data-id="{{ $supplier->id }}"
                                                    data-name="{{ $supplier->name }}"
                                                    data-contact="{{ $supplier->contact_name }}"
                                                    data-email="{{ $supplier->email }}"
                                                    data-phone="{{ $supplier->phone }}"
                                                    data-address="{{ $supplier->address }}">
                                                <i class="fas fa-eye me-1"></i> Ver
                                            </button>

                                            <!-- Botón Editar -->
                                            <button class="btn btn-warning btn-sm edit-button"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editSupplierModal"
                                                    data-id="{{ $supplier->id }}"
                                                    data-name="{{ $supplier->name }}"
                                                    data-contact="{{ $supplier->contact_name }}"
                                                    data-email="{{ $supplier->email }}"
                                                    data-phone="{{ $supplier->phone }}"
                                                    data-address="{{ $supplier->address }}">
                                                <i class="fas fa-edit me-1"></i> Editar
                                            </button>

                                            <!-- Botón Eliminar -->
                                            <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este proveedor?')">
                                                    <i class="fas fa-trash me-1"></i> Eliminar
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Modal Ver Proveedor -->
<div class="modal fade" id="viewSupplierModal" tabindex="-1" aria-labelledby="viewSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewSupplierModalLabel">Detalles del Proveedor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Nombre:</strong> <span id="view_name"></span></p>
                <p><strong>Contacto:</strong> <span id="view_contact"></span></p>
                <p><strong>Email:</strong> <span id="view_email"></span></p>
                <p><strong>Teléfono:</strong> <span id="view_phone"></span></p>
                <p><strong>Dirección:</strong> <span id="view_address"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Editar Proveedor -->
<div class="modal fade" id="editSupplierModal" tabindex="-1" aria-labelledby="editSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editSupplierForm" method="POST" action="{{ route('suppliers.update', 0) }}">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editSupplierModalLabel">Editar Proveedor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Nombre del Proveedor -->
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Nombre del Proveedor</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>

                    <!-- Contacto -->
                    <div class="mb-3">
                        <label for="edit_contact" class="form-label">Contacto</label>
                        <input type="text" class="form-control" id="edit_contact" name="contact_name" required>
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="edit_email" name="email" required>
                    </div>

                    <!-- Teléfono -->
                    <div class="mb-3">
                        <label for="edit_phone" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="edit_phone" name="phone" required>
                    </div>

                    <!-- Dirección -->
                    <div class="mb-3">
                        <label for="edit_address" class="form-label">Dirección</label>
                        <textarea class="form-control" id="edit_address" name="address" rows="3"></textarea>
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

<!-- Modal Crear Proveedor -->
<div class="modal fade" id="createSupplierModal" tabindex="-1" aria-labelledby="createSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('suppliers.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createSupplierModalLabel">Agregar Nuevo Proveedor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Nombre del Proveedor -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre del Proveedor</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>

                    <!-- Contacto -->
                    <div class="mb-3">
                        <label for="contact_name" class="form-label">Contacto</label>
                        <input type="text" class="form-control" id="contact_name" name="contact_name" required>
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <!-- Teléfono -->
                    <div class="mb-3">
                        <label for="phone" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="phone" name="phone" required>
                    </div>

                    <!-- Dirección -->
                    <div class="mb-3">
                        <label for="address" class="form-label">Dirección</label>
                        <textarea class="form-control" id="address" name="address" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Proveedor</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Inicializar DataTable para proveedores
        $('#suppliersTable').DataTable({
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

        // Rellenar los datos en el modal "Ver Proveedor"
        $('#viewSupplierModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var name = button.data('name');
            var contact = button.data('contact');
            var email = button.data('email');
            var phone = button.data('phone');
            var address = button.data('address');

            var modal = $(this);
            modal.find('#view_name').text(name);
            modal.find('#view_contact').text(contact);
            modal.find('#view_email').text(email);
            modal.find('#view_phone').text(phone);
            modal.find('#view_address').text(address);
        });

        // Rellenar los datos en el modal "Editar Proveedor"
        $('#editSupplierModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var name = button.data('name');
            var contact = button.data('contact');
            var email = button.data('email');
            var phone = button.data('phone');
            var address = button.data('address');

            var modal = $(this);
            modal.find('#editSupplierForm').attr('action', '/suppliers/' + id);
            modal.find('#edit_name').val(name);
            modal.find('#edit_contact').val(contact);
            modal.find('#edit_email').val(email);
            modal.find('#edit_phone').val(phone);
            modal.find('#edit_address').val(address);
        });
    });
</script>
@endpush
