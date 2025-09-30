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
        </ul>

    </div>

    <!-- Contenido -->
    <div class="flex-grow-1 p-4" style="margin-left: 250px;">
        <div class="d-flex justify-content-between mb-3">
            <h2>Registrar Estudiante</h2>
            <a href="{{ route('estudiantes.index') }}" class="btn btn-secondary">
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

                <form action="{{ route('estudiantes.store') }}" method="POST">
                    @csrf

                    <!-- Datos Personales -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="cNombre" class="form-control" value="{{ old('cNombre') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Apellido</label>
                            <input type="text" name="cApellido" class="form-control" value="{{ old('cApellido') }}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">DNI</label>
                            <input type="text" name="cDNI" class="form-control" 
                                   value="{{ old('cDNI') }}" required 
                                   pattern="\d{8}" title="El DNI debe tener 8 números">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Correo</label>
                            <input type="email" name="cCorreo" class="form-control" value="{{ old('cCorreo') }}" required>
                        </div>
                    </div>

                    <!-- Datos de Estudiante -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label class="form-label">Celular</label>
                            <input type="text" name="nCelular" class="form-control" 
                                   value="{{ old('nCelular') }}" required 
                                   pattern="\d{9}" title="El celular debe tener 9 números">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Programa de Estudios</label>
                            <select name="nProgramaEstudios" class="form-control" required>
                                <option value="">--Seleccionar--</option>
                                @foreach($programas as $prog)
                                    <option value="{{ $prog->nConstValor }}" 
                                        {{ old('nProgramaEstudios') == $prog->nConstValor ? 'selected' : '' }}>
                                        {{ $prog->nConstDescripcion }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Plan de Estudio</label>
                            <select name="nPlanEstudio" class="form-control" required>
                                <option value="">--Seleccionar--</option>
                                @foreach($planes as $plan)
                                    <option value="{{ $plan->nConstValor }}" 
                                        {{ old('nPlanEstudio') == $plan->nConstValor ? 'selected' : '' }}>
                                        {{ $plan->nConstDescripcion }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Módulo Formativo</label>
                            <select name="nModuloFormativo" class="form-control" required>
                                <option value="">--Seleccionar--</option>
                                @foreach($modulos as $mod)
                                    <option value="{{ $mod->nConstValor }}" 
                                        {{ old('nModuloFormativo') == $mod->nConstValor ? 'selected' : '' }}>
                                        {{ $mod->nConstDescripcion }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Turno</label>
                            <select name="nTurno" class="form-control" required>
                                <option value="">--Seleccionar--</option>
                                @foreach($turnos as $tur)
                                    <option value="{{ $tur->nConstValor }}" 
                                        {{ old('nTurno') == $tur->nConstValor ? 'selected' : '' }}>
                                        {{ $tur->nConstDescripcion }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-save"></i> Guardar Estudiante
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







