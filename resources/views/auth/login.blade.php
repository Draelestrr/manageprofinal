<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.png') }}">
  <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}">
  <title>
    ManagePRO - Login
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

<body class="bg-gray-200">
  <div class="container position-sticky z-index-sticky top-0">
    <div class="row">
      <div class="col-12">
        <nav class="navbar navbar-expand-lg blur border-radius-xl top-0 z-index-3 shadow position-absolute my-3 py-2 start-0 end-0 mx-4">
          <div class="container-fluid ps-2 pe-0">
            <a class="navbar-brand font-weight-bolder ms-lg-0 ms-3 " href="{{ route('dashboard') }}">
              ManagePRO
            </a>
          </div>
        </nav>
      </div>
    </div>
  </div>
  <main class="main-content mt-0">
    <div class="page-header align-items-start min-vh-100" style="background-image: url('https://images.unsplash.com/photo-1497294815431-9365093b7331?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1950&q=80');">
      <span class="mask bg-gradient-dark opacity-6"></span>
      <div class="container my-auto">
        <div class="row">
          <div class="col-lg-4 col-md-8 col-12 mx-auto">
            <div class="card z-index-0 fadeIn3 fadeInBottom">
              <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-dark shadow-dark border-radius-lg py-3 pe-1">
                  <h4 class="text-white font-weight-bolder text-center mt-2 mb-0">Iniciar Sesion</h4>
                </div>
              </div>
              <div class="card-body">
                <form role="form" method="POST" action="{{ route('login') }}" class="text-start">
                  @csrf
                  <div class="input-group input-group-outline my-3">
                    <label class="form-label">Correo Electronico</label>
                    <input type="email" name="email" class="form-control" required autofocus>
                  </div>
                  <div class="input-group input-group-outline mb-3">
                    <label class="form-label">Contraseña</label>
                    <input type="password" name="password" class="form-control" required>
                  </div>
                  <div class="form-check form-switch d-flex align-items-center mb-3">
                    <input class="form-check-input" type="checkbox" name="remember" id="rememberMe">
                    <label class="form-check-label mb-0 ms-3" for="rememberMe">Recordarme</label>
                  </div>
                  <div class="text-center">
                    <button type="submit" class="btn bg-gradient-dark w-100 my-4 mb-2">Iniciar Sesion</button>
                  </div>
                  <p class="mt-4 text-sm text-center">
                    ¿Olvidaste tu contraseña?
                    <a href="{{ route('password.request') }}" class="text-primary text-gradient font-weight-bold">Recupérala aquí</a>
                </p>

                  <p class="mt-4 text-sm text-center">
                    No tienes cuenta?
                    <a href="{{ route('register') }}" class="text-primary text-gradient font-weight-bold">Registrate Aqui</a>
                  </p>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </main>
  <!-- Core JS Files -->
  <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
  <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <script src="https://buttons.github.io/buttons.js" async defer></script>
  <script src="{{ asset('assets/js/material-dashboard.min.js') }}"></script>
</body>

</html>
