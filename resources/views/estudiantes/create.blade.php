{{-- resources/views/estudiantes/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="p-4">

    <div class="d-flex justify-content-between mb-3">
        <h2>Registrar Estudiante</h2>
        <a href="{{ route('estudiantes.index') }}" class="btn btn-secondary">
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

            <form action="{{ route('estudiantes.store') }}" method="POST">
                @csrf

                <!-- Datos Personales -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="cNombre" class="form-control" value="{{ old('cNombre') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Apellido</label>
                        <input type="text" name="cApellido" class="form-control" value="{{ old('cApellido') }}" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">DNI</label>
                        <input type="text" name="cDNI" class="form-control" 
                               value="{{ old('cDNI') }}" required 
                               pattern="\d{8}" title="El DNI debe tener 8 números">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Correo</label>
                        <input type="email" name="cCorreo" class="form-control" value="{{ old('cCorreo') }}" required>
                    </div>
                </div>

                <!-- Datos de Estudiante -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label">Celular</label>
                        <input type="text" name="nCelular" class="form-control" 
                               value="{{ old('nCelular') }}" required 
                               pattern="\d{9}" title="El celular debe tener 9 números">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Programa de Estudios</label>
                        <select name="nProgramaEstudios" class="form-control" required>
                            <option value="">--Seleccionar--</option>
                            @foreach($programas as $prog)
                                <option value="{{ $prog->nConstValor }}" 
                                    {{ old('nProgramaEstudios') == $prog->nConstValor ? 'selected' : '' }}>
                                    {{ $prog->nConstDescripcion }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Plan de Estudio</label>
                        <select name="nPlanEstudio" class="form-control" required>
                            <option value="">--Seleccionar--</option>
                            @foreach($planes as $plan)
                                <option value="{{ $plan->nConstValor }}" 
                                    {{ old('nPlanEstudio') == $plan->nConstValor ? 'selected' : '' }}>
                                    {{ $plan->nConstDescripcion }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Módulo Formativo</label>
                        <select name="nModuloFormativo" class="form-control" required>
                            <option value="">--Seleccionar--</option>
                            @foreach($modulos as $mod)
                                <option value="{{ $mod->nConstValor }}" 
                                    {{ old('nModuloFormativo') == $mod->nConstValor ? 'selected' : '' }}>
                                    {{ $mod->nConstDescripcion }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Turno</label>
                        <select name="nTurno" class="form-control" required>
                            <option value="">--Seleccionar--</option>
                            @foreach($turnos as $tur)
                                <option value="{{ $tur->nConstValor }}" 
                                    {{ old('nTurno') == $tur->nConstValor ? 'selected' : '' }}>
                                    {{ $tur->nConstDescripcion }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save"></i> Guardar Estudiante
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

{{-- Bootstrap Icons --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endsection
