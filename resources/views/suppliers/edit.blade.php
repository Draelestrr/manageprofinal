@extends('layouts.app')

@section('page-title', 'Editar Proveedor')

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
                                Editar Proveedor
                            </h5>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulario para Editar Proveedor -->
            <div class="container-fluid px-2 px-md-4 mt-4">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header pb-0">
                                <h6>Información del Proveedor</h6>
                            </div>
                            <hr class="dark horizontal my-0">
                            <div class="card-body">
                                <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nombre</label>
                                        <input type="text" name="suppliers_debug" value="{{ implode(',', old('suppliers', $product->suppliers->pluck('id')->toArray())) }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="contact_name" class="form-label">Contacto</label>
                                        <input type="text" class="form-control" id="contact_name" name="contact_name" value="{{ old('contact_name', $supplier->contact_name) }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $supplier->email) }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Teléfono</label>
                                        <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $supplier->phone) }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="address" class="form-label">Dirección</label>
                                        <input type="text" class="form-control" id="address" name="address" value="{{ old('address', $supplier->address) }}">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Actualizar Proveedor</button>
                                    <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Cancelar</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
