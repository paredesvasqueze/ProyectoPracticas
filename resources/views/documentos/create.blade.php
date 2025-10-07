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
                               value="{{ old('dFechaDocumento') }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="cTipoDocumento" class="form-label">Tipo de Documento</label>
                        <select class="form-select" id="cTipoDocumento" name="cTipoDocumento" required>
                            <option value="">-- Seleccione --</option>
                            @foreach($tiposDocumento as $id => $descripcion)
                                <option value="{{ $id }}" 
                                    {{ old('cTipoDocumento') == $id ? 'selected' : '' }}>
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
                    <label for="eDocumentoAdjunto" class="form-label">Adjuntar Documento</label>
                    <input type="file" class="form-control" id="eDocumentoAdjunto" name="eDocumentoAdjunto">
                </div>

                <!-- MEMORANDUM -->
                <div id="tablaMemorandum" class="tabla-dinamica card shadow-sm mt-4" style="display: none;">
                    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">MEMORÁNDUM A COORDINACIÓN DE PROGRAMA</h5>
                        <div>
                            <button type="button" class="btn btn-light btn-sm btnAgregarFila" data-tipo="memorandum">
                                <i class="bi bi-plus-circle"></i> Agregar Fila
                            </button>
                            <button type="button" class="btn btn-primary btn-sm btnBuscarFila" data-tipo="memorandum">
                                <i class="bi bi-search"></i> Buscar
                            </button>
                            <button type="button" class="btn btn-warning btn-sm btnLimpiarFila" data-tipo="memorandum">
                                <i class="bi bi-x-circle"></i> Limpiar
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <input type="text" class="form-control searchInput" id="searchMemorandum" placeholder="Buscar por DNI o Nombre">
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
                            <tbody id="bodyMemorandum">
                                @if(old('memorandum'))
                                    @foreach(old('memorandum') as $i => $fila)
                                        <tr>
                                            <td><input type="text" name="memorandum[{{ $i }}][nro_expediente]" class="form-control" value="{{ $fila['nro_expediente'] }}" required></td>
                                            <td><input type="text" name="memorandum[{{ $i }}][programa]" class="form-control" value="{{ $fila['programa'] }}" required></td>
                                            <td><input type="text" name="memorandum[{{ $i }}][nombre]" class="form-control" value="{{ $fila['nombre'] }}" required></td>
                                            <td><input type="text" name="memorandum[{{ $i }}][centro_practicas]" class="form-control" value="{{ $fila['centro_practicas'] }}" required></td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-danger btn-sm btnEliminar">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
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
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

<script>
$(document).ready(function() {

    let indexMemorandum = {{ old('memorandum') ? count(old('memorandum')) : 0 }};

    function mostrarTabla() {
        let tipo = $('#cTipoDocumento option:selected').text().trim().toLowerCase();
        $('.tabla-dinamica').hide();
        if (tipo.includes('memorándum')) $('#tablaMemorandum').show();
    }

    mostrarTabla();
    $('#cTipoDocumento').on('change', mostrarTabla);

    function agregarFila(tipo, persona = null) {
        let row = '';
        if (tipo === 'memorandum') {
            row = `<tr>
                <td><input type="text" name="memorandum[${indexMemorandum}][nro_expediente]" class="form-control" value="${persona ? persona.nro_expediente : ''}" required></td>
                <td><input type="text" name="memorandum[${indexMemorandum}][programa]" class="form-control" value="${persona ? persona.programa : ''}" required></td>
                <td><input type="text" name="memorandum[${indexMemorandum}][nombre]" class="form-control" value="${persona ? persona.nombre : ''}" required></td>
                <td><input type="text" name="memorandum[${indexMemorandum}][centro_practicas]" class="form-control" value="${persona ? persona.centro_practicas : ''}" required></td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger btn-sm btnEliminar"><i class="bi bi-trash"></i></button>
                </td>
            </tr>`;
            $('#bodyMemorandum').append(row);
            indexMemorandum++;
        }
    }

    $(document).on('click', '.btnAgregarFila', function() {
        agregarFila($(this).data('tipo'));
    });

    $(document).on('click', '.btnEliminar', function() {
        $(this).closest('tr').remove();
    });

    // Botón Buscar
    $(document).on('click', '.btnBuscarFila', function() {
        let tipo = $(this).data('tipo');
        let input = '#searchMemorandum';
        let query = $(input).val().trim();

        if(query === '') return alert('Ingrese DNI o nombre para buscar');

        $.get("{{ route('documentos.buscar-persona') }}", { term: query }, function(data) {
            data.forEach(persona => {
                agregarFila('memorandum', {
                    nro_expediente: persona.nro_expediente || '',
                    programa: persona.programa || '',
                    nombre: persona.nombre || '',
                    centro_practicas: persona.centro_practicas || ''
                });
            });
        });
    });

    // Autocomplete Memorandum
    $("#searchMemorandum").autocomplete({
        source: "{{ route('documentos.buscar-persona') }}",
        minLength: 2,
        focus: function(event, ui) {
            $(this).val(ui.item.nombre + " (" + ui.item.dni + ")");
            return false;
        },
        select: function(event, ui) {
            agregarFila('memorandum', {
                nro_expediente: ui.item.nro_expediente,
                programa: ui.item.programa,
                nombre: ui.item.nombre,
                centro_practicas: ui.item.centro_practicas
            });
            $(this).val('');
            return false;
        }
    }).autocomplete("instance")._renderItem = function(ul, item) {
        return $("<li>")
            .append("<div>" + item.nombre + " (" + item.dni + ") - " + (item.centro_practicas || '') + "</div>")
            .appendTo(ul);
    };

});
</script>
@endsection








