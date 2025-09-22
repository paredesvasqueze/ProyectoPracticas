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
                    <i class="bi bi-file-earmark-text me-2"></i> Gestionar Trámites
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

        <h2 class="mb-4">Gestión de Trámites - Cartas de Presentación</h2>

        <!-- Botón para crear nuevo trámite -->
        <a href="{{ route('cartas.create') }}" class="btn btn-success mb-3">
            <i class="bi bi-plus-circle"></i> Nuevo Trámite
        </a>

        <!-- Mensaje de éxito -->
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Formulario de búsqueda por DNI -->
        <form action="{{ route('cartas.index') }}" method="GET" class="mb-3 d-flex">
            <input type="text" name="dni" class="form-control me-2" placeholder="Buscar estudiante por DNI" value="{{ request('dni') }}">
            <button type="submit" class="btn btn-primary me-2">Buscar</button>
            <a href="{{ route('cartas.index') }}" class="btn btn-secondary">Limpiar</a>
        </form>

        <!-- Tabla de cartas -->
        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-striped table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Estudiante</th>
                            <th>N° Expediente</th>
                            <th>N° Carta</th>
                            <th>Fecha Carta</th>
                            <th>Fecha Recojo</th>
                            <th>N° Recibo</th>
                            <th>Observación</th>
                            <th>Supervisión</th>
                            <th>Empresa</th>
                            <th>Estado</th>
                            <th>Fecha Registro</th>
                            <th>Adjunto</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($cartas as $carta)
                            <tr>
                                <td>{{ $carta->IdCartaPresentacion }}</td>
                                <td>{{ $carta->estudiante->persona->cNombre ?? '' }} {{ $carta->estudiante->persona->cApellido ?? '' }}</td>
                                <td>{{ $carta->nNroExpediente }}</td>
                                <td>{{ $carta->nNroCarta }}</td>
                                <td>{{ \Carbon\Carbon::parse($carta->dFechaCarta)->format('d/m/Y') }}</td>
                                <td>{{ $carta->dFechaRecojo ? \Carbon\Carbon::parse($carta->dFechaRecojo)->format('d/m/Y') : '-' }}</td>
                                <td>{{ $carta->nNroResibo ?? '-' }}</td>
                                <td>{{ $carta->cObservacion ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-{{ $carta->bPresentoSupervision ? 'success' : 'danger' }}">
                                        {{ $carta->bPresentoSupervision ? 'Sí' : 'No' }}
                                    </span>
                                </td>
                                <td>{{ $carta->empresa->cNombreEmpresa ?? '' }}</td>
                                <td>
                                    <span class="badge bg-{{ 
                                        $carta->nEstado == 'En proceso' ? 'warning' : 
                                        ($carta->nEstado == 'Finalizado' ? 'success' : 'danger') 
                                    }}">
                                        {{ $carta->nEstado }}
                                    </span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($carta->dFechaRegistro)->format('d/m/Y') }}</td>
                                <td>
                                    @if($carta->adjunto)
                                        <a href="{{ asset('storage/'.$carta->adjunto) }}" target="_blank" class="btn btn-sm btn-info">
                                           <i class="bi bi-eye"></i> Ver
                                        </a>
                                    @else
                                        <span class="text-muted">No adjunto</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('cartas.edit', $carta->IdCartaPresentacion) }}" class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil-square"></i> Editar
                                    </a>
                                    <form action="{{ route('cartas.destroy', $carta->IdCartaPresentacion) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('¿Seguro de eliminar este trámite?')" class="btn btn-danger btn-sm">
                                            <i class="bi bi-trash"></i> Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="14" class="text-center">No hay trámites registrados.</td>
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







