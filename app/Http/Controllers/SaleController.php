<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class SaleController extends Controller
{
    /**
     * Constructor para aplicar middleware de autenticación.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostrar la lista de ventas.
     */
    public function index()
    {
        $sales = Sale::with('products', 'customer', 'user')->orderBy('created_at', 'desc')->get();
        $customers = Customer::all(); // Obtener todos los clientes para el formulario de edición
        return view('sales.index', compact('sales', 'customers'));
    }

    /**
     * Mostrar el formulario para crear una nueva venta.
     */
    public function create()
    {
        Session::forget('cart'); // Limpiar el carrito al iniciar una nueva venta
        $customers = Customer::all();
        return view('sales.create', compact('customers'));
    }

    /**
     * Almacenar una nueva venta en la base de datos.
     */
    public function store(Request $request)
    {
        // Validación de los campos
        $validator = Validator::make($request->all(), [
            'customer' => 'required|exists:customers,id',
            'total' => 'required|numeric|min:0',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Obtener el carrito de la sesión
        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return response()->json(['error' => 'El carrito está vacío.'], 400);
        }

        // Crear la venta
        $sale = new Sale();
        $sale->customer_id = $request->customer;
        $sale->total = $request->total;
        $sale->user_id = auth()->id(); // Asume que el usuario autenticado es el vendedor
        $sale->save();

        // Asociar productos a la venta y ajustar stock
        foreach ($cart as $productId => $item) {
            $product = Product::findOrFail($productId);

            // Verificar stock
            if ($product->stock < $item['quantity']) {
                // Restaurar la venta en caso de error
                $sale->delete();
                return response()->json(['error' => "No hay suficiente stock para el producto {$product->name}."], 400);
            }

            // Reducir stock
            $product->decrement('stock', $item['quantity']);

            // Asociar producto a la venta
            $sale->products()->attach($product->id, [
                'quantity' => $item['quantity'],
                'unit_price' => $product->sale_price,
                'subtotal' => $product->sale_price * $item['quantity'],
            ]);
        }

        // Generar PDF de la venta
        $pdfData = [
            'sale' => $sale->fresh('products', 'customer', 'user'),
            'products' => $sale->products,
            'total' => $sale->total,
            'date' => now()->format('Y-m-d H:i'),
            'user' => $sale->user->name,
        ];

        $pdf = Pdf::loadView('pdf.sale', $pdfData);
        $pdfPath = 'public/sale/' . now()->format('Y-m-d') . '/sale-' . $sale->id . '.pdf';
        Storage::put($pdfPath, $pdf->output());

        // Actualizar el nombre del archivo PDF en la base de datos
        $sale->update(['pdf_filename' => 'sale-' . $sale->id . '.pdf']);

        // Enviar el PDF por correo al cliente
        Mail::send([], [], function ($message) use ($sale, $pdf) {
            $message->to($sale->customer->email)
                ->subject('Factura de Venta')
                ->attachData($pdf->output(), 'factura-' . $sale->id . '.pdf');
        });

        // Limpiar el carrito después de crear la venta
        Session::forget('cart');

        return response()->json(['success' => 'Venta registrada exitosamente.']);
    }

    /**
     * Mostrar los detalles de una venta.
     */
    public function show($id)
    {
        $sale = Sale::with('products', 'customer', 'user')->findOrFail($id);
        return response()->json([
            'sale' => $sale,
        ]);
    }

    /**
     * Mostrar el formulario para editar una venta.
     */
    public function edit($saleId)
    {
        $sale = Sale::with('products', 'customer')->findOrFail($saleId);
        return response()->json([
            'sale' => $sale,
        ]);
    }

    /**
     * Actualizar una venta existente.
     */
    public function update(Request $request, $saleId)
    {
        // Validación de los campos
        $validator = Validator::make($request->all(), [
            'customer' => 'required|exists:customers,id',
            'total' => 'required|numeric|min:0',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Obtener la venta por ID
        $sale = Sale::findOrFail($saleId);
        $sale->customer_id = $request->customer;
        $sale->total = $request->total;
        $sale->save();

        // Recuperar los productos actuales en la venta
        $currentProducts = $sale->products;

        // Procesar cada producto en la venta
        foreach ($request->products as $productData) {
            $product = Product::findOrFail($productData['product_id']);

            if ($sale->products->contains($product->id)) {
                $currentQuantity = $sale->products->find($product->id)->pivot->quantity;
                $stockDifference = $productData['quantity'] - $currentQuantity;

                if ($stockDifference < 0) {
                    // Aumentar el stock si la cantidad de venta disminuye
                    $product->increment('stock', abs($stockDifference));
                } elseif ($stockDifference > 0) {
                    // Decrementar el stock si la cantidad de venta aumenta
                    if ($product->stock < $stockDifference) {
                        return response()->json(['error' => "No hay suficiente stock para el producto {$product->name}."], 400);
                    }
                    $product->decrement('stock', $stockDifference);
                }

                // Actualizar la cantidad del producto en la venta
                $sale->products()->updateExistingPivot($product->id, [
                    'quantity' => $productData['quantity'],
                    'subtotal' => $productData['quantity'] * $product->sale_price
                ]);
            } else {
                // Nuevo producto agregado a la venta
                if ($product->stock < $productData['quantity']) {
                    return response()->json(['error' => "No hay suficiente stock para el producto {$product->name}."], 400);
                }

                // Reducir stock
                $product->decrement('stock', $productData['quantity']);

                // Asociar el nuevo producto a la venta
                $sale->products()->attach($product->id, [
                    'quantity' => $productData['quantity'],
                    'unit_price' => $product->sale_price,
                    'subtotal' => $productData['quantity'] * $product->sale_price,
                ]);
            }
        }

        // Eliminar productos que ya no están en la venta
        $updatedProductIds = collect($request->products)->pluck('product_id')->toArray();
        $deletedProductIds = $currentProducts->pluck('id')->diff($updatedProductIds);

        foreach ($deletedProductIds as $productId) {
            $product = Product::find($productId);
            $quantity = $sale->products->find($productId)->pivot->quantity;

            // Revertir el stock
            $product->increment('stock', $quantity);

            // Desasociar el producto de la venta
            $sale->products()->detach($productId);
        }

        // Generar PDF de la venta actualizada
        $pdfData = [
            'sale' => $sale->fresh('products', 'customer', 'user'),
            'products' => $sale->products,
            'total' => $sale->total,
            'date' => now()->format('Y-m-d H:i'),
            'user' => $sale->user->name,
        ];

        $pdf = Pdf::loadView('pdf.sale', $pdfData);
        $pdfPath = 'public/sale/' . now()->format('Y-m-d') . '/sale-' . $sale->id . '.pdf';
        Storage::put($pdfPath, $pdf->output());

        // Actualizar el nombre del archivo PDF en la base de datos
        $sale->update(['pdf_filename' => 'sale-' . $sale->id . '.pdf']);

        // Enviar el PDF por correo al cliente
        Mail::send([], [], function ($message) use ($sale, $pdf) {
            $message->to($sale->customer->email)
                ->subject('Factura de Venta Actualizada')
                ->attachData($pdf->output(), 'factura-' . $sale->id . '.pdf');
        });

        return response()->json(['success' => 'Venta actualizada correctamente.']);
    }

    /**
     * Eliminar una venta.
     */
    public function destroy(Sale $sale)
    {
        // Restaurar stock de los productos asociados
        foreach ($sale->products as $product) {
            $productModel = Product::find($product->id);
            $productModel->increment('stock', $product->pivot->quantity);
        }

        // Desasociar productos y eliminar la venta
        $sale->products()->detach();
        $sale->delete();

        return response()->json(['success' => 'Venta eliminada con éxito.']);
    }

    /**
     * Buscar productos basados en un término de búsqueda.
     * Este método responde a solicitudes AJAX para la barra de búsqueda.
     */
    public function searchProducts(Request $request)
    {
        $search = $request->input('search');

        // Validar el término de búsqueda
        $validator = Validator::make($request->all(), [
            'search' => 'required|string|min:2',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Buscar productos cuyo nombre contenga el término de búsqueda (case-insensitive)
        $products = Product::where('name', 'LIKE', "%{$search}%")
                            ->where('stock', '>', 0) // Opcional: solo mostrar productos con stock
                            ->select('id', 'name', 'sale_price')
                            ->limit(10)
                            ->get();

        return response()->json(['products' => $products]);
    }

    /**
     * Agregar un producto al carrito.
     * Este método responde a solicitudes AJAX para agregar productos al carrito.
     * Gestiona el carrito utilizando la sesión.
     */
    public function addToCart(Request $request)
    {
        // Validar los datos recibidos
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $product = Product::findOrFail($request->product_id);

        // Verificar si hay suficiente stock
        if ($product->stock < $request->quantity) {
            return response()->json(['error' => "No hay suficiente stock para el producto {$product->name}."], 400);
        }

        // Obtener el carrito de la sesión o crear uno nuevo
        $cart = Session::get('cart', []);

        // Si el producto ya está en el carrito, actualizar la cantidad
        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $request->quantity;
            $cart[$product->id]['subtotal'] = $cart[$product->id]['price'] * $cart[$product->id]['quantity'];
        } else {
            // Agregar el producto al carrito
            $cart[$product->id] = [
                'name' => $product->name,
                'price' => floatval($product->sale_price),
                'quantity' => $request->quantity,
                'subtotal' => floatval($product->sale_price) * $request->quantity,
            ];
        }

        // Actualizar la sesión con el nuevo carrito
        Session::put('cart', $cart);

        return response()->json(['success' => 'Producto agregado al carrito correctamente.']);
    }

    /**
     * Obtener el contenido actual del carrito.
     * Este método responde a solicitudes AJAX para obtener el carrito.
     */
    public function getCart()
    {
        $cart = Session::get('cart', []);

        // Asegurar que los precios y cantidades son numéricos
        foreach ($cart as &$item) {
            $item['price'] = floatval($item['price']);
            $item['quantity'] = intval($item['quantity']);
            $item['subtotal'] = floatval($item['subtotal']);
        }

        return response()->json(['cart' => $cart]);
    }

    /**
     * Eliminar un producto del carrito.
     * Este método responde a solicitudes AJAX para eliminar productos del carrito.
     */
    public function removeFromCart(Request $request)
    {
        // Validar los datos recibidos
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $productId = $request->input('product_id');

        $cart = Session::get('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            Session::put('cart', $cart);
            return response()->json(['success' => 'Producto eliminado del carrito correctamente.']);
        }

        return response()->json(['error' => 'Producto no encontrado en el carrito.'], 404);
    }
    public function createCustomer(Request $request)
    {
    // Validar los datos del cliente
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:customers,email',
        'phone' => 'required|string|max:20',
        'address' => 'required|string|max:255',
    ]);

    // Crear el cliente
    $customer = new Customer();
    $customer->name = $request->name;
    $customer->email = $request->email;
    $customer->phone = $request->phone;
    $customer->address = $request->address;
    $customer->save();

    // Responder con los datos del cliente creado
    return response()->json(['customer' => $customer]);
    }


}
