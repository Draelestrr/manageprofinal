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
use App\Http\Controllers\DashboardController; // Agregar controlador de Dashboard
use Illuminate\Support\Facades\Route;
use Barryvdh\DomPDF\Facade\Pdf;

Route::get('/prueba-pdf', function () {
    $data = ['title' => 'Prueba PDF', 'content' => 'Este es un contenido de prueba'];
    $pdf = Pdf::loadView('pdf.prueba', $data);

    return $pdf->download('prueba.pdf');
});


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Aquí es donde puede registrar rutas web para su aplicación. Estas
| rutas están cargadas por el RouteServiceProvider y contienen el
| grupo de middleware "web".
|
*/

// Página principal: redirige al login si no está autenticado
Route::get('/', function () {
    return redirect()->route('login');
});

// Dashboard: protege la ruta por autenticación y verificación, y usa el controlador DashboardController
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// Rutas protegidas por autenticación
Route::middleware('auth')->group(function () {

    // Perfil del usuario autenticado
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas de categorías (Administrar categorías)
    Route::resource('categories', CategoryController::class)->only(['index', 'store']);

    // Rutas de proveedores (Administrar proveedores)
    Route::resource('suppliers', SupplierController::class);

    // Rutas de productos e inventario
    Route::resource('products', ProductController::class);
    Route::patch('products/{product}', [ProductController::class, 'update'])->name('products.update');

    // Rutas de ingresos de stock (Ingresar stock)
    Route::resource('stock_entries', StockEntryController::class);
    Route::get('/buscar-productos', [StockEntryController::class, 'searchProducts'])->name('stock_entries.search_products');

    // Rutas de clientes (Gestionar clientes)
    Route::resource('customers', CustomerController::class);

    // Rutas de ventas (Gestionar ventas)
    Route::resource('sales', SaleController::class);

    // Rutas de gastos (Gestionar gastos y cargos adicionales)
    Route::resource('expenses', ExpenseController::class);
    Route::resource('extra-charges', ExtraChargeController::class);

    // Rutas de reportes (Generales y personales)
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
});

require __DIR__.'/auth.php';
