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
                Aquí podrás gestionar usuarios, cartas de presentación, empresas, estudiantes, docentes, supervisiones y documentos del sistema.
            </p>
        </div>
    </div>
</div>

{{-- Ventana emergente para alertas --}}
@if($alertas->count() > 0)
    <div class="modal fade" id="alertasModal" tabindex="-1" aria-labelledby="alertasLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-warning">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="alertasLabel">
                        ⚠️ Alumnos por terminar sus prácticas
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <ul class="list-group">
                        @foreach($alertas as $alerta)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $alerta->cNombre }} {{ $alerta->cApellido }}
                                <span class="badge bg-danger">
                                    Finaliza: {{ \Carbon\Carbon::parse($alerta->dFechaFin)->format('d/m/Y') }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Script para mostrar la ventana automáticamente --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var modal = new bootstrap.Modal(document.getElementById('alertasModal'));
            modal.show();
        });
    </script>
@endif
@endsection

