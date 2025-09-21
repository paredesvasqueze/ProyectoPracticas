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

            <!-- Registro de Trámites -->
            <li class="nav-item mb-2">
                <a class="nav-link text-white {{ request()->is('cartas*') ? 'active fw-bold' : '' }}" 
                   href="{{ route('cartas.index') }}">
                    <i class="bi bi-file-earmark-text me-2"></i> Gestionar Trámites
                </a>
            </li>

            <!-- Gestión de Empresas -->
            <li class="nav-item mb-2">
                <a class="nav-link text-white {{ request()->is('empresas*') ? 'active fw-bold' : '' }}" 
                   href="{{ route('empresas.index') }}">
                    <i class="bi bi-building me-2"></i> Gestionar Empresas
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
                <form action="{{ route('cartas.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Estudiante</label>
                            <select name="IdEstudiante" class="form-select" required>
                                <option value="">Seleccione...</option>
                                @foreach($estudiantes as $est)
                                    <option value="{{ $est->IdEstudiante }}">
                                        {{ $est->cNombre }} {{ $est->cApellido }} (DNI: {{ $est->cDNI }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Empresa</label>
                            <select name="IdEmpresa" class="form-select" required>
                                <option value="">Seleccione...</option>
                                @foreach($empresas as $emp)
                                    <option value="{{ $emp->IdEmpresa }}">
                                        {{ $emp->cRazonSocial }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Nro Expediente</label>
                            <input type="text" name="nNroExpediente" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nro Carta</label>
                            <input type="text" name="nNroCarta" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nro Recibo</label>
                            <input type="text" name="nNroResibo" class="form-control">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Fecha Carta</label>
                            <input type="date" name="dFechaCarta" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fecha Recojo</label>
                            <input type="date" name="dFechaRecojo" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Observación</label>
                        <textarea name="cObservacion" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">¿Presentó Supervisión?</label>
                            <select name="bPresentoSupervision" class="form-select">
                                <option value="0">No</option>
                                <option value="1">Sí</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Estado</label>
                            <select name="nEstado" class="form-select">
                                <option value="En proceso">En proceso</option>
                                <option value="Finalizado">Finalizado</option>
                                <option value="Observado">Observado</option>
                            </select>
                        </div>
                    </div>

                    <!-- Adjuntos -->
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
@endsection
