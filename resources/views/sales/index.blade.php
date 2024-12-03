@extends('layouts.app')

@section('page-title', 'Ver Ventas')

@section('content')
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header pb-0">
            <h6>Lista de Ventas</h6>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sales as $sale)
                        <tr>
                            <td>{{ $sale->created_at }}</td>
                            <td>${{ $sale->total }}</td>
                            <td>
                                <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-info">Ver Detalles</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
