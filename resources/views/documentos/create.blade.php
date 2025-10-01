{{-- resources/views/documentos/create.blade.php --}}
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
            <li class="nav-item mb-2">
                <a class="nav-link text-white {{ request()->is('documentos*') ? 'active fw-bold' : '' }}" 
                   href="{{ route('documentos.index') }}">
                    <i class="bi bi-file-earmark-pdf-fill me-2"></i> Gestionar Documentos
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

        <h2 class="mb-4">Registrar Nuevo Documento</h2>

        <!-- Mostrar errores de validación -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Formulario -->
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('documentos.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Número de Documento -->
                    <div class="mb-3">
                        <label for="cNroDocumento" class="form-label">Número de Documento</label>
                        <input type="text" class="form-control" id="cNroDocumento" name="cNroDocumento" 
                               value="{{ old('cNroDocumento') }}" required>
                    </div>

                    <!-- Fecha Documento -->
                    <div class="mb-3">
                        <label for="dFechaDocumento" class="form-label">Fecha del Documento</label>
                        <input type="date" class="form-control" id="dFechaDocumento" name="dFechaDocumento" 
                               value="{{ old('dFechaDocumento') }}" required>
                    </div>

                    <!-- Tipo Documento -->
                    <div class="mb-3">
                        <label for="cTipoDocumento" class="form-label">Tipo de Documento</label>
                        <select class="form-select" id="cTipoDocumento" name="cTipoDocumento" required>
                            <option value="">-- Seleccione --</option>
                            @foreach($tiposDocumento as $id => $descripcion)
                                <option value="{{ $id }}" {{ old('cTipoDocumento') == $id ? 'selected' : '' }}>
                                    {{ $descripcion }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Fecha Entrega -->
                    <div class="mb-3">
                        <label for="dFechaEntrega" class="form-label">Fecha de Entrega</label>
                        <input type="date" class="form-control" id="dFechaEntrega" name="dFechaEntrega" 
                               value="{{ old('dFechaEntrega') }}">
                    </div>

                    <!-- Documento Adjunto -->
                    <div class="mb-3">
                        <label for="eDocumentoAdjunto" class="form-label">Adjuntar Documento</label>
                        <input type="file" class="form-control" id="eDocumentoAdjunto" name="eDocumentoAdjunto">
                    </div>

                    <!-- Botones -->
                    <div class="mt-4">
                        <a href="{{ route('documentos.index') }}" class="btn btn-secondary">
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







