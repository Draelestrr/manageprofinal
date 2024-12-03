<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\StockEntry;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Muestra el dashboard con las estadísticas.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Obtener el total de usuarios
        $totalUsers = User::count();

        // Obtener el total de productos
        $totalProducts = Product::count();

        // Obtener el total de categorías
        $totalCategories = Category::count();

        // Obtener el total de compras de productos (usando las entradas de stock)
        $totalPurchases = StockEntry::sum('purchase_price');

        // Pasar los datos a la vista
        return view('dashboard', compact('totalUsers', 'totalProducts', 'totalCategories', 'totalPurchases'));
    }
}
