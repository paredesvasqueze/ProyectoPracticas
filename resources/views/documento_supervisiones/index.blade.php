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

    <h2 class="mb-4">Gestión de Documentos de Supervisión</h2>
    <a href="{{ route('documento_supervisiones.create') }}" class="btn btn-success mb-3">
        <i class="bi bi-plus-circle"></i> Nuevo Documento Supervisión
    </a>

    <!-- Formulario búsqueda -->
    <form action="{{ route('documento_supervisiones.index') }}" method="GET" class="mb-3 d-flex">
        <input type="text" name="search" class="form-control me-2" 
               placeholder="Buscar por nro supervisión o documento" value="{{ request('search') }}">
        <button type="submit" class="btn btn-primary me-2">Buscar</button>
        <a href="{{ route('documento_supervisiones.index') }}" class="btn btn-secondary">Limpiar</a>
    </form>

    <!-- Tabla -->
    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Fecha Registro</th>
                        <th>Fecha Supervisión</th>
                        <th>Nro Supervisión</th>
                        <th>Documento</th>
                        <th>Supervisión</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($documento_supervisiones as $ds)
                    <tr>
                        <td>{{ $ds->IdDocumentoSupervision }}</td>
                        <td>{{ $ds->dFechaRegistro }}</td>
                        <td>{{ $ds->dFechaSupervision }}</td>
                        <td>{{ $ds->nNroSupervision }}</td>
                        <td>{{ $ds->documento->cNroDocumento ?? '-' }}</td>
                        <td>Supervisión #{{ $ds->supervision->IdSupervision ?? '-' }}</td>
                        <td class="text-center">
                            <a href="{{ route('documento_supervisiones.show', $ds->IdDocumentoSupervision) }}" class="btn btn-info btn-sm">
                                <i class="bi bi-eye"></i> Ver
                            </a>
                            <a href="{{ route('documento_supervisiones.edit', $ds->IdDocumentoSupervision) }}" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil-square"></i> Editar
                            </a>
                            <form action="{{ route('documento_supervisiones.destroy', $ds->IdDocumentoSupervision) }}" 
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('¿Seguro de eliminar este registro?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="bi bi-trash"></i> Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No hay documentos de supervisión registrados.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Paginación -->
            <div class="d-flex justify-content-center">
                {{ $documento_supervisiones->links() }}
            </div>
        </div>
    </div>
</div>

{{-- Bootstrap Icons --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endsection
