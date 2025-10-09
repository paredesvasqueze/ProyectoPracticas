{{-- resources/views/documentos/edit.blade.php --}}
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

    <h2 class="mb-4">Editar Documento</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('documentos.update', $documento->IdDocumento) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Datos del documento -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="cNroDocumento" class="form-label">Número de Documento</label>
                        <input type="text" class="form-control" id="cNroDocumento" name="cNroDocumento"
                               value="{{ old('cNroDocumento', $documento->cNroDocumento) }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="dFechaDocumento" class="form-label">Fecha del Documento</label>
                        <input type="date" class="form-control" id="dFechaDocumento" name="dFechaDocumento"
                               value="{{ old('dFechaDocumento', $documento->dFechaDocumento) }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="cTipoDocumento" class="form-label">Tipo de Documento</label>
                        <select class="form-select" id="cTipoDocumento" name="cTipoDocumento" required>
                            <option value="">-- Seleccione --</option>
                            @foreach($tiposDocumento as $id => $descripcion)
                                <option value="{{ $id }}" {{ old('cTipoDocumento', $documento->cTipoDocumento) == $id ? 'selected' : '' }}>
                                    {{ $descripcion }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="dFechaEntrega" class="form-label">Fecha de Entrega</label>
                        <input type="date" class="form-control" id="dFechaEntrega" name="dFechaEntrega"
                               value="{{ old('dFechaEntrega', $documento->dFechaEntrega) }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="eDocumentoAdjunto" class="form-label">Adjuntar Documento (PDF opcional)</label>
                    <input type="file" class="form-control" id="eDocumentoAdjunto" name="eDocumentoAdjunto" accept=".pdf">

                    @if($documento->eDocumentoAdjunto)
                        <div class="mt-2">
                            <a href="{{ asset('storage/' . $documento->eDocumentoAdjunto) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-file-earmark-pdf"></i> Ver Documento Actual
                            </a>
                        </div>
                    @endif
                </div>

                <!-- TABLA MEMORÁNDUM -->
                <div id="tablaMemorandum" class="tabla-dinamica card shadow-sm mt-4" style="display: none;">
                    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">MEMORÁNDUM A COORDINACIÓN DE PROGRAMA</h5>
                        <button type="button" class="btn btn-light btn-sm btnAgregarFila" data-tipo="memorandum">
                            <i class="bi bi-plus-circle"></i> Agregar Fila
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="mb-2 d-flex">
                            <input type="text" class="form-control me-2 searchInput" id="searchMemorandum"
                                   placeholder="Buscar estudiante por DNI o nombre">
                            <button type="button" class="btn btn-primary me-2" id="btnBuscarMemorandum">Buscar</button>
                            <button type="button" class="btn btn-secondary" id="btnLimpiarMemorandum">Limpiar</button>
                        </div>
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>N° Expediente</th>
                                    <th>Programa de Estudios</th>
                                    <th>Apellidos y Nombres</th>
                                    <th>Centro de Prácticas</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody id="bodyMemorandum"></tbody>
                        </table>
                    </div>
                </div>

                <!-- TABLA INFORME A SECRETARIADO -->
                <div id="tablaSecretaria" class="tabla-dinamica card shadow-sm mt-4" style="display: none;">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">INFORME A SECRETARIADO</h5>
                        <button type="button" class="btn btn-light btn-sm btnAgregarFila" data-tipo="secretaria">
                            <i class="bi bi-plus-circle"></i> Agregar Fila
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="mb-2 d-flex">
                            <input type="text" class="form-control me-2 searchInput" id="searchSecretaria"
                                   placeholder="Buscar estudiante por DNI o nombre">
                            <button type="button" class="btn btn-primary me-2" id="btnBuscarSecretaria">Buscar</button>
                            <button type="button" class="btn btn-secondary" id="btnLimpiarSecretaria">Limpiar</button>
                        </div>
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>N° Secuencial</th>
                                    <th>Programa</th>
                                    <th>Apellidos y Nombres</th>
                                    <th>DNI</th>
                                    <th>Módulo</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody id="bodySecretaria"></tbody>
                        </table>
                    </div>
                </div>

                <!-- Botones -->
                <div class="mt-4">
                    <a href="{{ route('documentos.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Guardar Cambios
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {

    let indexMemorandum = 0;
    let indexSecretaria = 0;

    // Mostrar tabla según tipo seleccionado
    function mostrarTabla() {
        let tipo = $('#cTipoDocumento option:selected').text().trim().toLowerCase();
        $('.tabla-dinamica').hide();

        if (tipo.includes('memorándum') || tipo.includes('memorandum')) {
            $('#tablaMemorandum').show();
        } else if (tipo.includes('informe') || tipo.includes('secretariado') || tipo.includes('secretaría')) {
            $('#tablaSecretaria').show();
        }
    }

    mostrarTabla();
    $('#cTipoDocumento').on('change', mostrarTabla);

    // Agregar fila
    function agregarFila(tipo, data = {}) {
        if (tipo === 'memorandum') {
            $('#bodyMemorandum').append(`
                <tr>
                    <td><input type="text" name="memorandum[${indexMemorandum}][nro_expediente]" class="form-control" value="${data.nro_expediente || ''}" required></td>
                    <td><input type="text" name="memorandum[${indexMemorandum}][programa]" class="form-control" value="${data.programa || ''}" required></td>
                    <td><input type="text" name="memorandum[${indexMemorandum}][nombre]" class="form-control" value="${data.nombre || ''}" required></td>
                    <td><input type="text" name="memorandum[${indexMemorandum}][centro_practicas]" class="form-control" value="${data.centro_practicas || ''}" required></td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm btnEliminar"><i class="bi bi-trash"></i></button>
                    </td>
                </tr>
            `);
            indexMemorandum++;
        } else if (tipo === 'secretaria') {
            $('#bodySecretaria').append(`
                <tr>
                    <td><input type="text" name="secretaria[${indexSecretaria}][nro_secuencial]" class="form-control" value="${data.nro_secuencial || ''}" required></td>
                    <td><input type="text" name="secretaria[${indexSecretaria}][programa]" class="form-control" value="${data.programa || ''}" required></td>
                    <td><input type="text" name="secretaria[${indexSecretaria}][nombre]" class="form-control" value="${data.nombre || ''}" required></td>
                    <td><input type="text" name="secretaria[${indexSecretaria}][dni]" class="form-control" maxlength="8" value="${data.dni || ''}" required></td>
                    <td><input type="text" name="secretaria[${indexSecretaria}][modulo]" class="form-control" value="${data.modulo || ''}" required></td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm btnEliminar"><i class="bi bi-trash"></i></button>
                    </td>
                </tr>
            `);
            indexSecretaria++;
        }
    }

    // Botón agregar fila
    $(document).on('click', '.btnAgregarFila', function() {
        agregarFila($(this).data('tipo'));
    });

    // Eliminar fila
    $(document).on('click', '.btnEliminar', function() {
        $(this).closest('tr').remove();
    });

    // 🔍 Función búsqueda AJAX
    function buscarEstudianteAjax(query, tipo) {
        if(query.length < 2) return; // mínimo 2 caracteres
        $.ajax({
            url: "{{ route('buscar.estudiante') }}",
            type: "GET",
            data: { q: query },
            success: function(respuesta) {
                if(tipo === 'memorandum') {
                    $('#bodyMemorandum').empty();
                    respuesta.forEach(function(est) {
                        agregarFila('memorandum', est);
                    });
                } else if(tipo === 'secretaria') {
                    $('#bodySecretaria').empty();
                    respuesta.forEach(function(est) {
                        agregarFila('secretaria', {
                            nro_secuencial: est.id,
                            programa: est.programa,
                            nombre: est.nombre,
                            dni: est.dni,
                            modulo: est.modulo
                        });
                    });
                }
            },
            error: function(err) {
                console.error(err);
            }
        });
    }

    // Memorandum: buscar y limpiar
    $('#btnBuscarMemorandum').click(function() {
        let query = $('#searchMemorandum').val();
        buscarEstudianteAjax(query, 'memorandum');
    });
    $('#btnLimpiarMemorandum').click(function() {
        $('#searchMemorandum').val('');
        $('#bodyMemorandum').empty();
    });

    // Secretaria: buscar y limpiar
    $('#btnBuscarSecretaria').click(function() {
        let query = $('#searchSecretaria').val();
        buscarEstudianteAjax(query, 'secretaria');
    });
    $('#btnLimpiarSecretaria').click(function() {
        $('#searchSecretaria').val('');
        $('#bodySecretaria').empty();
    });

});
</script>
@endsection


