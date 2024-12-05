<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2 bg-white my-2" id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand px-4 py-3 m-0" href="{{ url('/dashboard') }}">
            <img src="{{ asset('assets/img/logo-ct-dark.png') }}" class="navbar-brand-img" width="26" height="26" alt="main_logo">
            <span class="ms-1 text-sm text-dark">Material Dashboard</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0 mb-2">
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link text-dark {{ request()->is('dashboard') ? 'active' : '' }}" href="{{ url('/dashboard') }}">
                    <i class="material-symbols-rounded opacity-5">dashboard</i>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark {{ request()->is('profile') ? 'active' : '' }}" href="{{ url('/profile') }}">
                    <i class="material-symbols-rounded opacity-5">person</i>
                    <span class="nav-link-text ms-1">Profile</span>
                </a>
            </li>

            <!-- Botón desplegable de Inventario -->
            <li class="nav-item">
                <a class="nav-link text-dark dropdown-toggle collapsed" href="#inventoryMenu" data-bs-toggle="collapse" aria-expanded="false">
                    <i class="material-symbols-rounded opacity-5">inventory</i>
                    <span class="nav-link-text ms-1">Inventario</span>
                </a>
                <div class="collapse" id="inventoryMenu">
                    <ul class="nav flex-column ms-4">
                        <!-- Enlace para Crear Producto -->
                        <li class="nav-item">
                            <a class="nav-link text-dark {{ request()->is('products/create') ? 'active' : '' }}" href="{{ route('products.create') }}">
                                <i class="material-symbols-rounded opacity-5">add_circle</i>
                                <span class="nav-link-text ms-1">Crear Producto</span>
                            </a>
                        </li>
                        <!-- Enlace para Ver Stock -->
                        <li class="nav-item">
                            <a class="nav-link text-dark {{ request()->is('products') ? 'active' : '' }}" href="{{ route('products.index') }}">
                                <i class="material-symbols-rounded opacity-5">visibility</i>
                                <span class="nav-link-text ms-1">Ver Stock</span>
                            </a>
                        </li>
                        <!-- Enlace para Ingresar Stock -->
                        <li class="nav-item">
                            <a class="nav-link text-dark {{ request()->is('stock_entries/create') ? 'active' : '' }}" href="{{ route('stock_entries.create') }}">
                                <i class="material-symbols-rounded opacity-5">input</i>
                                <span class="nav-link-text ms-1">Ingresar Stock</span>
                            </a>
                        </li>

                        <!-- Nuevo Botón: Ver Proveedores -->
                        <li class="nav-item">
                            <a class="nav-link text-dark {{ request()->is('suppliers') ? 'active' : '' }}" href="{{ route('suppliers.index') }}">
                                <i class="material-symbols-rounded opacity-5">people</i>
                                <span class="nav-link-text ms-1">Ver Proveedores</span>
                            </a>
                        </li>

                        <!-- Nuevo Botón: Ver Categorías -->
                        <li class="nav-item">
                            <a class="nav-link text-dark {{ request()->is('categories') ? 'active' : '' }}" href="{{ route('categories.index') }}">
                                <i class="material-symbols-rounded opacity-5">category</i>
                                <span class="nav-link-text ms-1">Ver Categorías</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- Botón desplegable de Ventas -->
            <li class="nav-item">
                <a class="nav-link text-dark dropdown-toggle collapsed" href="#salesMenu" data-bs-toggle="collapse" aria-expanded="false">
                    <i class="material-symbols-rounded opacity-5">shopping_cart</i>
                    <span class="nav-link-text ms-1">Ventas</span>
                </a>
                <div class="collapse" id="salesMenu">
                    <ul class="nav flex-column ms-4">
                        <!-- Enlace para Ingresar Venta -->
                        <li class="nav-item">
                            <a class="nav-link text-dark {{ request()->is('sales/create') ? 'active' : '' }}" href="{{ route('sales.create') }}">
                                <i class="material-symbols-rounded opacity-5">add_shopping_cart</i>
                                <span class="nav-link-text ms-1">Ingresar Venta</span>
                            </a>
                        </li>
                        <!-- Enlace para Ver Ventas -->
                        <li class="nav-item">
                            <a class="nav-link text-dark {{ request()->is('sales') ? 'active' : '' }}" href="{{ route('sales.index') }}">
                                <i class="material-symbols-rounded opacity-5">visibility</i>
                                <span class="nav-link-text ms-1">Ver Ventas</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link text-dark {{ request()->is('logout') ? 'active' : '' }}" href="{{ route('logout') }}">
                    <i class="material-symbols-rounded opacity-5">logout</i>
                    <span class="nav-link-text ms-1">Logout</span>
                </a>
            </li>
        </ul>
    </div>
</aside>
