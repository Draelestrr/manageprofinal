@extends('layouts.app')

@section('page-title', 'Carrito de Compras')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">Carrito de Compras</h2>
    <div class="tab-content">
        <!-- Paso 1: Selección de Productos -->
        <div class="tab-pane active" id="step1">
            <h4>Seleccionar Productos</h4>
            <div class="input-group mb-3">
                <input type="text" id="product-search" class="form-control" placeholder="Buscar productos...">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button">Buscar</button>
                </div>
            </div>
            <div id="product-list" class="mb-3">
                <!-- Lista de productos filtrados -->
            </div>
            <table class="table table-bordered" id="cart-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="cart-items">
                    <!-- Productos en el carrito -->
                </tbody>
            </table>
            <button class="btn btn-primary" id="next-to-customer">Siguiente: Seleccionar Cliente</button>
        </div>

        <!-- Paso 2: Selección o Creación de Cliente -->
        <div class="tab-pane" id="step2">
            <h4>Seleccionar o Crear Cliente</h4>
            <select id="customer-select" class="form-control mb-3">
                <option value="">Seleccione un cliente existente</option>
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                @endforeach
            </select>
            <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#createCustomerModal">Crear Nuevo Cliente</button>
            <button class="btn btn-primary mt-3" id="next-to-payment">Siguiente: Método de Pago</button>
        </div>

        <!-- Paso 3: Selección de Método de Pago -->
        <div class="tab-pane" id="step3">
            <h4>Método de Pago</h4>
            <select id="payment-method" class="form-control mb-3">
                <option value="transfer">Transferencia</option>
                <option value="cash">Efectivo</option>
                <option value="card">Tarjeta</option>
            </select>
            <div id="cash-payment" class="mt-3" style="display: none;">
                <input type="number" id="cash-received" class="form-control mb-2" placeholder="Efectivo Recibido">
                <p>Vuelto: $<span id="change-amount">0.00</span></p>
            </div>
            <button class="btn btn-primary" id="next-to-summary">Siguiente: Resumen de Venta</button>
        </div>

        <!-- Paso 4: Resumen de Venta -->
        <div class="tab-pane" id="step4">
            <h4>Resumen de Venta</h4>
            <div id="sale-summary">
                <!-- Mostrar resumen de la venta -->
            </div>
            <button class="btn btn-success" id="confirm-sale">Confirmar Venta</button>
        </div>
    </div>
</div>

<!-- Modal para crear nuevo cliente -->
<div class="modal fade" id="createCustomerModal" tabindex="-1" role="dialog" aria-labelledby="createCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="create-customer-form">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createCustomerModalLabel">Crear Nuevo Cliente</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                        <div class="form-group">
                            <label for="new-customer-name">Nombre <span class="text-danger">*</span></label>
                            <input type="text" id="new-customer-name" class="form-control" placeholder="Nombre del Cliente" required>
                        </div>
                        <div class="form-group">
                            <label for="new-customer-email">Email <span class="text-danger">*</span></label>
                            <input type="email" id="new-customer-email" class="form-control" placeholder="Email del Cliente" required>
                        </div>
                        <div class="form-group">
                            <label for="new-customer-phone">Teléfono <span class="text-danger">*</span></label>
                            <input type="text" id="new-customer-phone" class="form-control" placeholder="Teléfono del Cliente" required>
                        </div>
                        <div class="form-group">
                            <label for="new-customer-address">Dirección <span class="text-danger">*</span></label>
                            <input type="text" id="new-customer-address" class="form-control" placeholder="Dirección del Cliente" required>
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success" id="save-new-customer">Guardar Cliente</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para confirmaciones y alertas -->
<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Mensaje de confirmación -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="confirm-action">Confirmar</button>
            </div>
        </div>
    </div>
</div>
@endsection


@push('scripts')

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- jQuery (asegúrate de que jQuery está incluido antes de este script) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JS (asegúrate de que Bootstrap está incluido para los modales) -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

