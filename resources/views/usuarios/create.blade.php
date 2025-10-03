{{-- resources/views/usuarios/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="p-4">
    <!-- Usuario arriba a la derecha -->
    <div class="d-flex justify-content-end mb-3">
        <div class="text-end">
            <small>
                Usuario: {{ Auth::user()->persona->cNombre ?? Auth::user()->cUsuario }} 
                         {{ Auth::user()->persona->cApellido ?? '' }}
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

    <!-- Formulario registrar usuario -->
    <div class="card shadow-sm">
        <div class="card-body">
            <h2 class="mb-4">Registrar Usuario</h2>

            <form action="{{ route('usuarios.store') }}" method="POST">
                @csrf

                <div class="row">
                    <!-- Nombre -->
                    <div class="col-md-6 mb-3">
                        <label for="cNombre" class="form-label">Nombre</label>
                        <input type="text" name="cNombre" id="cNombre" class="form-control" required>
                    </div>

                    <!-- Apellido -->
                    <div class="col-md-6 mb-3">
                        <label for="cApellido" class="form-label">Apellido</label>
                        <input type="text" name="cApellido" id="cApellido" class="form-control" required>
                    </div>

                    <!-- DNI -->
                    <div class="col-md-6 mb-3">
                        <label for="cDNI" class="form-label">DNI</label>
                        <input type="text" name="cDNI" id="cDNI" class="form-control" required>
                    </div>

                    <!-- Correo -->
                    <div class="col-md-6 mb-3">
                        <label for="cCorreo" class="form-label">Correo</label>
                        <input type="email" name="cCorreo" id="cCorreo" class="form-control">
                    </div>

                    <!-- Usuario -->
                    <div class="col-md-6 mb-3">
                        <label for="cUsuario" class="form-label">Usuario</label>
                        <input type="text" name="cUsuario" id="cUsuario" class="form-control" required>
                    </div>

                    <!-- Contraseña -->
                    <div class="col-md-6 mb-3">
                        <label for="cContrasenia" class="form-label">Contraseña</label>
                        <input type="password" name="cContrasenia" id="cContrasenia" class="form-control" required>
                    </div>

                    <!-- Selección de Roles -->
                    <div class="col-md-6 mb-3">
                        <label for="roles" class="form-label">Roles</label>
                        <select name="roles[]" id="roles" class="form-select" required>
                            <option value="" disabled selected>Seleccionar Rol</option>
                            @foreach($roles->unique('cNombreRol') as $rol)
                                <option value="{{ $rol->IdRol }}">{{ $rol->cNombreRol }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Guardar
                    </button>
                    <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Bootstrap Icons --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endsection






