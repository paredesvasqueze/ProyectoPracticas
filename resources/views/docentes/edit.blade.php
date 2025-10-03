{{-- resources/views/docentes/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="p-4">

    <div class="d-flex justify-content-between mb-3">
        <h2>Editar Docente</h2>
        <a href="{{ route('docentes.index') }}" class="btn btn-secondary">
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

            <form action="{{ route('docentes.update', $docente->IdDocente) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Datos Personales -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="cNombre" class="form-control" 
                               value="{{ old('cNombre', $docente->persona->cNombre) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Apellido</label>
                        <input type="text" name="cApellido" class="form-control" 
                               value="{{ old('cApellido', $docente->persona->cApellido) }}" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">DNI</label>
                        <input type="text" name="cDNI" class="form-control" 
                               value="{{ old('cDNI', $docente->persona->cDNI) }}" required 
                               pattern="\d{8}" title="El DNI debe tener 8 números">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Correo</label>
                        <input type="email" name="cCorreo" class="form-control" 
                               value="{{ old('cCorreo', $docente->persona->cCorreo) }}">
                    </div>
                </div>

                <!-- Datos Docente -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Programa de Estudios</label>
                        <select name="nProgramaEstudios" class="form-select" required>
                            <option value="">--Seleccionar--</option>
                            @foreach($programas as $programa)
                                <option value="{{ $programa->nConstDescripcion }}" 
                                    {{ old('nProgramaEstudios', $docente->nProgramaEstudios) == $programa->nConstDescripcion ? 'selected' : '' }}>
                                    {{ $programa->nConstDescripcion }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Actualizar Docente
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

{{-- Bootstrap Icons --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endsection

