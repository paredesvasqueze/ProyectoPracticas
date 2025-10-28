{{-- resources/views/documentos/index.blade.php --}}
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

    <h2 class="mb-4">Gestión de Documentos</h2>
    <a href="{{ route('documentos.create') }}" class="btn btn-success mb-3">
        <i class="bi bi-plus-circle"></i> Nuevo Documento
    </a>

    <!-- Formulario de búsqueda -->
    <form action="{{ route('documentos.index') }}" method="GET" class="mb-3 d-flex">
        <input type="text" name="search" class="form-control me-2"
            placeholder="Buscar por número o tipo" value="{{ request('search') }}">
        <button type="submit" class="btn btn-primary me-2">Buscar</button>
        <a href="{{ route('documentos.index') }}" class="btn btn-secondary">Limpiar</a>
    </form>

    <!-- Tabla de documentos -->
    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>N°</th>
                        <th>Nro Documento</th>
                        <th>Tipo</th>
                        <th>Fecha Documento</th>
                        <th>Fecha Entrega</th>
                        <th>Archivo</th>
                        <th>Ver</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($documentos ?? [] as $doc)
                    <tr>
                        <td>{{ $doc->IdDocumento }}</td>
                        <td>{{ $doc->cNroDocumento }}</td>
                        <td>{{ $doc->nombreTipoDocumento }}</td>
                        <td>{{ \Carbon\Carbon::parse($doc->dFechaDocumento)->format('Y-m-d') }}</td>
                        <td>{{ $doc->dFechaEntrega ? \Carbon\Carbon::parse($doc->dFechaEntrega)->format('Y-m-d') : '-' }}</td>
                        <td>
                            @if($doc->eDocumentoAdjunto)
                                <a href="{{ asset($doc->eDocumentoAdjunto) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                    <i class="bi bi-file-earmark-arrow-down"></i> Ver
                                </a>
                            @else
                                <span class="text-muted">Sin archivo</span>
                            @endif
                        </td>

                        <!-- Botón para ver detalle del documento en modal -->
                        <td class="text-center">
                            <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#detalleModal{{ $doc->IdDocumento }}">
                                <i class="bi bi-eye"></i> Ver
                            </button>

                            <!-- Modal Detalle -->
                            <div class="modal fade" id="detalleModal{{ $doc->IdDocumento }}" tabindex="-1" aria-labelledby="detalleModalLabel{{ $doc->IdDocumento }}" aria-hidden="true">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="detalleModalLabel{{ $doc->IdDocumento }}">
                                                Detalles del Documento #{{ $doc->IdDocumento }} - {{ $doc->nombreTipoDocumento }}
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            @if($doc->cartaPresentacion->isNotEmpty())
                                                <table class="table table-bordered table-striped">
                                                    <thead class="table-light">
                                                        <tr>
                                                            @php
                                                                $tipo = strtolower($doc->nombreTipoDocumento);
                                                            @endphp

                                                            @if(str_contains($tipo, 'secretaria') || str_contains($tipo, 'informe'))
                                                                <th>N° Expediente</th>
                                                                <th>Programa</th>
                                                                <th>Apellidos y Nombres</th>
                                                                <th>DNI</th>
                                                                <th>Módulo</th>
                                                                <th>N° Carta Presentación</th>
                                                                <th>Estado Carta</th>
                                                                <th>Fecha Registro</th>
                                                            @elseif(str_contains($tipo, 'memorandum') || str_contains($tipo, 'memorándum'))
                                                                <th>N° Expediente</th>
                                                                <th>Programa de Estudios</th>
                                                                <th>Apellidos y Nombres</th>
                                                                <th>Centro de Prácticas</th>
                                                                <th>N° Carta Presentación</th>
                                                                <th>Estado Carta</th>
                                                                <th>Fecha Registro</th>
                                                            @else
                                                                <th>Alumno</th>
                                                                <th>Número Carta</th>
                                                            @endif
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($doc->cartaPresentacion as $carta)
                                                            <tr>
                                                                @if(str_contains($tipo, 'secretaria') || str_contains($tipo, 'informe'))
                                                                    <td>{{ $carta->nNroExpediente ?? '-' }}</td>
                                                                    <td>{{ optional($carta->estudiante->programa)->nConstDescripcion ?? '-' }}</td>
                                                                    <td>{{ optional($carta->estudiante->persona)->cApellido ?? '' }} {{ optional($carta->estudiante->persona)->cNombre ?? '' }}</td>
                                                                    <td>{{ optional($carta->estudiante->persona)->cDNI ?? '-' }}</td>
                                                                    <td>{{ optional($carta->estudiante->modulo)->nConstDescripcion ?? '-' }}</td>
                                                                    <td>{{ $carta->nNroCarta ?? 'No asignado' }}</td>
                                                                    <td>{{ optional($carta->estado)->nConstDescripcion ?? ($carta->nEstado ?? '—') }}</td>
                                                                    <td>{{ $carta->pivot->dFechaRegistro ? \Carbon\Carbon::parse($carta->pivot->dFechaRegistro)->format('Y-m-d') : '-' }}</td>
                                                                @elseif(str_contains($tipo, 'memorandum') || str_contains($tipo, 'memorándum'))
                                                                    <td>{{ $carta->nNroExpediente ?? '-' }}</td>
                                                                    <td>{{ optional($carta->estudiante->programa)->nConstDescripcion ?? '-' }}</td>
                                                                    <td>{{ optional($carta->estudiante->persona)->cApellido ?? '' }} {{ optional($carta->estudiante->persona)->cNombre ?? '' }}</td>
                                                                    <td>{{ optional($carta->empresa)->cNombreEmpresa ?? '-' }}</td>
                                                                    <td>{{ $carta->nNroCarta ?? 'No asignado' }}</td>
                                                                    <td>{{ optional($carta->estado)->nConstDescripcion ?? ($carta->nEstado ?? '—') }}</td>
                                                                    <td>{{ $carta->pivot->dFechaRegistro ? \Carbon\Carbon::parse($carta->pivot->dFechaRegistro)->format('Y-m-d') : '-' }}</td>
                                                                @else
                                                                    <td>{{ optional($carta->estudiante->persona)->cApellido ?? '' }} {{ optional($carta->estudiante->persona)->cNombre ?? '' }}</td>
                                                                    <td>{{ $carta->nNroCarta ?? '-' }}</td>
                                                                @endif
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            @else
                                                <p class="text-center text-muted">No hay información adicional para este tipo de documento.</p>
                                            @endif
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Acciones -->
                        <td class="text-center">
                            <a href="{{ route('documentos.edit', $doc->IdDocumento) }}" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil-square"></i> Editar
                            </a>
                            <!--
                            <form action="{{ route('documentos.destroy', $doc->IdDocumento) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('¿Seguro de eliminar este documento?')" class="btn btn-danger btn-sm">
                                    <i class="bi bi-trash"></i> Eliminar
                                </button>
                            </form>
                            -->
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">No hay documentos registrados.</td>
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
