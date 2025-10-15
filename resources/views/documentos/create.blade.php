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

    <h2 class="mb-4">Registrar Nuevo Documento</h2>

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
            <form action="{{ route('documentos.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Datos del documento -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="cNroDocumento" class="form-label">Número de Documento</label>
                        <input type="text" class="form-control" id="cNroDocumento" name="cNroDocumento"
                               value="{{ old('cNroDocumento') }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="dFechaDocumento" class="form-label">Fecha del Documento</label>
                        <input type="date" class="form-control" id="dFechaDocumento" name="dFechaDocumento"
                               value="{{ old('dFechaDocumento', date('Y-m-d')) }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="cTipoDocumento" class="form-label">Tipo de Documento</label>
                        <select class="form-select" id="cTipoDocumento" name="cTipoDocumento" required>
                            <option value="">-- Seleccione --</option>
                            @foreach($tiposDocumento as $id => $descripcion)
                                <option value="{{ $id }}" {{ old('cTipoDocumento') == $id ? 'selected' : '' }}>
                                    {{ $descripcion }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="dFechaEntrega" class="form-label">Fecha de Entrega</label>
                        <input type="date" class="form-control" id="dFechaEntrega" name="dFechaEntrega"
                               value="{{ old('dFechaEntrega') }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="eDocumentoAdjunto" class="form-label">Adjuntar Documento (PDF/Word opcional)</label>
                    <input type="file" class="form-control" id="eDocumentoAdjunto" name="eDocumentoAdjunto"
                           accept=".pdf,.doc,.docx">
                </div>

                <!-- TABLAS DINÁMICAS -->
                <div id="tablaMemorandum" class="tabla-dinamica card shadow-sm mt-4" style="display: none;">
                    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">MEMORÁNDUM A COORDINACIÓN DE PROGRAMA</h5>
                        <!--<button type="button" class="btn btn-light btn-sm btnAgregarFila" data-tipo="memorandum">
                            <i class="bi bi-plus-circle"></i> Agregar Fila
                        </button>
                        -->
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
                                    <th>N° Carta Presentación</th>
                                    <th>Estado Carta</th>
                                    <th>Fecha Registro</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody id="bodyMemorandum"></tbody>
                        </table>
                    </div>
                </div>

                <div id="tablaSecretaria" class="tabla-dinamica card shadow-sm mt-4" style="display: none;">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">INFORME A SECRETARIADO</h5>
                        <!--
                        <button type="button" class="btn btn-light btn-sm btnAgregarFila" data-tipo="secretaria">
                            <i class="bi bi-plus-circle"></i> Agregar Fila
                        </button>
                        -->
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
                                    <th>N° Expediente</th>
                                    <th>Programa</th>
                                    <th>Apellidos y Nombres</th>
                                    <th>DNI</th>
                                    <th>Módulo</th>
                                    <th>N° Carta Presentación</th>
                                    <th>Estado Carta</th>
                                    <th>Fecha Registro</th>
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
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Registrar Documento
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
    const fechaRegistro = "{{ date('Y-m-d') }}";

    // === Mostrar tabla según tipo de documento ===
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

    // === Agregar fila ===
    function agregarFila(tipo, data = {}) {
        const idCarta = data.IdCartaPresentacion || '';
        const nroCarta = data.nro_carta || 'No asignado';
        const estadoCarta = data.estado_carta || 'No registrado';
        const dni = data.dni || '';

        // Verificar si ya existe el alumno (por DNI o IdCarta)
        let existe = false;
        if (tipo === 'memorandum') {
            $('#bodyMemorandum tr').each(function() {
                const dniExistente = $(this).find('td').eq(2).text().trim(); // Apellidos y Nombres (posición 2)
                if (dniExistente === data.nombre) existe = true;
            });
        } else {
            $('#bodySecretaria tr').each(function() {
                const dniExistente = $(this).find('td').eq(3).text().trim(); // DNI (posición 3)
                if (dniExistente === dni) existe = true;
            });
        }

        if (existe) return; // Evita duplicados

        if (tipo === 'memorandum') {
            $('#bodyMemorandum').append(`
                <tr>
                    <td>${data.nro_expediente || ''}</td>
                    <td>${data.programa || ''}</td>
                    <td>${data.nombre || ''}</td>
                    <td>${data.centro_practicas || ''}</td>
                    <td>${nroCarta}</td>
                    <td>${estadoCarta}</td>
                    <td>
                        <input type="date" class="form-control" value="${fechaRegistro}" readonly
                            name="documento_carta_memorandum[${indexMemorandum}][dFechaRegistro]">
                        <input type="hidden" name="documento_carta_memorandum[${indexMemorandum}][IdCartaPresentacion]" value="${idCarta}">
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm btnEliminar">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            `);
            indexMemorandum++;
        } else if (tipo === 'secretaria') {
            $('#bodySecretaria').append(`
                <tr>
                    <td>${data.nro_expediente || ''}</td>
                    <td>${data.programa || ''}</td>
                    <td>${data.nombre || ''}</td>
                    <td>${dni}</td>
                    <td>${data.modulo || ''}</td>
                    <td>${nroCarta}</td>
                    <td>${estadoCarta}</td>
                    <td>
                        <input type="date" class="form-control" value="${fechaRegistro}" readonly
                            name="documento_carta_secretaria[${indexSecretaria}][dFechaRegistro]">
                        <input type="hidden" name="documento_carta_secretaria[${indexSecretaria}][IdCartaPresentacion]" value="${idCarta}">
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm btnEliminar">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            `);
            indexSecretaria++;
        }
    }

    // === Buscar estudiante AJAX ===
    function buscarEstudianteAjax(query, tipo) {
        if (typeof query !== 'string' || query.trim().length < 2) return;

        $.ajax({
            url: "{{ route('buscar.estudiante') }}",
            type: "GET",
            data: { q: query },
            success: function(respuesta) {
                console.log('Respuesta AJAX buscar.estudiante:', respuesta);

                // Ya no vaciamos la tabla -> se acumulan los resultados
                respuesta.forEach(est => agregarFila(tipo, est));
            },
            error: function(err) {
                console.error(err);
            }
        });
    }

    // === Botones de búsqueda y limpieza ===
    $('#btnBuscarMemorandum').click(function() {
        buscarEstudianteAjax($('#searchMemorandum').val(), 'memorandum');
    });

    $('#btnBuscarSecretaria').click(function() {
        buscarEstudianteAjax($('#searchSecretaria').val(), 'secretaria');
    });

    $('#btnLimpiarMemorandum').click(function() {
        $('#searchMemorandum').val('');
        $('#bodyMemorandum').empty();
        indexMemorandum = 0;
    });

    $('#btnLimpiarSecretaria').click(function() {
        $('#searchSecretaria').val('');
        $('#bodySecretaria').empty();
        indexSecretaria = 0;
    });

    // === Eliminar fila ===
    $(document).on('click', '.btnEliminar', function() {
        $(this).closest('tr').remove();
    });

    // === Agregar fila manualmente ===
    $(document).on('click', '.btnAgregarFila', function() {
        const tipo = $(this).data('tipo');
        agregarFila(tipo);
    });
});
</script>

@endsection













