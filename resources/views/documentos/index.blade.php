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
                        <th>ID</th>
                        <th>Nro Documento</th>
                        <th>Tipo</th>
                        <th>Fecha Documento</th>
                        <th>Fecha Entrega</th>
                        <th>Archivo</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($documentos ?? [] as $doc)
                    <tr>
                        <td>{{ $doc->IdDocumento }}</td>
                        <td>{{ $doc->cNroDocumento }}</td>
                        <td>{{ $doc->nombreTipoDocumento }}</td>
                        <td>{{ $doc->dFechaDocumento }}</td>
                        <td>{{ $doc->dFechaEntrega }}</td>
                        <td>
                            @if($doc->eDocumentoAdjunto)
                                <a href="{{ asset('storage/'.$doc->eDocumentoAdjunto) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                    <i class="bi bi-file-earmark-arrow-down"></i> Ver
                                </a>
                            @else
                                <span class="text-muted">Sin archivo</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('documentos.edit', $doc->IdDocumento) }}" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil-square"></i> Editar
                            </a>
                            <form action="{{ route('documentos.destroy', $doc->IdDocumento) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('¿Seguro de eliminar este documento?')" class="btn btn-danger btn-sm">
                                    <i class="bi bi-trash"></i> Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No hay documentos registrados.</td>
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

