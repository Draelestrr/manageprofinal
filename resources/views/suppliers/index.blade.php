@extends('layouts.app')

@section('page-title', 'Lista de Proveedores')

@section('content')
<div class="g-sidenav-show bg-gray-100">
    @include('partials.sidenav') <!-- Incluir Sidebar -->

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        @include('partials.navbar') <!-- Incluir Navbar -->

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
                        <table class="table table-hover">
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
                                            <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-info btn-sm">Editar</a>
                                            <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este proveedor?')">Eliminar</button>
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

<!-- Modal Crear Proveedor -->
@include('partials.create-modal')
@endsection
