<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\StockEntry;
use Illuminate\Http\Request;
use App\Models\Sale;
use Carbon\Carbon;

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

        // Obtener el total de compras de productos (usando el stock y precio de compra)
        $totalPurchases = Product::all()->sum(function ($product) {
            return $product->purchase_price * $product->stock;
        });

        // Obtener los productos mas vendidos
        $bestSellingProducts = Sale::with('products')
            ->get()
            ->flatMap(function($sale) {
                return $sale->products->map(function($product) {
                    return [
                        'name' => $product->name,
                        'quantity' => $product->pivot->quantity,  // Accedemos a la cantidad vendida desde la tabla pivote
                    ];
                });
            })
            ->groupBy('name')  // Agrupar por nombre de producto
            ->map(function($group) {
                return $group->sum('quantity');  // Sumamos las cantidades vendidas por producto
            })
            ->sortDesc()  // Ordenar de mayor a menor cantidad vendida
            ->take(8);  // Limitar a los 8 productos más vendidos

        // Obtener las categorías más vendidas
        $bestSellingCategories = Sale::with('products.category') // Cargamos productos y sus categorías
            ->get()
            ->flatMap(function($sale) {
                return $sale->products->map(function($product) {
                    return [
                        'category' => $product->category->name, // Accedemos al nombre de la categoría
                        'quantity' => $product->pivot->quantity, // Accedemos a la cantidad vendida desde la tabla pivote
                    ];
                });
            })
            ->groupBy('category')  // Agrupar por categoría
            ->map(function($group) {
                return $group->sum('quantity');  // Sumamos las cantidades vendidas por categoría
            })
            ->sortDesc()  // Ordenar de mayor a menor cantidad vendida
            ->take(10);  // Limitar a las 10 categorías más vendidas

        // obtener los productos con stock
        $productsInStock = Product::where('stock', '>', 0)
        ->with('category') // Relación con la categoría
        ->get()
        ->groupBy('category.name') // Agrupar por nombre de la categoría
        ->map(function($group) {
            return $group->count(); // Contar cuántos productos por categoría tienen stock
        })
        ->sortDesc(); // Ordenar de mayor a menor cantidad de productos en stock

        // Obtener la cantidad de ventas por usuario
        $salesByUser = Sale::with('user') // Cargar las ventas con el usuario correspondiente
        ->get()
        ->groupBy('user.name') // Agrupar las ventas por nombre de usuario
        ->map(function($group) {
            return $group->count(); // Contar cuántas ventas ha hecho cada usuario
        })
        ->sortDesc(); // Ordenar de mayor a menor cantidad de ventas

        // Obtener el total de ventas por mes
        $salesByMonth = Sale::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(total) as total_sales')
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        // Obtener el total de compras por mes desde el modelo StockEntry
        $purchasesByMonth = StockEntry::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(purchase_price * quantity) as total_purchases')
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        // Asegurarnos de que ambas variables sean colecciones válidas
        $salesByMonth = collect($salesByMonth);
        $purchasesByMonth = collect($purchasesByMonth);

        // Unificar los datos por mes
        $monthlyData = $salesByMonth->map(function ($sale) use ($purchasesByMonth) {
            // Filtrar directamente por año y mes en la colección
            $purchase = $purchasesByMonth->first(function ($purchase) use ($sale) {
                return $purchase->year == $sale->year && $purchase->month == $sale->month;
            });

            // Crear una clave con un objeto Carbon para ordenar correctamente después
            return [
                'month' => Carbon::createFromDate($sale->year, $sale->month, 1)->format('F Y'),
                'date_key' => Carbon::createFromDate($sale->year, $sale->month, 1), // Clave de fecha
                'sales' => $sale->total_sales,
                'purchases' => $purchase ? $purchase->total_purchases : 0, // Si no hay compras, dejar en 0
            ];
        });

        // Si hay meses en los que solo hubo compras y no ventas
        $purchasesOnlyMonths = $purchasesByMonth->filter(function ($purchase) use ($salesByMonth) {
            return !$salesByMonth->first(function ($sale) use ($purchase) {
                return $sale->year == $purchase->year && $sale->month == $purchase->month;
            });
        });

        // Agregar meses donde solo hubo compras
        foreach ($purchasesOnlyMonths as $purchase) {
            $monthlyData->push([
                'month' => Carbon::createFromDate($purchase->year, $purchase->month, 1)->format('F Y'),
                'date_key' => Carbon::createFromDate($purchase->year, $purchase->month, 1), // Clave de fecha
                'sales' => 0, // Sin ventas
                'purchases' => $purchase->total_purchases,
            ]);
        }

        // Ordenar por la clave de fecha (date_key)
        $monthlyData = $monthlyData->sortBy('date_key');

        // Preparar los datos para pasar a la vista
        $productsData = $bestSellingProducts->map(function($quantity, $name) {
            return [
                'name' => $name,
                'quantity' => $quantity,
            ];
        })->values();

        // Preparar los datos para pasar a la vista
        $categoriesData = $bestSellingCategories->map(function($quantity, $category) {
            return [
                'category' => $category,
                'quantity' => $quantity,
            ];
        })->values();

        // Preparar los datos para pasar a la vista
        $stockData = $productsInStock->map(function($count, $category) {
            return [
                'category' => $category,
                'count' => $count,
            ];
        })->values();

        // Preparar los datos para pasar a la vista
        $salesData = $salesByUser->map(function($count, $user) {
            return [
                'user' => $user,
                'sales_count' => $count,
            ];
        })->values();

        // Preparar los datos para la vista
        $months = $monthlyData->pluck('month');
        $salesTotals = $monthlyData->pluck('sales');
        $purchasesTotals = $monthlyData->pluck('purchases');

        // Pasar los datos a la vista
        return view('dashboard', [
            'productosVendidos' => $productsData,
            'categoriasVendidas'=>$categoriesData,
            'stockData' => $stockData,
            'salesData' => $salesData,
            'months' => $months,
            'salesTotals' => $salesTotals,
            'purchasesTotals' => $purchasesTotals,
            'totalUsers' => User::count(),
            'totalProducts' => Product::count(),
            'totalCategories' => Category::count(),
            'totalSales' => Sale::sum('total'),
            'totalPurchases'=> $totalPurchases,  // Usamos la variable modificada para compras
        ]);
    }
}
