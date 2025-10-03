{{-- resources/views/documento_supervisiones/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="p-4">

    <div class="d-flex justify-content-between mb-3">
        <h2>Registrar Documento de Supervisión</h2>
        <a href="{{ route('documento_supervisiones.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            {{-- Mensajes de error --}}
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

            <form action="{{ route('documento_supervisiones.store') }}" method="POST">
                @csrf

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Fecha Registro</label>
                        <input type="date" name="dFechaRegistro" class="form-control" value="{{ old('dFechaRegistro') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Fecha Supervisión</label>
                        <input type="date" name="dFechaSupervision" class="form-control" value="{{ old('dFechaSupervision') }}" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Número de Supervisión</label>
                        <input type="text" name="nNroSupervision" class="form-control" value="{{ old('nNroSupervision') }}" required maxlength="20">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Documento</label>
                        <select name="IdDocumento" class="form-select" required>
                            <option value="">--Seleccionar Documento--</option>
                            @foreach($documentos as $doc)
                                <option value="{{ $doc->IdDocumento }}" {{ old('IdDocumento') == $doc->IdDocumento ? 'selected' : '' }}>
                                    {{ $doc->cNroDocumento }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Supervisión</label>
                        <select name="IdSupervision" class="form-select" required>
                            <option value="">--Seleccionar Supervisión--</option>
                            @foreach($supervisiones as $sup)
                                <option value="{{ $sup->IdSupervision }}" {{ old('IdSupervision') == $sup->IdSupervision ? 'selected' : '' }}>
                                    Supervisión #{{ $sup->IdSupervision }} - Docente #{{ $sup->IdDocente }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save"></i> Guardar Documento Supervisión
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

{{-- Bootstrap Icons --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endsection

