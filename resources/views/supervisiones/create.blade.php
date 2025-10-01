{{-- resources/views/supervisiones/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="d-flex" style="min-height: 100vh;">

    <!-- Sidebar -->
    <div class="text-white p-3 d-flex flex-column position-fixed" 
         style="width: 250px; height: 100vh; background-color: #99001F;">
        <div class="text-center mb-4">
            <h4 class="fw-bold">Sistema EFSRT</h4>
        </div>

        <ul class="nav flex-column mb-4">
            <!-- Gestión de Usuarios -->
            <li class="nav-item mb-2">
                <a class="nav-link text-white {{ request()->is('usuarios*') ? 'active fw-bold' : '' }}" 
                   href="{{ route('usuarios.index') }}">
                    <i class="bi bi-people-fill me-2"></i> Gestionar Usuarios
                </a>
            </li>

            <!-- Módulo de trámites -->
            <li class="nav-item mb-2">
                <a class="nav-link text-white {{ request()->is('cartas*') ? 'active fw-bold' : '' }}" 
                   href="{{ route('cartas.index') }}">
                    <i class="bi bi-file-earmark-text-fill me-2"></i> Gestionar Trámites
                </a>
            </li>

            <!-- Módulo de empresas -->
            <li class="nav-item mb-2">
                <a class="nav-link text-white {{ request()->is('empresas*') ? 'active fw-bold' : '' }}" 
                   href="{{ route('empresas.index') }}">
                    <i class="bi bi-building me-2"></i> Gestionar Empresas
                </a>
            </li>

            <!-- Módulo de estudiantes -->
            <li class="nav-item mb-2">
                <a class="nav-link text-white {{ request()->is('estudiantes*') ? 'active fw-bold' : '' }}" 
                   href="{{ route('estudiantes.index') }}">
                    <i class="bi bi-mortarboard-fill me-2"></i> Gestionar Estudiantes
                </a>
            </li>

            <!-- Módulo de docentes -->
            <li class="nav-item mb-2">
                <a class="nav-link text-white {{ request()->is('docentes*') ? 'active fw-bold' : '' }}" 
                   href="{{ route('docentes.index') }}">
                    <i class="bi bi-person-badge-fill me-2"></i> Gestionar Docentes
                </a>
            </li>

            <!-- Módulo de supervisiones -->
            <li class="nav-item mb-2">
                <a class="nav-link text-white {{ request()->is('supervisiones*') ? 'active fw-bold' : '' }}" 
                   href="{{ route('supervisiones.index') }}">
                    <i class="bi bi-journal-check me-2"></i> Gestionar Supervisiones
                </a>
            </li>

            <!-- Módulo de detalle de supervisión -->
            <li class="nav-item mb-2">
                <a class="nav-link text-white {{ request()->is('detalle_supervisiones*') ? 'active fw-bold' : '' }}" 
                href="{{ route('detalle_supervisiones.index') }}">
                    <i class="bi bi-journal-text me-2"></i> Supervisión Detalle
                </a>
            </li>

            <!-- Módulo de documentos -->
            <li class="nav-item mb-2">
                <a class="nav-link text-white {{ request()->is('documentos*') ? 'active fw-bold' : '' }}" 
                   href="{{ route('documentos.index') }}">
                    <i class="bi bi-file-earmark-pdf-fill me-2"></i> Gestionar Documentos
                </a>
            </li>
        </ul>

    </div>

    <!-- Contenido -->
    <div class="flex-grow-1 p-4" style="margin-left: 250px;">
        <div class="d-flex justify-content-between mb-3">
            <h2>Registrar Supervisión</h2>
            <a href="{{ route('supervisiones.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">

                <!-- Mensajes de error -->
                @if ($errors->any())
                    <div class="alert alert-warning">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('supervisiones.store') }}" method="POST">
                    @csrf

                    <!-- Docente -->
                    <div class="mb-3">
                        <label class="form-label">Docente</label>
                        <select name="IdDocente" class="form-select" required>
                            <option value="">-- Seleccione --</option>
                            @foreach($docentes as $docente)
                                <option value="{{ $docente->IdDocente }}" 
                                    {{ old('IdDocente') == $docente->IdDocente ? 'selected' : '' }}>
                                    {{ $docente->persona->cNombre }} {{ $docente->persona->cApellido }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Carta Presentación -->
                    <div class="mb-3">
                        <label class="form-label">Carta de Presentación</label>
                        <select name="IdCartaPresentacion" class="form-select" required>
                            <option value="">-- Seleccione --</option>
                            @foreach($cartas as $carta)
                                <option value="{{ $carta->IdCartaPresentacion }}" 
                                    {{ old('IdCartaPresentacion') == $carta->IdCartaPresentacion ? 'selected' : '' }}>
                                    Carta #{{ $carta->nNroCarta }} - Estudiante: {{ $carta->estudiante->persona->cNombre }} {{ $carta->estudiante->persona->cApellido }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Nota -->
                    <div class="mb-3">
                        <label class="form-label">Nota</label>
                        <input type="number" name="nNota" class="form-control" min="0" max="20" value="{{ old('nNota') }}">
                    </div>

                    <!-- Fechas -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Fecha Inicio</label>
                            <input type="date" name="dFechaInicio" class="form-control" value="{{ old('dFechaInicio') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fecha Fin</label>
                            <input type="date" name="dFechaFin" class="form-control" value="{{ old('dFechaFin') }}" required>
                        </div>
                    </div>

                    <!-- Horas -->
                    <div class="mb-3">
                        <label class="form-label">Horas</label>
                        <input type="number" name="nHoras" class="form-control" min="1" value="{{ old('nHoras') }}" required>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-save"></i> Guardar Supervisión
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


