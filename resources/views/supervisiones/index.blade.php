{{-- resources/views/supervisiones/index.blade.php --}}
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

    <h2 class="mb-4">Gestión de Supervisiones</h2>

    <!-- Botón para nueva supervisión -->
    <a href="{{ route('supervisiones.create') }}" class="btn btn-success mb-3">
        <i class="bi bi-plus-circle"></i> Nueva Supervisión
    </a>

    <!-- Mensaje de éxito -->
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

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
                        <th>Estado</th>
                        <th>Oficina</th>
                        <th class="text-center">Detalle</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($supervisiones as $supervision)
                        <tr>
                            <td>{{ $supervision->IdSupervision }}</td>
                            <td>
                                {{ $supervision->docente->persona->cNombre ?? '' }}
                                {{ $supervision->docente->persona->cApellido ?? '' }}
                            </td>
                            <td>{{ $supervision->cartaPresentacion->nNroCarta ?? 'N/A' }}</td>
                            <td>{{ $supervision->nNota ?? '-' }}</td>
                            <td>{{ $supervision->dFechaInicio }}</td>
                            <td>{{ $supervision->dFechaFin }}</td>
                            <td>{{ $supervision->nHoras }}</td>
                            <td>{{ $supervision->estado_nombre ?? '—' }}</td>
                            <td>{{ $supervision->oficina_nombre ?? '—' }}</td>

                            <!-- Botón ver detalle -->
                            <td class="text-center">
                                <button class="btn btn-info btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#detalleModal{{ $supervision->IdSupervision }}">
                                    <i class="bi bi-eye"></i> Ver
                                </button>

                                <!-- Modal Detalle -->
                                <div class="modal fade"
                                     id="detalleModal{{ $supervision->IdSupervision }}"
                                     tabindex="-1"
                                     aria-labelledby="detalleModalLabel{{ $supervision->IdSupervision }}"
                                     aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="detalleModalLabel{{ $supervision->IdSupervision }}">
                                                    Detalles de Supervisión #{{ $supervision->IdSupervision }}
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                            </div>
                                            <div class="modal-body">
                                                <table class="table table-bordered">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Número de Supervisión</th>
                                                            <th>Fecha de Supervisión</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($supervision->detalles as $detalle)
                                                            <tr>
                                                                <td>{{ $detalle->nNroSupervision }}</td>
                                                                <td>{{ $detalle->dFechaSupervision }}</td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="2" class="text-center">No hay detalles registrados</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Acciones -->
                            <td class="text-center">
                                <a href="{{ route('supervisiones.edit', $supervision) }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil-square"></i> Editar
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center">No hay supervisiones registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Bootstrap JS y Icons --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
@endsection

