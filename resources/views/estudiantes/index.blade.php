{{-- resources/views/estudiantes/index.blade.php --}}
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

    <h2 class="mb-4">Gestión de Estudiantes</h2>

    <!-- Botón para crear nuevo estudiante -->
    <a href="{{ route('estudiantes.create') }}" class="btn btn-success mb-3">
        <i class="bi bi-plus-circle"></i> Nuevo Estudiante
    </a>

    <!-- Mensaje de éxito -->
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Formulario de búsqueda por DNI -->
    <form action="{{ route('estudiantes.index') }}" method="GET" class="mb-3 d-flex">
        <input type="text" name="dni" class="form-control me-2" placeholder="Buscar por DNI" value="{{ request('dni') }}">
        <button type="submit" class="btn btn-primary me-2">Buscar</button>
        <a href="{{ route('estudiantes.index') }}" class="btn btn-secondary">Limpiar</a>
    </form>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>DNI</th>
                        <th>Correo</th>
                        <th>Celular</th>
                        <th>Programa de Estudios</th>
                        <th>Plan de Estudio</th>
                        <th>Módulo Formativo</th>
                        <th>Turno</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($estudiantes as $estudiante)
                        <tr>
                            <td>{{ $estudiante->IdEstudiante }}</td>
                            <td>{{ $estudiante->persona->cNombre ?? '' }}</td>
                            <td>{{ $estudiante->persona->cApellido ?? '' }}</td>
                            <td>{{ $estudiante->persona->cDNI ?? '' }}</td>
                            <td>{{ $estudiante->persona->cCorreo ?? '' }}</td>
                            <td>{{ $estudiante->nCelular }}</td>
                            <td>{{ $estudiante->programa->nConstDescripcion ?? '' }}</td>
                            <td>{{ $estudiante->plan->nConstDescripcion ?? '' }}</td>
                            <td>{{ $estudiante->modulo->nConstDescripcion ?? '' }}</td>
                            <td>{{ $estudiante->turno->nConstDescripcion ?? '' }}</td>
                            <td class="text-center">
                                <a href="{{ route('estudiantes.edit', $estudiante->IdEstudiante) }}" 
                                   class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil-square"></i> Editar
                                </a>
                                <!--
                                <form action="{{ route('estudiantes.destroy', $estudiante->IdEstudiante) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash"></i> Eliminar
                                    </button>
                                </form>
                                -->
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center">No hay estudiantes registrados.</td>
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
