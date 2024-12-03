@extends('layouts.app')

@section('page-title', 'Editar Categoría')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header pb-0">
                    <h6>Editar Categoría</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('categories.update', $category->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre</label>
                            <input type="text" name="name" value="{{ $category->name }}" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Descripción</label>
                            <textarea name="description" class="form-control">{{ $category->description }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                        <a href="{{ route('categories.index') }}" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
