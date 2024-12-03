<!-- Navbar -->
<nav class="navbar navbar-main navbar-expand-lg px-0 mx-3 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
    <div class="container-fluid py-1 px-3">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
          <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
          <li class="breadcrumb-item text-sm text-dark active" aria-current="page">@yield('page-title')</li>
        </ol>
      </nav>
      <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
        <!-- Opcional: Campo de búsqueda -->
        <div class="ms-md-auto pe-md-3 d-flex align-items-center">
          <div class="input-group input-group-outline">
            <label class="form-label">Escribe aquí...</label>
            <input type="text" class="form-control">
          </div>
        </div>
        <ul class="navbar-nav ms-auto align-items-center">
          <!-- Icono de ajustes -->
          <li class="nav-item px-3 d-flex align-items-center">
            <a href="javascript:;" class="nav-link text-body p-0">
              <i class="material-symbols-rounded fixed-plugin-button-nav">settings</i>
            </a>
          </li>
          <!-- Dropdown de notificaciones -->
          <li class="nav-item dropdown pe-3 d-flex align-items-center">
            <a href="javascript:;" class="nav-link text-body p-0" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="material-symbols-rounded">notifications</i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
              <li class="mb-2">
                <a class="dropdown-item border-radius-md" href="javascript:;">
                  <div class="d-flex py-1">
                    <div class="my-auto">
                      <img src="../assets/img/team-2.jpg" class="avatar avatar-sm me-3">
                    </div>
                    <div class="d-flex flex-column justify-content-center">
                      <h6 class="text-sm font-weight-normal mb-1">
                        <span class="font-weight-bold">Nuevo mensaje</span> de Laur
                      </h6>
                      <p class="text-xs text-secondary mb-0">
                        <i class="fa fa-clock me-1"></i>
                        Hace 13 minutos
                      </p>
                    </div>
                  </div>
                </a>
              </li>
              <!-- Puedes añadir más elementos de notificación aquí -->
            </ul>
          </li>
          <!-- Enlace de Logout -->
          <li class="nav-item">
            <a class="nav-link text-body font-weight-bold px-0" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              <div class="d-flex align-items-center">
                <i class="fa fa-sign-out-alt text-primary text-sm me-2"></i>
                <span class="d-sm-inline d-none">Logout</span>
              </div>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
            </form>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <!-- End Navbar -->
