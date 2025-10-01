{{-- resources/views/detalle_supervisiones/index.blade.php --}}
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
                    <i class="bi bi-file-earmark-text-fill me-2"></i> Gestionar Trámites
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
            <li class="nav-item mb-2">
                <a class="nav-link text-white {{ request()->is('docentes*') ? 'active fw-bold' : '' }}"
                   href="{{ route('docentes.index') }}">
                    <i class="bi bi-person-badge-fill me-2"></i> Gestionar Docentes
                </a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link text-white {{ request()->is('supervisiones*') ? 'active fw-bold' : '' }}"
                   href="{{ route('supervisiones.index') }}">
                    <i class="bi bi-journal-check me-2"></i> Gestionar Supervisiones
                </a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link text-white {{ request()->is('detalle_supervisiones*') ? 'active fw-bold' : '' }}"
                   href="{{ route('detalle_supervisiones.index') }}">
                    <i class="bi bi-journal-text me-2"></i> Supervisión Detalle
                </a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link text-white {{ request()->is('documentos*') ? 'active fw-bold' : '' }}" 
                   href="{{ route('documentos.index') }}">
                    <i class="bi bi-file-earmark-pdf-fill me-2"></i> Gestionar Documentos
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

        <!-- Encabezado -->
        <h2 class="mb-4">Supervisión Detalle</h2>

        <!-- Botón para crear nuevo -->
        <div class="mb-3">
            <a href="{{ route('detalle_supervisiones.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Nuevo Detalle
            </a>
        </div>

        <!-- Buscador por nombre y fecha -->
        <form action="{{ route('detalle_supervisiones.index') }}" method="GET" class="mb-3 row g-2">

            <!-- Buscar por nombre -->
            <div class="col-md-5">
                <input type="text" name="search" class="form-control"
                       placeholder="Buscar por nombre del docente"
                       value="{{ request('search') }}">
            </div>

            <!-- Buscar por fecha -->
            <div class="col-md-4">
                <input type="date" name="fecha" class="form-control"
                       value="{{ request('fecha') }}">
            </div>

            <!-- Botones -->
            <div class="col-md-3 d-flex">
                <button type="submit" class="btn btn-primary me-2 w-100">
                    <i class="bi bi-search"></i> Buscar
                </button>
                <a href="{{ route('detalle_supervisiones.index') }}" class="btn btn-secondary w-100">
                    <i class="bi bi-x-circle"></i> Limpiar
                </a>
            </div>
        </form>

        <!-- Tabla de registros -->
        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-striped table-bordered align-middle mt-3">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Docente</th>
                            <th>N° Supervisión</th>
                            <th>Fecha Supervisión</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($detalles as $detalle)
                            <tr>
                                <td>{{ $detalle->IdSupervisionDetalle }}</td>
                                <td>
                                    {{ optional($detalle->supervision->docente->persona)->cNombre ?? '---' }}
                                    {{ optional($detalle->supervision->docente->persona)->cApellido ?? '' }}
                                </td>
                                <td>{{ $detalle->nNroSupervision }}</td>
                                <td>{{ $detalle->dFechaSupervision }}</td>
                                <td class="text-center">
                                    <a href="{{ route('detalle_supervisiones.edit', $detalle->IdSupervisionDetalle) }}" 
                                       class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil-square"></i> Editar
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No hay detalles de supervisiones registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Paginación -->
                @if(method_exists($detalles, 'links'))
                    <div class="d-flex justify-content-center">
                        {{ $detalles->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Bootstrap Icons --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endsection





