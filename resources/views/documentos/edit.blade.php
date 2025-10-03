{{-- resources/views/documentos/edit.blade.php --}}
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

    <h2 class="mb-4">Editar Documento</h2>

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
            <form action="{{ route('documentos.update', $documento->IdDocumento) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Número de Documento -->
                <div class="mb-3">
                    <label for="cNroDocumento" class="form-label">Número de Documento</label>
                    <input type="text" class="form-control" id="cNroDocumento" name="cNroDocumento" 
                           value="{{ old('cNroDocumento', $documento->cNroDocumento) }}" required>
                </div>

                <!-- Fecha Documento -->
                <div class="mb-3">
                    <label for="dFechaDocumento" class="form-label">Fecha del Documento</label>
                    <input type="date" class="form-control" id="dFechaDocumento" name="dFechaDocumento" 
                           value="{{ old('dFechaDocumento', $documento->dFechaDocumento) }}" required>
                </div>

                <!-- Tipo Documento -->
                <div class="mb-3">
                    <label for="cTipoDocumento" class="form-label">Tipo de Documento</label>
                    <select class="form-select" id="cTipoDocumento" name="cTipoDocumento" required>
                        <option value="">-- Seleccione Tipo --</option>
                        @foreach($tiposDocumento as $id => $nombre)
                            <option value="{{ $id }}" 
                                {{ old('cTipoDocumento', $documento->cTipoDocumento) == $id ? 'selected' : '' }}>
                                {{ $nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Fecha Entrega -->
                <div class="mb-3">
                    <label for="dFechaEntrega" class="form-label">Fecha de Entrega</label>
                    <input type="date" class="form-control" id="dFechaEntrega" name="dFechaEntrega" 
                           value="{{ old('dFechaEntrega', $documento->dFechaEntrega) }}">
                </div>

                <!-- Documento Adjunto -->
                <div class="mb-3">
                    <label for="eDocumentoAdjunto" class="form-label">Adjuntar Documento</label>
                    <input type="file" class="form-control" id="eDocumentoAdjunto" name="eDocumentoAdjunto">

                    @if($documento->eDocumentoAdjunto)
                        <p class="mt-2">
                            Documento actual: 
                            <a href="{{ asset('storage/'.$documento->eDocumentoAdjunto) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-download"></i> Descargar
                            </a>
                        </p>
                    @endif
                </div>

                <!-- Botones -->
                <div class="mt-4">
                    <a href="{{ route('documentos.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

{{-- Bootstrap Icons --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endsection
