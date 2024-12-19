{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.app')

@section('page-title', 'Dashboard')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Usando el Componente: DashboardCard -->
        <x-dashboard-card
            title="Total de Usuarios"
            value="{{ $totalUsers }}"
            description="Usuarios registrados"
            icon="people"
            color="bg-gradient-primary"
            textColor="text-dark"
        />

        <x-dashboard-card
            title="Total de Productos"
            value="{{ $totalProducts }}"
            description="Productos en inventario"
            icon="inventory_2"
            color="bg-gradient-success"
            textColor="text-dark"
        />

        <x-dashboard-card
            title="Total de Categorías"
            value="{{ $totalCategories }}"
            description="Categorías disponibles"
            icon="category"
            color="bg-gradient-warning"
            textColor="text-dark"
        />

        <x-dashboard-card
            title="Total en Compras"
            value="${{ number_format($totalPurchases, 2) }}"
            description="Inversión en inventario"
            icon="shopping_cart"
            color="bg-gradient-danger"
            textColor="text-dark"
        />

        <x-dashboard-card
            title="Total en Ventas"
            value="${{ number_format($totalSales, 2) }}"
            description="Ganancias"
            icon="monetization_on"
            color="bg-gradient-info"
            textColor="text-dark"
        />
    </div>

    <!-- Sección de gráficos o información adicional -->
    <div class="row mt-4">
        <!-- Gráfico 1: Productos más Vendidos -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div id="chart1" style="width: 100%; height: 350px;"></div>
                </div>
            </div>
        </div>

        <!-- Gráfico 2: Categorías más Vendidas -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div id="chart2" style="width: 100%; height: 350px;"></div>
                </div>
            </div>
        </div>

        <!-- Gráfico 3: Stock por Categoría -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div id="chart3" style="width: 100%; height: 350px;"></div>
                </div>
            </div>
        </div>

        <!-- Gráfico 4: Ventas por Usuario -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div id="chart4" style="width: 100%; height: 350px;"></div>
                </div>
            </div>
        </div>

        <!-- Gráfico 5: Total Ventas y Compras por Mes -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div id="chart5" style="width: 100%; height: 350px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <style>
        /* Ajustar el tamaño de los iconos */
        .icon i {
            font-size: 2rem;
        }

        /* Asegurar que el texto no se superponga con el icono */
        .card-header {
            padding-right: 3rem; /* Ajusta según el tamaño del icono */
        }

        /* Ajustar la alineación de los textos */
        .card-header .text-end p,
        .card-header .text-end h4 {
            margin-bottom: 0;
        }
    </style>
@endpush

@push('scripts')
    <!-- Incluir ApexCharts JS -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <!-- Incluir Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Función para mostrar notificaciones usando Toastr
            function showNotification(message, type = 'info') {
                toastr.options = {
                    "closeButton": true,
                    "progressBar": true,
                    "positionClass": "toast-top-right",
                    "timeOut": "3000",
                };
                toastr[type](message);
            }

            // Mostrar notificación de bienvenida
            showNotification('Bienvenido al Dashboard', 'info');

            // Función para escapar HTML y prevenir XSS
            function escapeHtml(text) {
                if (!text) return '';
                return text
                    .replace(/&/g, "&amp;")
                    .replace(/</g, "&lt;")
                    .replace(/>/g, "&gt;")
                    .replace(/"/g, "&quot;")
                    .replace(/'/g, "&#039;");
            }

            // Gráfico 1: Productos más Vendidos
            (function() {
                var bestSellingProducts = @json($productosVendidos);

                if (bestSellingProducts.length === 0) {
                    document.querySelector("#chart1").innerHTML = "<p>No hay datos para mostrar.</p>";
                    return;
                }

                var productNames = bestSellingProducts.map(product => escapeHtml(product.name));
                var quantitiesSold = bestSellingProducts.map(product => product.quantity);

                var options1 = {
                    chart: {
                        type: 'bar',
                        height: 350,
                        toolbar: {
                            show: true
                        },
                        zoom: {
                            enabled: false
                        }
                    },
                    series: [{
                        name: 'Cantidad Vendida',
                        data: quantitiesSold
                    }],
                    xaxis: {
                        categories: productNames,
                        labels: {
                            rotate: -45
                        }
                    },
                    title: {
                        text: 'PRODUCTOS MÁS VENDIDOS',
                        align: 'center'
                    },
                    colors: ['#FF5733', '#33FF57', '#3357FF', '#FF33A1'],
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '55%',
                            endingShape: 'rounded'
                        },
                    },
                    dataLabels: {
                        enabled: true
                    },
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                return val;
                            }
                        }
                    }
                };

                var chart1 = new ApexCharts(document.querySelector("#chart1"), options1);
                chart1.render();
            })();

            // Gráfico 2: Categorías más Vendidas
            (function() {
                var bestSellingCategories = @json($categoriasVendidas);

                if (bestSellingCategories.length === 0) {
                    document.querySelector("#chart2").innerHTML = "<p>No hay datos para mostrar.</p>";
                    return;
                }

                var categoryNames = bestSellingCategories.map(category => escapeHtml(category.category));
                var categoryQuantities = bestSellingCategories.map(category => category.quantity);

                var options2 = {
                    chart: {
                        type: 'bar',
                        height: 350,
                        toolbar: {
                            show: true
                        },
                        zoom: {
                            enabled: false
                        }
                    },
                    series: [{
                        name: 'Cantidad Vendida',
                        data: categoryQuantities
                    }],
                    xaxis: {
                        categories: categoryNames,
                        labels: {
                            rotate: -45
                        }
                    },
                    title: {
                        text: 'CATEGORÍAS MÁS VENDIDAS',
                        align: 'center'
                    },
                    colors: ['#33A1FF', '#FF9E33', '#FF5733', '#9E33FF'],
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '55%',
                            endingShape: 'rounded'
                        },
                    },
                    dataLabels: {
                        enabled: true
                    },
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                return val;
                            }
                        }
                    }
                };

                var chart2 = new ApexCharts(document.querySelector("#chart2"), options2);
                chart2.render();
            })();

            // Gráfico 3: Stock por Categoría
            (function() {
                var stockData = @json($stockData);

                if (stockData.length === 0) {
                    document.querySelector("#chart3").innerHTML = "<p>No hay datos para mostrar.</p>";
                    return;
                }

                var stockCategories = stockData.map(item => escapeHtml(item.category));
                var stockCounts = stockData.map(item => item.count);

                var options3 = {
                    chart: {
                        type: 'pie',
                        height: 350,
                        toolbar: {
                            show: true
                        }
                    },
                    series: stockCounts,
                    labels: stockCategories,
                    title: {
                        text: 'STOCK POR CATEGORÍA',
                        align: 'center'
                    },
                    colors: ['#FF5733', '#33FF57', '#3357FF', '#FF33A1', '#9E33FF'],
                    dataLabels: {
                        enabled: true,
                        formatter: function(val, opts) {
                            return opts.w.config.series[opts.seriesIndex];
                        },
                        style: {
                            fontSize: '14px',
                            fontWeight: 'bold'
                        }
                    },
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                return val;
                            }
                        }
                    }
                };

                var chart3 = new ApexCharts(document.querySelector("#chart3"), options3);
                chart3.render();
            })();

            // Gráfico 4: Ventas por Usuario
            (function() {
                var salesData = @json($salesData);

                if (salesData.length === 0) {
                    document.querySelector("#chart4").innerHTML = "<p>No hay datos para mostrar.</p>";
                    return;
                }

                var userNames = salesData.map(user => escapeHtml(user.user));
                var salesCounts = salesData.map(user => user.sales_count);

                var options4 = {
                    chart: {
                        type: 'bar',
                        height: 350,
                        toolbar: {
                            show: true
                        },
                        zoom: {
                            enabled: false
                        }
                    },
                    series: [{
                        name: 'Ventas',
                        data: salesCounts
                    }],
                    xaxis: {
                        categories: userNames,
                        labels: {
                            rotate: -45
                        }
                    },
                    title: {
                        text: 'VENTAS POR USUARIO',
                        align: 'center'
                    },
                    colors: ['#FF5733', '#33FF57', '#3357FF', '#FF33A1'],
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '55%',
                            endingShape: 'rounded'
                        },
                    },
                    dataLabels: {
                        enabled: true
                    },
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                return val;
                            }
                        }
                    }
                };

                var chart4 = new ApexCharts(document.querySelector("#chart4"), options4);
                chart4.render();
            })();

            // Gráfico 5: Total Ventas y Compras por Mes
            (function() {
                var months = @json($months); // Nombres de los meses
                var salesTotals = @json($salesTotals); // Totales de ventas por mes
                var purchasesTotals = @json($purchasesTotals); // Totales de compras por mes

                if (months.length === 0) {
                    document.querySelector("#chart5").innerHTML = "<p>No hay datos para mostrar.</p>";
                    return;
                }

                var options5 = {
                    chart: {
                        type: 'line',
                        height: 350,
                        toolbar: {
                            show: true
                        }
                    },
                    series: [
                        {
                            name: 'Ventas',
                            data: salesTotals
                        },
                        {
                            name: 'Compras',
                            data: purchasesTotals
                        }
                    ],
                    xaxis: {
                        categories: months,
                        labels: {
                            rotate: -45
                        }
                    },
                    title: {
                        text: 'TOTAL VENTAS Y COMPRAS',
                        align: 'center'
                    },
                    colors: ['#FF5733', '#33FF57'],
                    stroke: {
                        width: 2,
                        curve: 'smooth'
                    },
                    markers: {
                        size: 5
                    },
                    grid: {
                        borderColor: '#e7e7e7'
                    },
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                return '$' + val.toFixed(2);
                            }
                        }
                    }
                };

                var chart5 = new ApexCharts(document.querySelector("#chart5"), options5);
                chart5.render();
            })();
        });
    </script>
@endpush
