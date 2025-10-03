{{-- resources/views/supervisiones/create.blade.php --}}
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

    <div class="d-flex justify-content-between mb-3">
        <h2>Registrar Supervisión</h2>
        <a href="{{ route('supervisiones.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>

    {{-- Mensajes de error --}}
    @if ($errors->any())
        <div class="alert alert-danger mb-3">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Formulario principal y detalles juntos --}}
    <form action="{{ route('supervisiones.store') }}" method="POST" id="supervisionForm">
        @csrf

        <!-- Cuadro 1: Supervisión Principal -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Datos de Supervisión</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Docente</label>
                    <select name="IdDocente" class="form-select" required>
                        <option value="">-- Seleccione --</option>
                        @foreach($docentes as $docente)
                            <option value="{{ $docente->IdDocente }}" {{ old('IdDocente') == $docente->IdDocente ? 'selected' : '' }}>
                                {{ $docente->persona->cNombre }} {{ $docente->persona->cApellido }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Carta de Presentación</label>
                    <select name="IdCartaPresentacion" class="form-select" required>
                        <option value="">-- Seleccione --</option>
                        @foreach($cartas as $carta)
                            <option value="{{ $carta->IdCartaPresentacion }}" {{ old('IdCartaPresentacion') == $carta->IdCartaPresentacion ? 'selected' : '' }}>
                                Carta #{{ $carta->nNroCarta }} - Estudiante: {{ $carta->estudiante->persona->cNombre }} {{ $carta->estudiante->persona->cApellido }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nota</label>
                    <input type="number" name="nNota" class="form-control" min="0" max="20" step="0.1" value="{{ old('nNota') }}" required>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Fecha Inicio</label>
                        <input type="date" name="dFechaInicio" class="form-control" value="{{ old('dFechaInicio') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Fecha Fin</label>
                        <input type="date" name="dFechaFin" class="form-control" value="{{ old('dFechaFin') }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Horas</label>
                    <input type="number" name="nHoras" class="form-control" min="1" step="1" value="{{ old('nHoras') }}" required>
                </div>
            </div>
        </div>

        <!-- Cuadro 2: Detalles dinámicos -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0">Detalles de Supervisión</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered" id="detallesTable">
                    <thead>
                        <tr>
                            <th>Número de Supervisión</th>
                            <th>Fecha de Supervisión</th>
                            <th style="width: 50px">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="number" name="detalles[0][nNroSupervision]" class="form-control" min="1" step="1" required></td>
                            <td><input type="date" name="detalles[0][dFechaSupervision]" class="form-control" required></td>
                            <td class="text-center">
                                <button type="button" class="btn btn-success btn-sm addRow"><i class="bi bi-plus"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="text-end mb-5">
            <button type="submit" class="btn btn-success">
                <i class="bi bi-save"></i> Guardar Supervisión con Detalles
            </button>
        </div>
    </form>
</div>

{{-- Bootstrap Icons --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

{{-- JS para agregar/quitar filas --}}
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.0/dist/jquery.min.js"></script>
<script>
let rowIndex = 1;

// Agregar fila
$(document).on('click', '.addRow', function() {
    let row = `<tr>
        <td><input type="number" name="detalles[${rowIndex}][nNroSupervision]" class="form-control" min="1" step="1" required></td>
        <td><input type="date" name="detalles[${rowIndex}][dFechaSupervision]" class="form-control" required></td>
        <td class="text-center">
            <button type="button" class="btn btn-danger btn-sm removeRow"><i class="bi bi-trash"></i></button>
        </td>
    </tr>`;
    $('#detallesTable tbody').append(row);
    rowIndex++;
});

// Eliminar fila
$(document).on('click', '.removeRow', function() {
    $(this).closest('tr').remove();
});
</script>
@endsection






