{{-- resources/views/detalle_supervisiones/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="d-flex" style="min-height: 100vh;">

    <!-- Sidebar fijo -->
    <div class="text-white p-3 d-flex flex-column position-fixed"
         style="width: 250px; height: 100vh; background-color: #99001F;">
        <div class="text-center mb-4">
            <h4 class="fw-bold">Sistema EFSRT</h4>
        </div>

        <ul class="nav flex-column mb-4">
            <li class="nav-item mb-2">
                <a class="nav-link text-white {{ request()->is('usuarios*') ? 'active fw-bold' : '' }}"
                   href="{{ route('usuarios.index') }}">
                    <i class="bi bi-people-fill me-2"></i> Gestionar Usuarios
                </a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link text-white {{ request()->is('cartas*') ? 'active fw-bold' : '' }}"
                   href="{{ route('cartas.index') }}">
                    <i class="bi bi-file-earmark-text-fill me-2"></i> Gestionar Trámites
                </a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link text-white {{ request()->is('empresas*') ? 'active fw-bold' : '' }}"
                   href="{{ route('empresas.index') }}">
                    <i class="bi bi-building me-2"></i> Gestionar Empresas
                </a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link text-white {{ request()->is('estudiantes*') ? 'active fw-bold' : '' }}"
                   href="{{ route('estudiantes.index') }}">
                    <i class="bi bi-mortarboard-fill me-2"></i> Gestionar Estudiantes
                </a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link text-white {{ request()->is('docentes*') ? 'active fw-bold' : '' }}"
                   href="{{ route('docentes.index') }}">
                    <i class="bi bi-person-badge-fill me-2"></i> Gestionar Docentes
                </a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link text-white {{ request()->is('supervisiones*') ? 'active fw-bold' : '' }}"
                   href="{{ route('supervisiones.index') }}">
                    <i class="bi bi-journal-check me-2"></i> Gestionar Supervisiones
                </a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link text-white {{ request()->is('detalle_supervisiones*') ? 'active fw-bold' : '' }}"
                   href="{{ route('detalle_supervisiones.index') }}">
                    <i class="bi bi-journal-text me-2"></i> Supervisión Detalle
                </a>
            </li>
        </ul>
    </div>

    <!-- Contenido principal -->
    <div class="flex-grow-1 p-4" style="margin-left: 250px;">
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

        <!-- Encabezado -->
        <h2 class="mb-4">Editar Detalle de Supervisión</h2>

        <!-- Formulario -->
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('detalle_supervisiones.update', $detalle->IdSupervisionDetalle) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Supervisión -->
                    <div class="mb-3">
                        <label for="IdSupervision" class="form-label">Supervisión</label>
                        <select name="IdSupervision" id="IdSupervision" class="form-select" required>
                            <option value="">-- Seleccionar Supervisión --</option>
                            @foreach($supervisiones as $supervision)
                                <option value="{{ $supervision->IdSupervision }}"
                                    {{ $detalle->IdSupervision == $supervision->IdSupervision ? 'selected' : '' }}>
                                    Docente: {{ optional($supervision->docente->persona)->cNombre }} {{ optional($supervision->docente->persona)->cApellido }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- N° Supervisión -->
                    <div class="mb-3">
                        <label for="nNroSupervision" class="form-label">N° Supervisión</label>
                        <input type="number" name="nNroSupervision" id="nNroSupervision" 
                               value="{{ old('nNroSupervision', $detalle->nNroSupervision) }}" 
                               class="form-control" required>
                    </div>

                    <!-- Fecha Supervisión -->
                    <div class="mb-3">
                        <label for="dFechaSupervision" class="form-label">Fecha de Supervisión</label>
                        <input type="date" name="dFechaSupervision" id="dFechaSupervision" 
                               value="{{ old('dFechaSupervision', $detalle->dFechaSupervision) }}" 
                               class="form-control" required>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('detalle_supervisiones.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Volver
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Bootstrap Icons --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endsection

