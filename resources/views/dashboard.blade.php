{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="d-flex flex-column">
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

    <h2>Dashboard</h2>
    <p>
        Bienvenido, 
        <strong>{{ Auth::user()->persona->cNombre ?? '' }} {{ Auth::user()->persona->cApellido ?? '' }}</strong>
    </p>

    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title">Panel de Control</h5>
            <p class="card-text">
                Aquí podrás gestionar usuarios, trámites de cartas de presentación, empresas vinculadas, estudiantes, docentes, supervisiones y documentos del sistema.
            </p>
        </div>
    </div>
</div>
@endsection
