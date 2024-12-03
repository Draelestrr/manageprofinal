<!-- resources/views/dashboard.blade.php -->

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.png') }}">
  <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}">
  <title>
    Material Dashboard 3 - Dashboard
  </title>
  <!-- Fonts and icons -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
  <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  <!-- CSS Files -->
  <link href="{{ asset('assets/css/material-dashboard.css') }}" rel="stylesheet" />
</head>

<body class="g-sidenav-show bg-gray-100">
  @include('partials.sidenav') <!-- Incluir Sidebar -->

  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    @include('partials.navbar') <!-- Incluir Navbar -->

    <div class="container-fluid py-4">
      <div class="row">
        <!-- Card para total de usuarios -->
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-header p-3 pt-2">
              <div class="text-end pt-1">
                <p class="text-sm mb-0 text-capitalize">Total de Usuarios</p>
                <h4 class="mb-0">{{ $totalUsers }}</h4> <!-- Mostrar total de usuarios -->
              </div>
              <div class="icon icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">
                <i class="material-symbols-rounded">people</i>
              </div>
            </div>
            <hr class="dark horizontal my-0">
          </div>
        </div>

        <!-- Card para total de productos -->
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-header p-3 pt-2">
              <div class="text-end pt-1">
                <p class="text-sm mb-0 text-capitalize">Total de Productos</p>
                <h4 class="mb-0">{{ $totalProducts }}</h4> <!-- Mostrar total de productos -->
              </div>
              <div class="icon icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                <i class="material-symbols-rounded">inventory_2</i>
              </div>
            </div>
            <hr class="dark horizontal my-0">
          </div>
        </div>

        <!-- Card para total de categorías -->
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-header p-3 pt-2">
              <div class="text-end pt-1">
                <p class="text-sm mb-0 text-capitalize">Total de Categorías</p>
                <h4 class="mb-0">{{ $totalCategories }}</h4> <!-- Mostrar total de categorías -->
              </div>
              <div class="icon icon-shape bg-gradient-warning shadow-warning text-center border-radius-xl mt-n4 position-absolute">
                <i class="material-symbols-rounded">category</i>
              </div>
            </div>
            <hr class="dark horizontal my-0">
          </div>
        </div>

        <!-- Card para total en compras de productos -->
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-header p-3 pt-2">
              <div class="text-end pt-1">
                <p class="text-sm mb-0 text-capitalize">Total en Compras de Productos</p>
                <h4 class="mb-0">${{ number_format($totalPurchases) }}</h4> <!-- Mostrar total en compras -->
              </div>
              <div class="icon icon-shape bg-gradient-danger shadow-danger text-center border-radius-xl mt-n4 position-absolute">
                <i class="material-symbols-rounded">shopping_cart</i>
              </div>
            </div>
            <hr class="dark horizontal my-0">
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Archivos JS -->
  <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
  <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
  <script src="{{ asset('assets/js/material-dashboard.min.js') }}"></script>
</body>

</html>