<!-- Scripts Personalizados -->
<script>
    $(document).ready(function () {
        let cart = [];
        let selectedCustomer = null;
        let paymentMethod = null;

        // Inicializar el carrito desde la sesión
        loadCart();

        // Búsqueda de productos
        $('#product-search').on('input', function () {
            const search = $(this).val().trim();
            if (search.length > 2) {
                $.ajax({
                    url: '{{ route('cart.searchProducts') }}',
                    method: 'GET',
                    data: { search: search },
                    success: function (data) {
                        let productList = $('#product-list');
                        productList.empty();
                        if (data.products.length > 0) {
                            data.products.forEach(function (product) {
                                productList.append(`
                                    <div class="product-item card mb-2" style="width: 18rem;" data-id="${product.id}" data-name="${product.name}" data-price="${product.sale_price}">
                                        <div class="card-body">
                                            <h5 class="card-title">${product.name}</h5>
                                            <p class="card-text">Precio: $${parseFloat(product.sale_price).toFixed(2)}</p>
                                            <button class="btn btn-sm btn-primary add-to-cart">Agregar</button>
                                        </div>
                                    </div>
                                `);
                            });
                        } else {
                            productList.append(`<p>No se encontraron productos.</p>`);
                        }
                    },
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error al buscar productos.',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    }
                });
            } else {
                $('#product-list').empty();
            }
        });

        // Agregar producto al carrito
        $('#product-list').on('click', '.add-to-cart', function () {
            const productId = $(this).closest('.product-item').data('id');
            const productName = $(this).closest('.product-item').data('name');
            const productPrice = parseFloat($(this).closest('.product-item').data('price'));

            if (isNaN(productPrice)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Precio del producto inválido.',
                    showConfirmButton: false,
                    timer: 3000
                });
                return;
            }

            addToCart(productId, 1, productName, productPrice);
        });

        // Función para agregar al carrito
        function addToCart(productId, quantity, name, price) {
            $.ajax({
                url: '{{ route('cart.add') }}',
                method: 'POST',
                data: {
                    product_id: productId,
                    quantity: quantity,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    // Verificar si el producto ya existe en el carrito
                    const existingItem = cart.find(item => item.id === productId);
                    if (existingItem) {
                        existingItem.quantity += quantity;
                        existingItem.subtotal = (existingItem.price * existingItem.quantity).toFixed(2);
                    } else {
                        cart.push({
                            id: productId,
                            name: name,
                            price: parseFloat(price).toFixed(2),
                            quantity: quantity,
                            subtotal: (price * quantity).toFixed(2)
                        });
                    }
                    updateCartDisplay();
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: response.success,
                        showConfirmButton: false,
                        timer: 1500
                    });
                },
                error: function (xhr) {
                    const error = xhr.responseJSON.error || 'Error al agregar producto al carrito.';
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error,
                        showConfirmButton: false,
                        timer: 3000
                    });
                }
            });
        }

        // Actualizar visualización del carrito
        function updateCartDisplay() {
            const cartItems = $('#cart-items');
            cartItems.empty();

            let total = 0;

            cart.forEach(item => {
                // Asegurarse de que item.price es un número
                const price = parseFloat(item.price);
                const quantity = parseInt(item.quantity);

                // Verificar si la conversión fue exitosa
                if (isNaN(price) || isNaN(quantity)) {
                    console.error(`Datos inválidos para el producto ${item.name}: price=${item.price}, quantity=${item.quantity}`);
                    return;
                }

                const subtotal = price * quantity;
                total += subtotal;

                cartItems.append(`
                    <tr>
                        <td>${item.name}</td>
                        <td>
                            <input type="number" class="form-control quantity" data-id="${item.id}" value="${quantity}" min="1">
                        </td>
                        <td>$${price.toFixed(2)}</td>
                        <td>$${subtotal.toFixed(2)}</td>
                        <td>
                            <button class="btn btn-sm btn-danger remove-from-cart" data-id="${item.id}">Eliminar</button>
                        </td>
                    </tr>
                `);
            });

            // Actualizar el total en la tabla o donde corresponda
            // Primero, eliminar cualquier pie de tabla existente para evitar duplicados
            $('#cart-table tfoot').remove();

            if (cart.length > 0) {
                $('#cart-table').append(`
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-right">Total:</th>
                            <th colspan="2">$${total.toFixed(2)}</th>
                        </tr>
                    </tfoot>
                `);
            }
        }

        // Cambiar cantidad en el carrito
        $('#cart-items').on('change', '.quantity', function () {
            const productId = $(this).data('id');
            const newQuantity = parseInt($(this).val());

            if (isNaN(newQuantity) || newQuantity < 1) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'La cantidad debe ser al menos 1.',
                    showConfirmButton: false,
                    timer: 3000
                });
                $(this).val(1);
                return;
            }

            // Actualizar la cantidad en el carrito
            const item = cart.find(item => item.id === productId);
            if (item) {
                item.quantity = newQuantity;
                item.subtotal = (item.price * newQuantity).toFixed(2);
                updateCartDisplay();
            }
        });

        // Eliminar producto del carrito
        $('#cart-items').on('click', '.remove-from-cart', function () {
            const productId = $(this).data('id');

            // Eliminar del carrito en el servidor
            $.ajax({
                url: '{{ route('cart.remove') }}',
                method: 'POST',
                data: {
                    product_id: productId,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    cart = cart.filter(item => item.id !== productId);
                    updateCartDisplay();
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: response.success,
                        showConfirmButton: false,
                        timer: 1500
                    });
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al eliminar el producto del carrito.',
                        showConfirmButton: false,
                        timer: 3000
                    });
                }
            });
        });

        // Manejo de pasos
        $('#next-to-customer').click(function () {
            if (cart.length === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Carrito Vacío',
                    text: 'Agrega productos al carrito antes de continuar.',
                    showConfirmButton: false,
                    timer: 2000
                });
                return;
            }
            $('.tab-pane').removeClass('active');
            $('#step2').addClass('active');
        });

        $('#next-to-payment').click(function () {
            selectedCustomer = $('#customer-select').val();
            if (!selectedCustomer) {
                Swal.fire({
                    icon: 'error',
                    title: 'Cliente No Seleccionado',
                    text: 'Por favor, selecciona un cliente o crea uno nuevo.',
                    showConfirmButton: false,
                    timer: 2000
                });
                return;
            }
            $('.tab-pane').removeClass('active');
            $('#step3').addClass('active');
        });

        $('#next-to-summary').click(function () {
            paymentMethod = $('#payment-method').val();
            if (paymentMethod === 'cash') {
                const cashReceived = parseFloat($('#cash-received').val());
                const total = calculateTotal();
                if (isNaN(cashReceived) || cashReceived < total) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Efectivo Insuficiente',
                        text: 'El efectivo recibido es menor al total de la venta.',
                        showConfirmButton: false,
                        timer: 2000
                    });
                    return;
                }
                $('#change-amount').text((cashReceived - total).toFixed(2));
            }
            $('.tab-pane').removeClass('active');
            $('#step4').addClass('active');
            displaySummary();
        });

        $('#confirm-sale').click(function () {
            if (cart.length === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Carrito Vacío',
                    text: 'No hay productos en el carrito para confirmar la venta.',
                    showConfirmButton: false,
                    timer: 2000
                });
                return;
            }

            // Recolectar los datos de la venta
            const saleData = {
                customer: selectedCustomer,
                total: calculateTotal(),
                products: cart.map(item => ({
                    product_id: item.id,
                    quantity: item.quantity
                })),
                _token: '{{ csrf_token() }}'
            };

            // Enviar los datos al servidor para crear la venta
            $.ajax({
                url: '{{ route('sales.store') }}',
                method: 'POST',
                data: saleData,
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Venta Confirmada!',
                        text: 'La venta se ha registrado exitosamente.',
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {
                        // Redireccionar o limpiar el carrito
                        window.location.href = '{{ route('sales.index') }}';
                    });
                },
                error: function (xhr) {
                    const error = xhr.responseJSON.errors || xhr.responseJSON.error || 'Error al procesar la venta.';
                    let errorMessage = '';
                    if (typeof error === 'object') {
                        Object.values(error).forEach(msg => {
                            errorMessage += msg + '<br>';
                        });
                    } else {
                        errorMessage = error;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        html: errorMessage,
                        showConfirmButton: false,
                        timer: 3000
                    });
                }
            });
        });

        // Mostrar u ocultar el campo de pago en efectivo
        $('#payment-method').change(function () {
            if ($(this).val() === 'cash') {
                $('#cash-payment').show();
            } else {
                $('#cash-payment').hide();
            }
        });

        // Guardar nuevo cliente
        $('#save-new-customer').click(function () {
    const customerData = {
        name: $('#new-customer-name').val(),
        email: $('#new-customer-email').val(),
        phone: $('#new-customer-phone').val(),
        address: $('#new-customer-address').val(),
        _token: '{{ csrf_token() }}' // Incluir el token CSRF
    };

    $.ajax({
        url: '{{ route('cart.createCustomer') }}', // Asegúrate de que la ruta sea correcta
        method: 'POST',  // El método debe ser POST
        data: customerData,
        success: function (response) {
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: 'Cliente creado exitosamente.',
                showConfirmButton: false,
                timer: 2000
            });

            // Cerrar el modal
            $('#createCustomerModal').modal('hide');

            // Actualizar la lista de clientes y seleccionarlo automáticamente
            $('#customer-select').append(new Option(response.customer.name, response.customer.id, true, true));

            // Reiniciar el formulario
            $('#create-customer-form')[0].reset();
        },
        error: function (xhr) {
            const error = xhr.responseJSON.errors || 'Error al crear cliente.';
            let errorMessage = '';
            if (typeof error === 'object') {
                Object.values(error).forEach(msg => {
                    errorMessage += msg + '<br>';
                });
            } else {
                errorMessage = error;
            }
            Swal.fire({
                icon: 'error',
                title: 'Error',
                html: errorMessage,
                showConfirmButton: false,
                timer: 3000
            });
        }
    });
});



        // Funciones auxiliares

        // Cargar el carrito desde la sesión al iniciar la página
        function loadCart() {
            $.ajax({
                url: '{{ route('cart.get') }}',
                method: 'GET',
                success: function (data) {
                    // Convertir los precios y cantidades a números
                    cart = Object.values(data.cart).map(item => ({
                        ...item,
                        price: parseFloat(item.price).toFixed(2),
                        quantity: parseInt(item.quantity)
                    }));
                    updateCartDisplay();
                },
                error: function () {
                    console.log('Error al cargar el carrito.');
                }
            });
        }

        function calculateTotal() {
            return cart.reduce((sum, item) => sum + (parseFloat(item.price) * parseInt(item.quantity)), 0);
        }

        function displaySummary() {
            const customerName = $('#customer-select option:selected').text();
            const total = calculateTotal();
            const summaryHtml = `
                <p><strong>Cliente:</strong> ${customerName}</p>
                <p><strong>Método de Pago:</strong> ${capitalizeFirstLetter(paymentMethod)}</p>
                <p><strong>Total:</strong> $${total.toFixed(2)}</p>
            `;
            $('#sale-summary').html(summaryHtml);
        }

        function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }
    });
</script>

@endpush

