{{-- resources/views/cartas/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="p-4">

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

    <div class="d-flex justify-content-between mb-3">
        <h2>Editar Trámite - Carta de Presentación</h2>
        <a href="{{ route('cartas.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>

    {{-- Mensajes de error --}}
    @if ($errors->any())
        <div class="alert alert-danger mb-3">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">

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
                            <option value="En coordinación" {{ old('nEstado', $carta->nEstado) == 'En coordinación' ? 'selected' : '' }}>En coordinación</option>
                            <option value="En jefatura académica" {{ old('nEstado', $carta->nEstado) == 'En jefatura académica' ? 'selected' : '' }}>En jefatura académica</option>
                            <option value="En JUA" {{ old('nEstado', $carta->nEstado) == 'En JUA' ? 'selected' : '' }}>En JUA</option>
                            <option value="Observado" {{ old('nEstado', $carta->nEstado) == 'Observado' ? 'selected' : '' }}>Observado</option>
                            <option value="Entregado" {{ old('nEstado', $carta->nEstado) == 'Entregado' ? 'selected' : '' }}>Entregado</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Documento Adjunto (PDF, JPG, PNG)</label>
                    <input type="file" name="adjunto" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    @if($carta->adjunto)
                        <p class="mt-2">
                            <a href="{{ asset('storage/'.$carta->adjunto) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-file-earmark-text"></i> Ver Documento Actual
                            </a>
                            <!--
                            <a href="{{ asset('storage/'.$carta->adjunto) }}" download class="btn btn-outline-success btn-sm">
                                <i class="bi bi-download"></i> Descargar
                            </a>
                            -->
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
