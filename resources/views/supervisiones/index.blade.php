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

            <!-- Módulo de trámites -->
            <li class="nav-item mb-2">
                <a class="nav-link text-white {{ request()->is('cartas*') ? 'active fw-bold' : '' }}" 
                   href="{{ route('cartas.index') }}">
                    <i class="bi bi-file-earmark-text-fill me-2"></i> Gestionar Trámites
                </a>
            </li>

            <!-- Módulo de empresas -->
            <li class="nav-item mb-2">
                <a class="nav-link text-white {{ request()->is('empresas*') ? 'active fw-bold' : '' }}" 
                   href="{{ route('empresas.index') }}">
                    <i class="bi bi-building me-2"></i> Gestionar Empresas
                </a>
            </li>

            <!-- Módulo de estudiantes -->
            <li class="nav-item mb-2">
                <a class="nav-link text-white {{ request()->is('estudiantes*') ? 'active fw-bold' : '' }}" 
                   href="{{ route('estudiantes.index') }}">
                    <i class="bi bi-mortarboard-fill me-2"></i> Gestionar Estudiantes
                </a>
            </li>

            <!-- Módulo de docentes -->
            <li class="nav-item mb-2">
                <a class="nav-link text-white {{ request()->is('docentes*') ? 'active fw-bold' : '' }}" 
                   href="{{ route('docentes.index') }}">
                    <i class="bi bi-person-badge-fill me-2"></i> Gestionar Docentes
                </a>
            </li>

            <!-- Módulo de supervisiones -->
            <li class="nav-item mb-2">
                <a class="nav-link text-white {{ request()->is('supervisiones*') ? 'active fw-bold' : '' }}" 
                   href="{{ route('supervisiones.index') }}">
                    <i class="bi bi-journal-check me-2"></i> Gestionar Supervisiones
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

        <!-- Título y botón -->
        <h2 class="mb-4">Gestión de Supervisiones</h2>
        <a href="{{ route('supervisiones.create') }}" class="btn btn-success mb-3">
            <i class="bi bi-plus-circle"></i> Nueva Supervisión
        </a>

        <!-- Tabla de supervisiones -->
        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-striped table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Docente</th>
                            <th>Carta</th>
                            <th>Nota</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Fin</th>
                            <th>Horas</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($supervisiones as $supervision)
                            <tr>
                                <td>{{ $supervision->IdSupervision }}</td>
                                <td>
                                    @if($supervision->docente && $supervision->docente->persona)
                                        {{ $supervision->docente->persona->cNombre }} {{ $supervision->docente->persona->cApellido }}
                                    @else
                                        Sin asignar
                                    @endif
                                </td>
                                <td>{{ $supervision->cartaPresentacion->nNroCarta ?? 'N/A' }}</td>
                                <td>{{ $supervision->nNota ?? '-' }}</td>
                                <td>{{ $supervision->dFechaInicio }}</td>
                                <td>{{ $supervision->dFechaFin }}</td>
                                <td>{{ $supervision->nHoras }}</td>
                                <td class="text-center">
                                    <a href="{{ route('supervisiones.edit', $supervision) }}" 
                                       class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil-square"></i> Editar
                                    </a>
                                    <!--
                                    <form action="{{ route('supervisiones.destroy', $supervision) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                onclick="return confirm('¿Seguro de eliminar esta supervisión?')" 
                                                class="btn btn-danger btn-sm">
                                            <i class="bi bi-trash"></i> Eliminar
                                        </button>
                                    </form>
                                    -->
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No hay supervisiones registradas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Bootstrap Icons --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endsection

