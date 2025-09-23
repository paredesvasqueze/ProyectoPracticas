{{-- resources/views/empresas/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="d-flex" style="min-height: 100vh;">

    <!-- Sidebar fijo -->
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
                    <i class="bi bi-file-earmark-text me-2"></i> Registro de Trámites
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

    <!-- Contenido principal -->
    <div class="flex-grow-1 p-4" style="margin-left: 250px;">

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

        <h2 class="mb-4">Editar Empresa</h2>

        <!-- Mensajes de error -->
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
                <form action="{{ route('empresas.update', $empresa->IdEmpresa) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="nTipoEmpresa" class="form-label">Tipo de Empresa</label>
                        <input type="text" class="form-control" id="nTipoEmpresa" name="nTipoEmpresa" 
                               value="{{ old('nTipoEmpresa', $empresa->nTipoEmpresa) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="cNombreEmpresa" class="form-label">Nombre de la Empresa</label>
                        <input type="text" class="form-control" id="cNombreEmpresa" name="cNombreEmpresa" 
                               value="{{ old('cNombreEmpresa', $empresa->cNombreEmpresa) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="nRepresentanteLegal" class="form-label">Representante Legal</label>
                        <input type="text" class="form-control" id="nRepresentanteLegal" name="nRepresentanteLegal" 
                               value="{{ old('nRepresentanteLegal', $empresa->nRepresentanteLegal) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="nProfesion" class="form-label">Profesión</label>
                        <input type="text" class="form-control" id="nProfesion" name="nProfesion" 
                               value="{{ old('nProfesion', $empresa->nProfesion) }}">
                    </div>

                    <div class="mb-3">
                        <label for="nCargo" class="form-label">Cargo</label>
                        <input type="text" class="form-control" id="nCargo" name="nCargo" 
                               value="{{ old('nCargo', $empresa->nCargo) }}">
                    </div>

                    <div class="mb-3">
                        <label for="nRUC" class="form-label">RUC</label>
                        <input type="text" class="form-control" id="nRUC" name="nRUC" 
                               value="{{ old('nRUC', $empresa->nRUC) }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="cDireccion" class="form-label">Dirección</label>
                        <input type="text" class="form-control" id="cDireccion" name="cDireccion" 
                               value="{{ old('cDireccion', $empresa->cDireccion) }}">
                    </div>

                    <div class="mb-3">
                        <label for="cCorreo" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="cCorreo" name="cCorreo" 
                               value="{{ old('cCorreo', $empresa->cCorreo) }}">
                    </div>

                    <div class="mb-3">
                        <label for="nTelefono" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="nTelefono" name="nTelefono" 
                               value="{{ old('nTelefono', $empresa->nTelefono) }}">
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('empresas.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Volver
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Actualizar
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





