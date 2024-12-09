<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>@yield('page-title', 'Tu Título por Defecto')</title>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  <!-- Fonts and Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
  <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>

  <!-- Material Dashboard CSS -->
  <link id="pagestyle" href="{{ asset('assets/css/material-dashboard.css?v=3.2.0') }}" rel="stylesheet" />

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- SweetAlert2 CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">

  <!-- Bootstrap Icons (opcional, para iconos adicionales) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

  <!-- Stack para estilos adicionales -->
  @stack('styles')

  <!-- Tus estilos personalizados -->
  <style>
    .custom-icon-size {
    font-size: 4.5em; /* Ajusta el tamaño según sea necesario */
    }

    .avatar {
      width: 50px;
      height: 50px;
      object-fit: cover;
      border-radius: 50%;
    }

    .badge-supplier {
      margin-bottom: 2px;
    }

    .image-preview {
      max-width: 150px;
      max-height: 150px;
      margin-top: 10px;
      border-radius: 8px;
      display: none;
      object-fit: cover;
      border: 1px solid #ddd;
      transition: opacity 0.3s ease;
    }

    .form-section {
      margin-bottom: 2rem;
    }

    .btn-add {
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .btn-add i {
      margin-right: 5px;
    }

    .btn-save {
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .btn-save i {
      margin-right: 5px;
    }

    /* Aumentar el tamaño de los iconos en los botones */
    .btn i,
    .btn .material-symbols-rounded {
      font-size: 1.25rem; /* Ajusta según tus necesidades */
      color: inherit; /* Hereda el color del botón */
    }

    /* Opcional: Cambiar el cursor al pasar sobre los botones */
    .btn {
      cursor: pointer;
    }

    /* CSS personalizado para mejorar la visibilidad de los inputs en modales */
    .modal-content .form-control,
    .modal-content .form-select {
      background-color: #fff; /* Fondo blanco para los inputs */
      color: #495057; /* Texto oscuro */
      border: 1px solid #ced4da; /* Borde estándar de Bootstrap */
    }

    /* Opcional: Añadir sombra a los inputs para destacarlos */
    .modal-content .form-control:focus,
    .modal-content .form-select:focus {
      box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
  </style>
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

  <!-- jQuery (requerido por DataTables) -->

  <!-- Bootstrap JS Bundle (incluye Popper) -->
  <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
  <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>

  <!-- DataTables JS -->
  <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>

  <!-- SweetAlert2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- Otros Plugins JS -->
  <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
  <script src="{{ asset('assets/js/material-dashboard.min.js?v=3.2.0') }}"></script>

  <!-- Stack para scripts adicionales -->
  @stack('scripts')
</body>

</html>
