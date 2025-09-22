{{-- resources/views/cartas/create.blade.php --}}
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
            <li class="nav-item mb-2">
                <a class="nav-link text-white {{ request()->is('usuarios*') ? 'active fw-bold' : '' }}" 
                   href="{{ route('usuarios.index') }}">
                    <i class="bi bi-people-fill me-2"></i> Gestionar Usuarios
                </a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link text-white {{ request()->is('cartas*') ? 'active fw-bold' : '' }}" 
                   href="{{ route('cartas.index') }}">
                    <i class="bi bi-file-earmark-text me-2"></i> Gestionar Trámites
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
        </ul>
    </div>

    <!-- Contenido -->
    <div class="flex-grow-1 p-4" style="margin-left: 250px;">
        <div class="d-flex justify-content-between mb-3">
            <h2>Registrar Trámite - Carta de Presentación</h2>
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

                <form action="{{ route('cartas.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row mb-3">
                        <!-- Estudiante -->
                        <div class="col-md-6">
                            <label class="form-label">Estudiante</label>
                            <select id="estudianteSelect" name="IdEstudiante" class="form-select" required style="width: 100%;">
                                <option value="">Seleccione...</option>
                                @foreach($estudiantes as $est)
                                    <option value="{{ $est->IdEstudiante }}" {{ old('IdEstudiante') == $est->IdEstudiante ? 'selected' : '' }}>
                                        {{ $est->persona->cNombre ?? '' }} {{ $est->persona->cApellido ?? '' }} ({{ $est->persona->cDNI ?? '' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Empresa -->
                        <div class="col-md-6">
                            <label class="form-label">Empresa</label>
                            <select id="empresaSelect" name="IdEmpresa" class="form-select" required style="width: 100%;">
                                <option value="">Seleccione...</option>
                                @foreach($empresas as $emp)
                                    <option value="{{ $emp->IdEmpresa }}" {{ old('IdEmpresa') == $emp->IdEmpresa ? 'selected' : '' }}>
                                        {{ $emp->cNombreEmpresa }} (RUC: {{ $emp->nRUC ?? '' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Nro Expediente</label>
                            <input type="text" name="nNroExpediente" class="form-control" value="{{ old('nNroExpediente') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nro Carta</label>
                            <input type="text" name="nNroCarta" class="form-control" value="{{ old('nNroCarta') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nro Recibo</label>
                            <input type="text" name="nNroResibo" class="form-control" value="{{ old('nNroResibo') }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Fecha Carta</label>
                            <input type="date" name="dFechaCarta" class="form-control" value="{{ old('dFechaCarta') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fecha Recojo</label>
                            <input type="date" name="dFechaRecojo" class="form-control" value="{{ old('dFechaRecojo') }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Observación</label>
                        <textarea name="cObservacion" class="form-control" rows="3">{{ old('cObservacion') }}</textarea>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">¿Presentó Supervisión?</label>
                            <select name="bPresentoSupervision" class="form-select">
                                <option value="0" {{ old('bPresentoSupervision') == "0" ? 'selected' : '' }}>No</option>
                                <option value="1" {{ old('bPresentoSupervision') == "1" ? 'selected' : '' }}>Sí</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Estado</label>
                            <select name="nEstado" class="form-select">
                                <option value="En proceso" {{ old('nEstado') == 'En proceso' ? 'selected' : '' }}>En proceso</option>
                                <option value="Finalizado" {{ old('nEstado') == 'Finalizado' ? 'selected' : '' }}>Finalizado</option>
                                <option value="Observado" {{ old('nEstado') == 'Observado' ? 'selected' : '' }}>Observado</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Documento Adjunto (PDF, JPG, PNG)</label>
                        <input type="file" name="adjunto" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-save"></i> Guardar Trámite
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






