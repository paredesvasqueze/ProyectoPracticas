{{-- resources/views/empresas/create.blade.php --}}
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
                    <i class="bi bi-file-earmark-text me-2"></i> Registro de Trámites
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

        <h2 class="mb-4">Registrar Nueva Empresa</h2>

        <!-- Formulario -->
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('empresas.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="cNombreEmpresa" class="form-label">Nombre de la Empresa</label>
                        <input type="text" class="form-control" id="cNombreEmpresa" name="cNombreEmpresa" required>
                    </div>

                    <div class="mb-3">
                        <label for="nRUC" class="form-label">RUC</label>
                        <input type="text" class="form-control" id="nRUC" name="nRUC" required>
                    </div>

                    <div class="mb-3">
                        <label for="cDireccion" class="form-label">Dirección</label>
                        <input type="text" class="form-control" id="cDireccion" name="cDireccion">
                    </div>

                    <div class="mb-3">
                        <label for="cCorreo" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="cCorreo" name="cCorreo">
                    </div>

                    <div class="mb-3">
                        <label for="nTelefono" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="nTelefono" name="nTelefono">
                    </div>

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
</div>

{{-- Bootstrap Icons --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endsection
