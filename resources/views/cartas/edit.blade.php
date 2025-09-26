{{-- resources/views/cartas/edit.blade.php --}}
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
        </ul>

    </div>

    <!-- Contenido -->
    <div class="flex-grow-1 p-4" style="margin-left: 250px;">
        <div class="d-flex justify-content-between mb-3">
            <h2>Editar Trámite - Carta de Presentación</h2>
            <a href="{{ route('cartas.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">

                {{-- Mensajes de error --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('cartas.update', $carta->IdCartaPresentacion) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row mb-3">
                        <!-- Estudiante (No editable) -->
                        <div class="col-md-6">
                            <label class="form-label">Estudiante</label>
                            <select class="form-select" disabled>
                                <option>
                                    {{ $carta->estudiante->persona->cNombre ?? '' }}
                                    {{ $carta->estudiante->persona->cApellido ?? '' }}
                                    ({{ $carta->estudiante->persona->cDNI ?? '' }})
                                </option>
                            </select>
                            <input type="hidden" name="IdEstudiante" value="{{ $carta->IdEstudiante }}">
                        </div>

                        <!-- Empresa (No editable) -->
                        <div class="col-md-6">
                            <label class="form-label">Empresa</label>
                            <select class="form-select" disabled>
                                <option>
                                    {{ $carta->empresa->cNombreEmpresa ?? '' }}
                                    (RUC: {{ $carta->empresa->nRUC ?? '' }})
                                </option>
                            </select>
                            <input type="hidden" name="IdEmpresa" value="{{ $carta->IdEmpresa }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Nro Expediente</label>
                            <input type="text" name="nNroExpediente" class="form-control" 
                                   value="{{ old('nNroExpediente', $carta->nNroExpediente) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nro Carta</label>
                            <input type="text" name="nNroCarta" class="form-control" 
                                   value="{{ old('nNroCarta', $carta->nNroCarta) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nro Recibo</label>
                            <input type="text" name="nNroResibo" class="form-control" 
                                   value="{{ old('nNroResibo', $carta->nNroResibo) }}" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Fecha Carta</label>
                            <input type="date" name="dFechaCarta" class="form-control" 
                                   value="{{ old('dFechaCarta', $carta->dFechaCarta ? $carta->dFechaCarta->format('Y-m-d') : '') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fecha Recojo</label>
                            <input type="date" name="dFechaRecojo" class="form-control" 
                                   value="{{ old('dFechaRecojo', $carta->dFechaRecojo ? $carta->dFechaRecojo->format('Y-m-d') : '') }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Observación</label>
                        <textarea name="cObservacion" class="form-control" rows="3">{{ old('cObservacion', $carta->cObservacion) }}</textarea>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">¿Presentó Supervisión?</label>
                            <select name="bPresentoSupervision" class="form-select">
                                <option value="0" {{ old('bPresentoSupervision', $carta->bPresentoSupervision) == 0 ? 'selected' : '' }}>No</option>
                                <option value="1" {{ old('bPresentoSupervision', $carta->bPresentoSupervision) == 1 ? 'selected' : '' }}>Sí</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Estado</label>
                            <select name="nEstado" class="form-select">
                                <option value="En proceso" {{ old('nEstado', $carta->nEstado) == 'En proceso' ? 'selected' : '' }}>En proceso</option>
                                <option value="Finalizado" {{ old('nEstado', $carta->nEstado) == 'Finalizado' ? 'selected' : '' }}>Finalizado</option>
                                <option value="Observado" {{ old('nEstado', $carta->nEstado) == 'Observado' ? 'selected' : '' }}>Observado</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Documento Adjunto (PDF, JPG, PNG)</label>
                        <input type="file" name="adjunto" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                        @if($carta->adjunto)
                            <p class="mt-2">
                                Archivo actual: 
                                <a href="{{ asset('storage/'.$carta->adjunto) }}" target="_blank">Ver documento</a>
                            </p>
                        @endif
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-pencil-square"></i> Actualizar Trámite
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

{{-- Bootstrap Icons --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

{{-- Select2 --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    $('#estudianteSelect').select2({
        placeholder: "Buscar estudiante por nombre o DNI",
        allowClear: true
    });

    function matchCustom(params, data) {
        if ($.trim(params.term) === '') return data;
        if (data.text.toLowerCase().indexOf(params.term.toLowerCase()) > -1) return data;
        return null;
    }

    $('#empresaSelect').select2({
        placeholder: "Buscar empresa por nombre o RUC",
        allowClear: true,
        matcher: matchCustom
    });
});
</script>
@endsection














