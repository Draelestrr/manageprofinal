<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>@yield('page-title', 'Tu Título por Defecto')</title>
  <!--     Fonts and icons     -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
  <!-- Nucleo Icons -->
  <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- Material Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded" />
  <!-- CSS Files -->
  <link id="pagestyle" href="{{ asset('assets/css/material-dashboard.css?v=3.2.0') }}" rel="stylesheet" />
  <meta name="csrf-token" content="{{ csrf_token() }}">

</head>

<body class="g-sidenav-show bg-gray-100">
  <!-- Incluir Sidebar -->
  @include('partials.sidenav')

  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <!-- Incluir Navbar -->
    @include('partials.navbar')

    <!-- Contenido Principal -->
    <div class="container-fluid py-4">
      @yield('content')
    </div>
  </main>

  <!-- Configurador fijo (opcional) -->
  @include('partials.fixed-plugin')

  <!-- Scripts -->
  <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
  <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
  <script src="{{ asset('assets/js/material-dashboard.min.js?v=3.2.0') }}"></script>
  <!-- Agrega esto en tu blade, preferiblemente antes del cierre de la etiqueta </body> -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- Scripts adicionales -->
  @stack('scripts')
</body>

</html>
