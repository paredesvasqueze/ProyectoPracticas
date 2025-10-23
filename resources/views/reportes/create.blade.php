{{-- resources/views/reportes/create.blade.php --}}
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

    <h2 class="mb-4">Generar Reporte</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form id="formReporte" action="{{ route('reportes.store') }}" method="POST">
        @csrf

        <!-- Tipo de reporte -->
        <div class="mb-3">
            <label for="tipoReporte" class="form-label">Tipo de Reporte</label>
            <select id="tipoReporte" name="tipo" class="form-select" required>
                <option value="">-- Seleccionar --</option>
                <option value="estudiantes">Estudiantes</option>
                <option value="supervisiones">Supervisiones</option>
                <option value="cartas">Cartas de Presentación</option>
                <option value="empresas">Empresas</option>
                <option value="documentos">Documentos</option>
            </select>
        </div>

        {{-- Filtros para Estudiantes --}}
        <div id="filtrosEstudiantes" class="d-none mb-3">
            <label class="form-label">Filtros de Estudiantes</label>
            <input type="text" name="dni" class="form-control mb-2" placeholder="DNI">
            <input type="text" name="nombre" class="form-control mb-2" placeholder="Nombre">
            <div class="row mb-2">
                <div class="col-md-4">
                    <label for="programa" class="form-label">Programa de Estudios</label>
                    <select name="programa" id="programa" class="form-select">
                        <option value="">Todos</option>
                        @foreach($programas as $programa)
                            <option value="{{ $programa->nConstValor }}">{{ $programa->nConstDescripcion }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="plan" class="form-label">Plan de Estudio</label>
                    <select name="plan" id="plan" class="form-select">
                        <option value="">Todos</option>
                        @foreach($planes as $plan)
                            <option value="{{ $plan->nConstValor }}">{{ $plan->nConstDescripcion }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="modulo" class="form-label">Módulo Formativo</label>
                    <select name="modulo" id="modulo" class="form-select">
                        <option value="">Todos</option>
                        @foreach($modulos as $modulo)
                            <option value="{{ $modulo->nConstValor }}">{{ $modulo->nConstDescripcion }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-2">
                <label for="turno" class="form-label">Turno</label>
                <select name="turno" id="turno" class="form-select">
                    <option value="">Todos</option>
                    @foreach($turnos as $turno)
                        <option value="{{ $turno->nConstValor }}">{{ $turno->nConstDescripcion }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Filtros para Supervisiones --}}
        <div id="filtrosSupervisiones" class="d-none mb-3">
            <label class="form-label">Filtros de Supervisiones</label>
            <input type="text" name="docente" class="form-control mb-2" placeholder="Docente">
            <input type="text" name="estudiante" class="form-control mb-2" placeholder="Estudiante">
            <input type="text" name="empresa" class="form-control mb-2" placeholder="Empresa">
            <div class="d-flex mb-2">
                <input type="date" name="fecha_inicio" class="form-control me-2">
                <input type="date" name="fecha_fin" class="form-control">
            </div>
        </div>

        {{-- Filtros para Cartas de Presentación --}}
        <div id="filtrosCartas" class="d-none mb-3">
            <label class="form-label">Filtros de Cartas</label>
            <input type="text" name="estudiante_carta" class="form-control mb-2" placeholder="Estudiante">
            <input type="text" name="empresa_carta" class="form-control mb-2" placeholder="Empresa">
            <select name="estado" class="form-select mb-2">
                <option value="">-- Estado --</option>
                @foreach($estados as $estado)
                    <option value="{{ $estado->nConstValor }}">{{ $estado->nConstDescripcion }}</option>
                @endforeach
            </select>
            <div class="d-flex mb-2">
                <input type="date" name="fecha_inicio_carta" class="form-control me-2">
                <input type="date" name="fecha_fin_carta" class="form-control">
            </div>
        </div>

        {{-- Filtros para Empresas --}}
        <div id="filtrosEmpresas" class="d-none mb-3">
            <label class="form-label">Filtros de Empresas</label>
            <input type="text" name="nombre_empresa" class="form-control mb-2" placeholder="Nombre de la Empresa">
            <input type="text" name="ruc" class="form-control mb-2" placeholder="RUC">
            <input type="text" name="representante" class="form-control mb-2" placeholder="Representante Legal">
            <input type="text" name="correo" class="form-control mb-2" placeholder="Correo">
            <input type="text" name="telefono" class="form-control mb-2" placeholder="Teléfono">
        </div>

        {{-- Filtros para Documentos --}}
        <div id="filtrosDocumentos" class="d-none mb-3">
            <label class="form-label">Filtros de Documentos</label>
            <select name="tipo_documento" class="form-select mb-2">
                <option value="">Todos</option>
                @foreach($tipos_documento as $tipo)
                    <option value="{{ $tipo->nConstValor }}">{{ $tipo->nConstDescripcion }}</option>
                @endforeach
            </select>
            <input type="text" name="estudiante_doc" class="form-control mb-2" placeholder="Estudiante">
            <input type="text" name="carta_doc" class="form-control mb-2" placeholder="Carta de Presentación">
            <div class="d-flex mb-2">
                <input type="date" name="fecha_inicio_doc" class="form-control me-2">
                <input type="date" name="fecha_fin_doc" class="form-control">
            </div>
        </div>

        <div class="mt-3">
            {{-- Botón Vista Previa / Descargar PDF --}}
            <a href="#" id="btnVistaPrevia" class="btn btn-info d-none" target="_blank" role="button">
                <i class="bi bi-eye me-1"></i>
                Vista Previa /
                <i class="bi bi-download mx-1"></i>
                Descargar PDF
            </a>

            <a href="{{ route('reportes.index') }}" class="btn btn-secondary ms-2">Cancelar</a>
        </div>
    </form>

</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<script>
const tipoSelect = document.getElementById('tipoReporte');
const filtros = ['Estudiantes','Supervisiones','Cartas','Empresas','Documentos'];
const btnVistaPrevia = document.getElementById('btnVistaPrevia');

tipoSelect.addEventListener('change', actualizarVistaPrevia);
document.getElementById('formReporte').addEventListener('input', actualizarVistaPrevia);

function actualizarVistaPrevia() {
    filtros.forEach(tipo => {
        const div = document.getElementById('filtros' + tipo);
        if(div) div.classList.add('d-none');
    });

    switch(tipoSelect.value) {
        case 'estudiantes': document.getElementById('filtrosEstudiantes').classList.remove('d-none'); break;
        case 'supervisiones': document.getElementById('filtrosSupervisiones').classList.remove('d-none'); break;
        case 'cartas': document.getElementById('filtrosCartas').classList.remove('d-none'); break;
        case 'empresas': document.getElementById('filtrosEmpresas').classList.remove('d-none'); break;
        case 'documentos': document.getElementById('filtrosDocumentos').classList.remove('d-none'); break;
    }

    if(tipoSelect.value){
        const params = new URLSearchParams(new FormData(document.getElementById('formReporte'))).toString();
        btnVistaPrevia.classList.remove('d-none');
        btnVistaPrevia.href = `/reportes/vista-previa/${tipoSelect.value}?${params}`;
    } else {
        btnVistaPrevia.classList.add('d-none');
    }
}
</script>
@endsection





