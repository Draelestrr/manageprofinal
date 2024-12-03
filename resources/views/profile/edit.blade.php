<!-- resources/views/profile/edit.blade.php -->

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.png') }}">
  <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}">
  <title>
    Material Dashboard 3 - Profile
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
      <!-- Profile Header -->
      <div class="card card-body mx-2 mx-md-4 mt-4"> <!-- Cambié mt-n6 por mt-4 para ajustar el margen superior -->
        <div class="row gx-4 mb-2">
          <div class="col-auto">
            <div class="avatar avatar-xl position-relative">
              <img src="{{ asset('assets/img/bruce-mars.jpg') }}" alt="profile_image" class="w-100 border-radius-lg shadow-sm">
            </div>
          </div>
          <div class="col-auto my-auto">
            <div class="h-100">
              <h5 class="mb-1">
                {{ Auth::user()->name }}
              </h5>
              <p class="mb-0 font-weight-normal text-sm">
                {{ Auth::user()->email }}
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Update Forms Section -->
      <div class="container-fluid px-2 px-md-4 mt-4">
        <div class="row">
          <!-- Update Profile Information -->
          <div class="col-md-6 mb-4">
            <div class="card">
              <div class="card-header pb-0">
                <h6>Update Profile Information</h6>
              </div>
              <hr class="dark horizontal my-0">
              <div class="card-body">
                @include('profile.partials.update-profile-information-form')
              </div>
            </div>
          </div>

          <!-- Update Password -->
          <div class="col-md-6 mb-4">
            <div class="card">
              <div class="card-header pb-0">
                <h6>Update Password</h6>
              </div>
              <hr class="dark horizontal my-0">
              <div class="card-body">
                @include('profile.partials.update-password-form')
              </div>
            </div>
          </div>

          <!-- Delete Account -->
          <div class="col-md-6 mb-4">
            <div class="card">
              <div class="card-header pb-0">
                <h6>Delete Account</h6>
              </div>
              <hr class="dark horizontal my-0">
              <div class="card-body">
                @include('profile.partials.delete-user-form')
              </div>
            </div>
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
