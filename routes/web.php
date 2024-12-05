<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ExtraChargeController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\StockEntryController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Barryvdh\DomPDF\Facade\Pdf;

Route::get('/prueba-pdf', function () {
    $data = ['title' => 'Prueba PDF', 'content' => 'Este es un contenido de prueba'];
    $pdf = Pdf::loadView('pdf.prueba', $data);
    return $pdf->download('prueba.pdf');
});

// Página principal: redirige al login si no está autenticado
Route::get('/', function () {
    return redirect()->route('login');
});

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Rutas protegidas por autenticación
Route::middleware('auth')->group(function () {
    // Perfil de usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Categorías
    Route::resource('categories', CategoryController::class)->only(['index', 'store']);
    Route::post('/products/create-category', [ProductController::class, 'createCategory'])
        ->name('products.createCategory');
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');

    // Proveedores
    Route::resource('suppliers', SupplierController::class);
    Route::post('/products/create-supplier', [ProductController::class, 'createSupplier'])
        ->name('products.createSupplier');
    Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');

    // Productos
    Route::resource('products', ProductController::class);
    Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');
    Route::patch('products/{product}', [ProductController::class, 'update'])->name('products.update');

    // Ingresos de stock
    Route::resource('stock_entries', StockEntryController::class);
    Route::get('/buscar-productos', [StockEntryController::class, 'searchProducts'])
        ->name('stock_entries.search_products');

    // Clientes
    Route::resource('customers', CustomerController::class);

    // Ventas
    Route::resource('sales', SaleController::class);

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
