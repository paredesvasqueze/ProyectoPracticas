{{-- resources/views/empresas/index.blade.php --}}
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

    <h2 class="mb-4">Gestión de Empresas</h2>
    <a href="{{ route('empresas.create') }}" class="btn btn-success mb-3">
        <i class="bi bi-plus-circle"></i> Nueva Empresa
    </a>

    <!-- Formulario de búsqueda por nombre o RUC -->
    <form action="{{ route('empresas.index') }}" method="GET" class="mb-3 d-flex">
        <input type="text" name="search" class="form-control me-2" 
            placeholder="Buscar por nombre o RUC" value="{{ request('search') }}">
        <button type="submit" class="btn btn-primary me-2">Buscar</button>
        <a href="{{ route('empresas.index') }}" class="btn btn-secondary">Limpiar</a>
    </form>

    <!-- Tabla de empresas -->
    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Tipo</th>
                        <th>Nombre</th>
                        <th>Representante</th>
                        <th>Profesión</th>
                        <th>Cargo</th>
                        <th>RUC</th>
                        <th>Dirección</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($empresas ?? [] as $empresa)
                    <tr>
                        <td>{{ $empresa->tipoEmpresa->nConstDescripcion ?? '-' }}</td>
                        <td>{{ $empresa->cNombreEmpresa }}</td>
                        <td>{{ $empresa->nRepresentanteLegal }}</td>
                        <td>{{ $empresa->profesion->nConstDescripcion ?? '-' }}</td>
                        <td>{{ $empresa->cargo->nConstDescripcion ?? '-' }}</td>
                        <td>{{ $empresa->nRUC }}</td>
                        <td>{{ $empresa->cDireccion }}</td>
                        <td>{{ $empresa->cCorreo }}</td>
                        <td>{{ $empresa->nTelefono }}</td>
                        <td class="text-center">
                            <a href="{{ route('empresas.edit', $empresa->IdEmpresa) }}" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil-square"></i> Editar
                            </a>
                            <!--
                            <form action="{{ route('empresas.destroy', $empresa->IdEmpresa) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('¿Seguro de eliminar esta empresa?')" class="btn btn-danger btn-sm">
                                    <i class="bi bi-trash"></i> Eliminar
                                </button>
                            </form>
                            -->
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="text-center">No hay empresas registradas.</td>
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

