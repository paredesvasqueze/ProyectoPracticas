@extends('layouts.app')

@section('content')
<div class="p-4">

    <!-- Usuario arriba a la derecha -->
    <div class="d-flex justify-content-end mb-3">
        <div class="text-end">
            <small>
                Usuario: {{ Auth::user()->persona->cNombre ?? '' }} {{ Auth::user()->persona->cApellido ?? '' }}
            </small>
            <div class="mt-2">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                    </button>
                </form>
            </div>
        </div>
    </div>

    <h2 class="mb-4">Editar Reporte</h2>

    <!-- Mensaje de éxito -->
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Formulario para editar reporte -->
    <form action="{{ route('reportes.update', $reporte->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del Reporte</label>
            <input type="text" name="nombre" id="nombre" class="form-control" 
                   value="{{ $reporte->nombre }}" required>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-circle me-2"></i> Guardar Cambios
        </button>
        <a href="{{ route('reportes.index') }}" class="btn btn-secondary ms-2">Cancelar</a>
    </form>

</div>

{{-- Bootstrap Icons --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endsection
