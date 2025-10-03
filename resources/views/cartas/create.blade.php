{{-- resources/views/cartas/create.blade.php --}}
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
        <h2>Registrar Trámite - Carta de Presentación</h2>
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

            <form action="{{ route('cartas.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row mb-3">
                    <!-- Estudiante -->
                    <div class="col-md-6">
                        <label class="form-label">Estudiante</label>
                        <select id="estudianteSelect" name="IdEstudiante" class="form-select" required>
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
                        <select id="empresaSelect" name="IdEmpresa" class="form-select" required>
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
