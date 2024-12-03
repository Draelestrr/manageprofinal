@extends('layouts.app')

@section('page-title', 'Editar Producto')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header pb-0">
                    <h6>Editar Producto</h6>
                </div>
                <div class="card-body">
                    <!-- Depuración: Verificar los valores de old() y $product->suppliers -->
                    {{-- Elimina el dd() después de depurar --}}
                    {{-- {{ dd(old('suppliers', $product->suppliers->pluck('id')->toArray())) }} --}}

                    <form action="{{ route('products.update', $product->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre</label>
                            <input type="text" name="name" value="{{ old('name', $product->name) }}" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Categoría</label>
                            <select name="category_id" class="form-control" required>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="suppliers" class="form-label">Proveedores</label>
                            <select name="suppliers[]" class="form-control" multiple>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}"
                                        {{ in_array($supplier->id, old('suppliers', $product->suppliers->pluck('id')->toArray())) ? 'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>

                        </div>
                        <div class="mb-3">
                            <label for="purchase_price" class="form-label">Precio de Compra</label>
                            <input type="text" name="purchase_price" value="{{ old('purchase_price', $product->purchase_price) }}" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="sale_price" class="form-label">Precio de Venta</label>
                            <input type="text" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="stock" class="form-label">Stock</label>
                            <input type="text" name="stock" value="{{ old('stock', $product->stock) }}" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="stock_min" class="form-label">Stock Mínimo</label>
                            <input type="text" name="stock_min" value="{{ old('stock_min', $product->stock_min) }}" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="image_path" class="form-label">Imagen (URL)</label>
                            <input type="text" name="image_path" value="{{ old('image_path', $product->image_path) }}" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
