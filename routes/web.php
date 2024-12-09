<?php

use App\Http\Controllers\{
    ProfileController,
    CategoryController,
    ProductController,
    SupplierController,
    CustomerController,
    ExpenseController,
    ExtraChargeController,
    SaleController,
    StockEntryController,
    ReportController,
    DashboardController
};
use Illuminate\Support\Facades\Route;

// Página principal: redirige al login si no está autenticado
Route::get('/', fn() => redirect()->route('login'));

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Rutas protegidas por autenticación
Route::middleware('auth')->group(function () {
    // Perfil de usuario
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    // Categorías
    Route::resource('categories', CategoryController::class);
    Route::post('/products/create-category', [ProductController::class, 'createCategory'])->name('products.createCategory');

    // Proveedores
    Route::resource('suppliers', SupplierController::class);
    Route::post('/products/create-supplier', [ProductController::class, 'createSupplier'])->name('products.createSupplier');

    // Productos
    Route::resource('products', ProductController::class);
    Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');

    // Ingresos de stock
    Route::resource('stock_entries', StockEntryController::class);
    Route::get('/buscar-productos', [StockEntryController::class, 'searchProducts'])->name('stock_entries.search_products');

    // Clientes
    Route::resource('customers', CustomerController::class);

    // Ventas
    Route::resource('sales', SaleController::class);

    // Rutas adicionales para la gestión del carrito y confirmación de ventas
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [SaleController::class, 'showCart'])->name('show'); // Página de carrito
        Route::post('/add', [SaleController::class, 'addToCart'])->name('add'); // Añadir al carrito
        Route::post('/remove', [SaleController::class, 'removeFromCart'])->name('remove'); // Eliminar del carrito
        Route::get('/get', [SaleController::class, 'getCart'])->name('get'); // Obtener el carrito
        Route::get('/search-products', [SaleController::class, 'searchProducts'])->name('searchProducts'); // Buscar productos en el carrito
        Route::post('/create-customer', [SaleController::class, 'createCustomer'])->name('createCustomer'); // Crear cliente
    });


    // Confirmar venta
    Route::post('/sales/confirm', [SaleController::class, 'confirmSale'])->name('sales.confirmSale'); // Confirmar venta

    // Gastos y cargos adicionales
    Route::resource('expenses', ExpenseController::class);
    Route::resource('extra-charges', ExtraChargeController::class);

    // Reportes
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    // API endpoints para búsqueda y actualización dinámica
    Route::prefix('api')->group(function () {
        Route::get('/categories', [CategoryController::class, 'getAll'])->name('api.categories.all');
        Route::get('/suppliers', [SupplierController::class, 'getAll'])->name('api.suppliers.all');
    });
});

require __DIR__.'/auth.php';
