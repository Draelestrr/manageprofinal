@extends('layouts.app')

@section('page-title', 'Entradas de Stock')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h6>Entradas de Stock</h6>
                    <a href="{{ route('stock_entries.create') }}" class="btn btn-primary">Nueva Entrada de Stock</a>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Producto</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Cantidad</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Precio de Compra</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Fecha de Entrada</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stockEntries as $entry)
                                    <tr>
                                        <td>{{ $entry->product->name }}</td>
                                        <td>{{ $entry->quantity }}</td>
                                        <td>${{ number_format($entry->purchase_price, 2) }}</td>
                                        <td>{{ $entry->created_at->format('d/m/Y') }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('stock_entries.edit', $entry->id) }}" class="btn btn-warning btn-sm">Editar</a>
                                            <form action="{{ route('stock_entries.destroy', $entry->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
