@extends('layouts.app')

@section('page-title', 'Dashboard')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Card para total de usuarios -->
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-header p-3 pt-2">
                    <div class="text-end pt-1">
                        <p class="text-sm mb-0 text-capitalize">Total de Usuarios</p>
                        <h4 class="mb-0">{{ $totalUsers }}</h4>
                    </div>
                    <div class="icon icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">
                        <i class="material-symbols-rounded">people</i>
                    </div>
                </div>
                <hr class="dark horizontal my-0">
                <div class="card-footer p-3">
                    <p class="mb-0"><span class="text-success text-sm font-weight-bolder">Usuarios registrados</span></p>
                </div>
            </div>
        </div>

        <!-- Card para total de productos -->
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-header p-3 pt-2">
                    <div class="text-end pt-1">
                        <p class="text-sm mb-0 text-capitalize">Total de Productos</p>
                        <h4 class="mb-0">{{ $totalProducts }}</h4>
                    </div>
                    <div class="icon icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                        <i class="material-symbols-rounded">inventory_2</i>
                    </div>
                </div>
                <hr class="dark horizontal my-0">
                <div class="card-footer p-3">
                    <p class="mb-0"><span class="text-success text-sm font-weight-bolder">Productos en inventario</span></p>
                </div>
            </div>
        </div>

        <!-- Card para total de categorías -->
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-header p-3 pt-2">
                    <div class="text-end pt-1">
                        <p class="text-sm mb-0 text-capitalize">Total de Categorías</p>
                        <h4 class="mb-0">{{ $totalCategories }}</h4>
                    </div>
                    <div class="icon icon-shape bg-gradient-warning shadow-warning text-center border-radius-xl mt-n4 position-absolute">
                        <i class="material-symbols-rounded">category</i>
                    </div>
                </div>
                <hr class="dark horizontal my-0">
                <div class="card-footer p-3">
                    <p class="mb-0"><span class="text-success text-sm font-weight-bolder">Categorías disponibles</span></p>
                </div>
            </div>
        </div>

        <!-- Card para total en compras de productos -->
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-header p-3 pt-2">
                    <div class="text-end pt-1">
                        <p class="text-sm mb-0 text-capitalize">Total en Compras</p>
                        <h4 class="mb-0">${{ number_format($totalPurchases, 2) }}</h4>
                    </div>
                    <div class="icon icon-shape bg-gradient-danger shadow-danger text-center border-radius-xl mt-n4 position-absolute">
                        <i class="material-symbols-rounded">shopping_cart</i>
                    </div>
                </div>
                <hr class="dark horizontal my-0">
                <div class="card-footer p-3">
                    <p class="mb-0"><span class="text-success text-sm font-weight-bolder">Inversión en inventario</span></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección de gráficos o información adicional -->
    <div class="row mt-4">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header pb-0">
                    <h6>Resumen de Ventas</h6>
                </div>
                <div class="card-body">
                    <!-- Aquí podrías agregar un gráfico de ventas o información adicional -->
                    <p>Próximamente: Gráfico de ventas mensuales</p>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header pb-0">
                    <h6>Productos con Bajo Stock</h6>
                </div>
                <div class="card-body">
                    <!-- Aquí podrías listar productos con stock bajo -->
                    <p>Próximamente: Lista de productos con stock crítico</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ejemplo de notificación de bienvenida
        showNotification('Bienvenido al Dashboard', 'info');

        // Puedes agregar más funcionalidades interactivas aquí
    });
</script>
@endpush
