{{-- resources/views/cartas/edit.blade.php --}}
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
                <a class="nav-link text-white" href="{{ route('usuarios.index') }}">
                    <i class="bi bi-people-fill me-2"></i> Gestionar Usuarios
                </a>
            </li>

            <!-- Gestión de Trámites -->
            <li class="nav-item mb-2">
                <a class="nav-link text-white {{ request()->is('cartas*') ? 'active fw-bold' : '' }}" 
                   href="{{ route('cartas.index') }}">
                    <i class="bi bi-file-earmark-text me-2"></i> Registro de Trámites
                </a>
            </li>

            <!-- Gestión de Empresas -->
            <li class="nav-item mb-2">
                <a class="nav-link text-white" href="{{ route('empresas.index') }}">
                    <i class="bi bi-building me-2"></i> Gestionar Empresas
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

        <!-- Título -->
        <h2 class="mb-4">Editar Trámite (Carta de Presentación)</h2>

        <!-- Formulario -->
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('cartas.update', $carta->IdCartaPresentacion) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Estudiante --}}
                    <div class="mb-3">
                        <label for="IdEstudiante" class="form-label">Estudiante</label>
                        <select name="IdEstudiante" id="IdEstudiante" class="form-select" required>
                            <option value="">Seleccione un estudiante</option>
                            @foreach($estudiantes as $estudiante)
                                <option value="{{ $estudiante->IdEstudiante }}" {{ $carta->IdEstudiante == $estudiante->IdEstudiante ? 'selected' : '' }}>
                                    {{ $estudiante->cNombre }} {{ $estudiante->cApellido }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Empresa --}}
                    <div class="mb-3">
                        <label for="IdEmpresa" class="form-label">Empresa</label>
                        <select name="IdEmpresa" id="IdEmpresa" class="form-select" required>
                            <option value="">Seleccione una empresa</option>
                            @foreach($empresas as $empresa)
                                <option value="{{ $empresa->IdEmpresa }}" {{ $carta->IdEmpresa == $empresa->IdEmpresa ? 'selected' : '' }}>
                                    {{ $empresa->cRazonSocial }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Número de expediente --}}
                    <div class="mb-3">
                        <label for="nNroExpediente" class="form-label">N° Expediente</label>
                        <input type="text" name="nNroExpediente" id="nNroExpediente" class="form-control" value="{{ $carta->nNroExpediente }}">
                    </div>

                    {{-- Número de carta --}}
                    <div class="mb-3">
                        <label for="nNroCarta" class="form-label">N° Carta</label>
                        <input type="text" name="nNroCarta" id="nNroCarta" class="form-control" value="{{ $carta->nNroCarta }}">
                    </div>

                    {{-- Fecha carta --}}
                    <div class="mb-3">
                        <label for="dFechaCarta" class="form-label">Fecha Carta</label>
                        <input type="date" name="dFechaCarta" id="dFechaCarta" class="form-control" value="{{ $carta->dFechaCarta }}">
                    </div>

                    {{-- Fecha recojo --}}
                    <div class="mb-3">
                        <label for="dFechaRecojo" class="form-label">Fecha Recojo</label>
                        <input type="date" name="dFechaRecojo" id="dFechaRecojo" class="form-control" value="{{ $carta->dFechaRecojo }}">
                    </div>

                    {{-- Número de recibo --}}
                    <div class="mb-3">
                        <label for="nNroResibo" class="form-label">N° Recibo</label>
                        <input type="text" name="nNroResibo" id="nNroResibo" class="form-control" value="{{ $carta->nNroResibo }}">
                    </div>

                    {{-- Observación --}}
                    <div class="mb-3">
                        <label for="cObservacion" class="form-label">Observación</label>
                        <textarea name="cObservacion" id="cObservacion" class="form-control">{{ $carta->cObservacion }}</textarea>
                    </div>

                    {{-- Supervisión --}}
                    <div class="mb-3">
                        <label for="bPresentoSupervision" class="form-label">¿Presentó Supervisión?</label>
                        <select name="bPresentoSupervision" id="bPresentoSupervision" class="form-select">
                            <option value="0" {{ $carta->bPresentoSupervision == 0 ? 'selected' : '' }}>No</option>
                            <option value="1" {{ $carta->bPresentoSupervision == 1 ? 'selected' : '' }}>Sí</option>
                        </select>
                    </div>

                    {{-- Estado --}}
                    <div class="mb-3">
                        <label for="nEstado" class="form-label">Estado</label>
                        <input type="text" name="nEstado" id="nEstado" class="form-control" value="{{ $carta->nEstado }}">
                    </div>

                    {{-- Fecha de registro --}}
                    <div class="mb-3">
                        <label for="dFechaRegistro" class="form-label">Fecha Registro</label>
                        <input type="date" name="dFechaRegistro" id="dFechaRegistro" class="form-control" value="{{ $carta->dFechaRegistro }}">
                    </div>

                    {{-- Adjunto --}}
                    <div class="mb-3">
                        <label for="adjunto" class="form-label">Documento Adjunto</label>
                        <input type="file" name="adjunto" id="adjunto" class="form-control">
                        @if($carta->adjunto)
                            <p class="mt-2">Archivo actual: 
                                <a href="{{ asset('storage/'.$carta->adjunto) }}" target="_blank">Ver documento</a>
                            </p>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary">Actualizar</button>
                    <a href="{{ route('cartas.index') }}" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>

    </div>
</div>

{{-- Bootstrap Icons --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endsection

