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
                <option value="empresas">Empresas</option>
                <option value="cartas">Cartas de Presentación</option>
                <option value="supervisiones">Supervisiones</option>
            </select>
        </div>

        {{-- Filtros para Estudiantes --}}
        <div id="filtrosEstudiantes" class="d-none mb-3">
            <label class="form-label">Filtros de Estudiantes</label>
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

            {{-- Docente --}}
            <div id="filtroDocente" class="mb-3">
                <label for="docente" class="form-label">Docente</label>
                <input type="text" name="docente" id="docente" class="form-control" placeholder="Docente">
            </div>

            {{-- Estudiante --}}
            <div id="filtroEstudiante" class="mb-3">
                <label for="estudiante" class="form-label">Estudiante</label>
                <input type="text" name="estudiante" id="estudiante" class="form-control" placeholder="Estudiante">
            </div>

            {{-- Empresa --}}
            <div id="filtroEmpresa" class="mb-3">
                <label for="empresa" class="form-label">Empresa</label>
                <input type="text" name="empresa" id="empresa" class="form-control" placeholder="Empresa">
            </div>

            <div class="row">
            {{-- N° de Supervisión --}}
            <div id="filtroNroSupervision" class="col-md-6 mb-3">
                <label for="nro_supervision" class="form-label">N° de Supervisión</label>
                <input type="text" name="nro_supervision" id="nro_supervision" class="form-control" placeholder="Ingrese el número de supervisión">
            </div>

            {{-- Horas --}}
            <div id="filtroHoras" class="col-md-6 mb-3">
                <label for="horas" class="form-label">Horas</label>
                <input type="number" name="horas" id="horas" class="form-control" placeholder="Ingrese las horas">
            </div>
        </div>


            <div class="row">
            {{-- Estado --}}
            <div id="filtroEstado" class="col-md-6 mb-3">
                <label for="estado" class="form-label">Estado</label>
                <select name="estado" id="estado" class="form-select">
                    <option value="">Todos</option>
                    <option value="1">Supervisado</option>
                    <option value="2">No Supervisado</option>
                </select>
            </div>

            {{-- Oficina --}}
            <div id="filtroOficina" class="col-md-6 mb-3">
                <label for="oficina" class="form-label">Oficina</label>
                <select name="oficina" id="oficina" class="form-select">
                    <option value="">Todos</option>
                    <option value="1">Coordinación</option>
                    <option value="2">Secretaría Académica</option>
                    <option value="3">Jefatura de Unidad Académica</option>
                </select>
            </div>
        </div>

            <div class="d-flex mb-2">
                <input type="date" name="fecha_inicio" class="form-control me-2" placeholder="Fecha inicio">
                <input type="date" name="fecha_fin" class="form-control" placeholder="Fecha fin">
            </div>
        </div>

        {{-- Filtros para Cartas de Presentación --}}
        <div id="filtrosCartas" class="d-none mb-3">
            <label class="form-label fw-bold">Filtros de Cartas de Presentación</label>

            {{-- Estudiante --}}
            <div id="filtroEstudianteCarta" class="mb-3">
                <label for="estudiante_carta" class="form-label">Nombre del Estudiante</label>
                <input type="text" name="estudiante_carta" id="estudiante_carta" class="form-control" placeholder="Ingrese el nombre del estudiante">
            </div>

            {{-- Empresa --}}
            <div id="filtroEmpresaCarta" class="mb-3">
                <label for="empresa_carta" class="form-label">Nombre de la Empresa</label>
                <input type="text" name="empresa_carta" id="empresa_carta" class="form-control" placeholder="Ingrese el nombre de la empresa">
            </div>

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

            <div class="row">
            {{-- Estado --}}
            <div id="filtroEstado" class="col-md-6 mb-3">
                <label for="estado" class="form-label">Estado de la Carta</label>
                <select name="estado" id="estado" class="form-select">
                    <option value="">Todos</option>
                    <option value="En proceso">En proceso</option>
                    <option value="En coordinación">En coordinación</option>
                    <option value="En jefatura académica">En jefatura académica</option>
                    <option value="En JUA">En JUA</option>
                    <option value="Observado">Observado</option>
                    <option value="Entregado">Entregado</option>
                </select>
            </div>

            {{-- Presentó Supervisión --}}
            <div id="filtroSupervision" class="col-md-6 mb-3">
                <label for="presento_supervision" class="form-label">¿Presentó Supervisión?</label>
                <select name="presento_supervision" id="presento_supervision" class="form-select">
                    <option value="">Todos</option>
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                </select>
            </div>
        </div>

            {{-- Fechas de Emisión --}}
            <div class="d-flex mb-2">
                <input type="date" name="fecha_inicio_carta" class="form-control me-2" placeholder="Desde">
                <input type="date" name="fecha_fin_carta" class="form-control" placeholder="Hasta">
            </div>

        </div>

        {{-- Filtros para Empresas --}}
        <div id="filtrosEmpresas" class="d-none mb-3">
            <label for="tipo_empresa" class="form-label">Tipo de Empresa</label>
            <select name="tipo_empresa" id="tipo_empresa" class="form-select">
                <option value="">Todos</option>
                <option value="1">Pública</option>
                <option value="2">Privada</option>
            </select>
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





