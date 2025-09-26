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

        <h2 class="mb-4">Registrar Nueva Empresa</h2>

        <!-- Formulario -->
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('empresas.store') }}" method="POST">
                    @csrf

                    <!-- Tipo Empresa -->
                    <div class="mb-3">
                        <label for="nTipoEmpresa" class="form-label">Tipo de Empresa</label>
                        <select class="form-select" id="nTipoEmpresa" name="nTipoEmpresa" required>
                            <option value="">--Seleccionar--</option>
                            @foreach($tiposEmpresa as $tipo)
                                <option value="{{ $tipo->nConstValor }}">
                                    {{ $tipo->nConstDescripcion }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Nombre Empresa -->
                    <div class="mb-3">
                        <label for="cNombreEmpresa" class="form-label">Razón Social</label>
                        <input type="text" class="form-control" id="cNombreEmpresa" name="cNombreEmpresa" required>
                    </div>

                    <!-- Representante -->
                    <div class="mb-3">
                        <label for="nRepresentanteLegal" class="form-label">Representante Legal</label>
                        <input type="text" class="form-control" id="nRepresentanteLegal" name="nRepresentanteLegal">
                    </div>

                    <!-- Profesión -->
                    <div class="mb-3">
                        <label for="nProfesion" class="form-label">Profesión del Representante</label>
                        <select class="form-select" id="nProfesion" name="nProfesion">
                            <option value="">--Seleccionar--</option>
                            @foreach($profesiones as $profesion)
                                <option value="{{ $profesion->nConstValor }}">
                                    {{ $profesion->nConstDescripcion }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Cargo -->
                    <div class="mb-3">
                        <label for="nCargo" class="form-label">Cargo del Representante</label>
                        <select class="form-select" id="nCargo" name="nCargo">
                            <option value="">--Seleccionar--</option>
                            @foreach($cargos as $cargo)
                                <option value="{{ $cargo->nConstValor }}">
                                    {{ $cargo->nConstDescripcion }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- RUC -->
                    <div class="mb-3">
                        <label for="nRUC" class="form-label">RUC</label>
                        <input type="text" class="form-control" id="nRUC" name="nRUC" required>
                    </div>

                    <!-- Dirección -->
                    <div class="mb-3">
                        <label for="cDireccion" class="form-label">Dirección</label>
                        <input type="text" class="form-control" id="cDireccion" name="cDireccion">
                    </div>

                    <!-- Correo -->
                    <div class="mb-3">
                        <label for="cCorreo" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="cCorreo" name="cCorreo">
                    </div>

                    <!-- Teléfono -->
                    <div class="mb-3">
                        <label for="nTelefono" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="nTelefono" name="nTelefono">
                    </div>

                    <!-- Botones -->
                    <div class="mt-4">
                        <a href="{{ route('empresas.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Volver
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle"></i> Registrar
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




