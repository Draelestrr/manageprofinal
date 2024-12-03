@extends('layouts.app')

@section('page-title', 'Entradas de Stock')

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
                                Entradas de Stock
                            </h5>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de Entradas de Stock -->
            <div class="container-fluid px-2 px-md-4 mt-4">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header pb-0">
                                <h6>Historial de Entradas de Stock</h6>
                            </div>
                            <hr class="dark horizontal my-0">
                            <div class="card-body">
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center mb-0">
                                        <thead>
                                            <tr>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Producto</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Categoría</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Cantidad</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Precio de Compra</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Fecha de Entrada</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($stockEntries as $stockEntry)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex px-2 py-1">
                                                            <div>
                                                                @if ($stockEntry->product->image_path)
                                                                    <img src="{{ asset($stockEntry->product->image_path) }}" class="avatar avatar-sm me-3" alt="product_image">
                                                                @endif
                                                            </div>
                                                            <div class="d-flex flex-column justify-content-center">
                                                                <h6 class="mb-0 text-sm">{{ $stockEntry->product->name }}</h6>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <p class="text-xs font-weight-bold mb-0">{{ $stockEntry->product->category->name }}</p>
                                                    </td>
                                                    <td>
                                                        <p class="text-xs font-weight-bold mb-0">{{ $stockEntry->quantity }}</p>
                                                    </td>
                                                    <td>
                                                        <p class="text-xs font-weight-bold mb-0">${{ number_format($stockEntry->purchase_price, 2) }}</p>
                                                    </td>
                                                    <td>
                                                        <p class="text-xs font-weight-bold mb-0">{{ $stockEntry->created_at->format('d/m/Y') }}</p>
                                                    </td>
                                                    <td>
                                                        <form action="{{ route('stock_entries.destroy', $stockEntry->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar esta entrada?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm">
                                                                Eliminar
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @if ($stockEntries->isEmpty())
                                        <div class="alert alert-warning text-center mt-3">
                                            No hay entradas de stock registradas.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
