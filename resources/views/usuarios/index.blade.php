{{-- resources/views/usuarios/index.blade.php --}}
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

    <!-- Título y botón -->
    <h2 class="mb-4">Gestión de Usuarios</h2>
    <a href="{{ route('usuarios.create') }}" class="btn btn-success mb-3">
        <i class="bi bi-person-plus"></i> Nuevo Usuario
    </a>

    <!-- Buscador por DNI -->
    <form action="{{ route('usuarios.index') }}" method="GET" class="mb-3 d-flex">
        <input type="text" name="dni" class="form-control me-2" 
               placeholder="Buscar por DNI" value="{{ request('dni') }}">
        <button type="submit" class="btn btn-primary me-2">Buscar</button>
        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Limpiar</a>
    </form>

    <!-- Tabla de usuarios -->
    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>DNI</th>
                        <th>Correo</th>
                        <th>Rol</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($usuarios as $usuario)
                        <tr>
                            <td>{{ $usuario->IdUsuario }}</td>
                            <td>{{ $usuario->cUsuario }}</td>
                            <td>{{ $usuario->persona->cNombre ?? '' }}</td>
                            <td>{{ $usuario->persona->cApellido ?? '' }}</td>
                            <td>{{ $usuario->persona->cDNI ?? '' }}</td>
                            <td>{{ $usuario->persona->cCorreo ?? '' }}</td>
                            <td>
                                @if($usuario->roles->count())
                                    {{ $usuario->roles->pluck('cNombreRol')->join(', ') }}
                                @else
                                    Sin rol
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('usuarios.edit', $usuario) }}" 
                                   class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil-square"></i> Editar
                                </a>
                                <form action="{{ route('usuarios.destroy', $usuario) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            onclick="return confirm('¿Seguro de eliminar este usuario?')" 
                                            class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash"></i> Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No hay usuarios registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
