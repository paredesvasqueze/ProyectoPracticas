{{-- resources/views/empresas/create.blade.php --}}
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

    <h2 class="mb-4">Registrar Nueva Empresa</h2>

    <!-- Formulario -->
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('empresas.store') }}" method="POST">
                @csrf

                <!-- Tipo Empresa -->
                <div class="mb-3">
                    <label for="nTipoEmpresa" class="form-label">Tipo de Empresa</label>
                    <select class="form-select" id="nTipoEmpresa" name="nTipoEmpresa" required>
                        <option value="">--Seleccionar--</option>
                        @foreach($tiposEmpresa as $tipo)
                            <option value="{{ $tipo->nConstValor }}">
                                {{ $tipo->nConstDescripcion }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Nombre Empresa -->
                <div class="mb-3">
                    <label for="cNombreEmpresa" class="form-label">Razón Social</label>
                    <input type="text" class="form-control" id="cNombreEmpresa" name="cNombreEmpresa" required>
                </div>

                <!-- Representante -->
                <div class="mb-3">
                    <label for="nRepresentanteLegal" class="form-label">Representante Legal</label>
                    <input type="text" class="form-control" id="nRepresentanteLegal" name="nRepresentanteLegal">
                </div>

                <!-- Profesión -->
                <div class="mb-3">
                    <label for="nProfesion" class="form-label">Profesión del Representante</label>
                    <select class="form-select" id="nProfesion" name="nProfesion">
                        <option value="">--Seleccionar--</option>
                        @foreach($profesiones as $profesion)
                            <option value="{{ $profesion->nConstValor }}">
                                {{ $profesion->nConstDescripcion }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Cargo -->
                <div class="mb-3">
                    <label for="nCargo" class="form-label">Cargo del Representante</label>
                    <select class="form-select" id="nCargo" name="nCargo">
                        <option value="">--Seleccionar--</option>
                        @foreach($cargos as $cargo)
                            <option value="{{ $cargo->nConstValor }}">
                                {{ $cargo->nConstDescripcion }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- RUC -->
                <div class="mb-3">
                    <label for="nRUC" class="form-label">RUC</label>
                    <input type="text" class="form-control" id="nRUC" name="nRUC" required>
                </div>

                <!-- Dirección -->
                <div class="mb-3">
                    <label for="cDireccion" class="form-label">Dirección</label>
                    <input type="text" class="form-control" id="cDireccion" name="cDireccion">
                </div>

                <!-- Correo -->
                <div class="mb-3">
                    <label for="cCorreo" class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control" id="cCorreo" name="cCorreo">
                </div>

                <!-- Teléfono -->
                <div class="mb-3">
                    <label for="nTelefono" class="form-label">Teléfono</label>
                    <input type="text" class="form-control" id="nTelefono" name="nTelefono">
                </div>

                <!-- Botones -->
                <div class="mt-4">
                    <a href="{{ route('empresas.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Registrar
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

{{-- Bootstrap Icons --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endsection
