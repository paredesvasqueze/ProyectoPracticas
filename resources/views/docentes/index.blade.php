{{-- resources/views/docentes/index.blade.php --}}
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

    <h2 class="mb-4">Gestión de Docentes</h2>

    <!-- Botón para crear nuevo docente -->
    <a href="{{ route('docentes.create') }}" class="btn btn-success mb-3">
        <i class="bi bi-plus-circle"></i> Nuevo Docente
    </a>

    <!-- Mensaje de éxito -->
    @if (session('success'))
        <div class="alert alert-success mb-3">{{ session('success') }}</div>
    @endif

    <!-- Formulario de búsqueda por nombre o DNI -->
    <form action="{{ route('docentes.index') }}" method="GET" class="mb-3 d-flex">
        <input type="text" name="search" class="form-control me-2" 
               placeholder="Buscar por nombre o DNI" value="{{ request('search') }}">
        <button type="submit" class="btn btn-primary me-2">Buscar</button>
        <a href="{{ route('docentes.index') }}" class="btn btn-secondary">Limpiar</a>
    </form>

    <!-- Tabla de docentes -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-striped table-bordered align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>DNI</th>
                        <th>Correo</th>
                        <th>Programa de Estudios</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($docentes ?? [] as $docente)
                        <tr>
                            <td>{{ $docente->IdDocente }}</td>
                            <td>{{ $docente->persona->cNombre ?? '-' }}</td>
                            <td>{{ $docente->persona->cApellido ?? '-' }}</td>
                            <td>{{ $docente->persona->cDNI ?? '-' }}</td>
                            <td>{{ $docente->persona->cCorreo ?? '-' }}</td>
                            <td>{{ $docente->nProgramaEstudios }}</td>
                            <td class="text-center">
                                <a href="{{ route('docentes.edit', $docente->IdDocente) }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil-square"></i> Editar
                                </a>
                                <!-- Eliminar opcional
                                <form action="{{ route('docentes.destroy', $docente->IdDocente) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('¿Seguro de eliminar este docente?')" class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash"></i> Eliminar
                                    </button>
                                </form>
                                -->
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No hay docentes registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- Bootstrap Icons --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endsection
