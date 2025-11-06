<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Supervisiones</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 30px 20px 30px 10px;
        }

        .contenedor {
            max-width: 1000px;
            margin: 0 auto;
            margin-left: 0;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .logo {
            font-size: 18px;
            font-weight: bold;
            color: #003366;
        }

        .fecha {
            font-size: 11px;
            color: #666;
        }

        h2 {
            text-align: center;
            color: #003366;
            border-bottom: 2px solid #003366;
            padding-bottom: 5px;
            margin: 10px 0 15px;
        }

        .filtros {
            font-size: 11px;
            margin-bottom: 15px;
            background-color: #f9f9f9;
            border-left: 4px solid #003366;
            padding: 8px;
            width: 97%;
            margin-left: 5px;
        }

        table {
            width: 97%;
            border-collapse: collapse;
            margin-bottom: 15px;
            margin-left: 5px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 6px 8px;
            text-align: left;
        }

        th {
            background-color: #003366;
            color: #fff;
            font-weight: bold;
            text-align: center;
            font-size: 11px;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        td.numero-general {
            text-align: center;
            font-weight: bold;
            color: #003366;
        }

        td.numero-supervision {
            text-align: center;
            color: #000;
        }

        .nombre-estudiante {
            background-color: #fafafa;
            color: #000;
        }

        .footer {
            text-align: center;
            font-size: 10px;
            color: #777;
            margin-top: 20px;
            border-top: 1px solid #ccc;
            padding-top: 5px;
        }
    </style>
</head>
<body>

<div class="contenedor">

    <div class="header">
        <div class="logo">Sistema EFSRT</div>
        <div class="fecha">Fecha: {{ date('d/m/Y') }}</div>
    </div>

    <h2>Reporte de Supervisiones</h2>

    {{-- Mostrar filtros aplicados --}}
    @if(!empty($filtros))
        <div class="filtros">
            <strong>Filtros aplicados:</strong><br>
            <span>
                @if(!empty($filtros['docente'])) <strong>Docente:</strong> {{ $filtros['docente'] }} &nbsp;&nbsp; @endif
                @if(!empty($filtros['empresa'])) <strong>Empresa:</strong> {{ $filtros['empresa'] }} &nbsp;&nbsp; @endif
                @if(!empty($filtros['estudiante'])) <strong>Estudiante:</strong> {{ $filtros['estudiante'] }} &nbsp;&nbsp; @endif
                @if(!empty($filtros['fecha_inicio'])) <strong>Desde:</strong> {{ $filtros['fecha_inicio'] }} &nbsp;&nbsp; @endif
                @if(!empty($filtros['fecha_fin'])) <strong>Hasta:</strong> {{ $filtros['fecha_fin'] }} @endif
            </span>
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>N°</th>
                <th>Docente Supervisor</th>
                <th>Estudiante</th>
                <th>Empresa</th>
                <th>Dirección Empresa</th>
                <th>N° Carta</th> {{-- ✅ NUEVO --}}
                <th>N° Supervisión</th>
                <th>Fecha Supervisión</th>
                <th>Nota</th>
                <th>Horas</th>
                <th>Estado</th>
                <th>Oficina</th>
            </tr>
        </thead>

        <tbody>

            @php
                $contador = 1;
                $lastGrupo = null;
                $grupos = [];
                $tempGrupo = [];

                foreach ($resultados as $key => $r) {

                    // ✅ AGRUPACIÓN REAL POR CARTA + CICLO
                    $grupoActual = ($r->IdCartaPresentacion ?? '') . '|' .
                                   ($r->IdSupervision ?? '') . '|' .
                                   ($r->DocenteNombre ?? '') . '|' .
                                   ($r->DocenteApellido ?? '') . '|' .
                                   ($r->EstudianteNombre ?? '') . '|' .
                                   ($r->EstudianteApellido ?? '') . '|' .
                                   ($r->EmpresaNombre ?? '') . '|' .
                                   ($r->EmpresaDireccion ?? '') . '|' .
                                   ($r->EstadoDescripcion ?? '') . '|' .
                                   ($r->OficinaDescripcion ?? '');

                    if ($lastGrupo === $grupoActual || $lastGrupo === null) {
                        $tempGrupo[] = $r;
                    } else {
                        $grupos[] = $tempGrupo;
                        $tempGrupo = [$r];
                    }

                    $lastGrupo = $grupoActual;

                    if ($key === $resultados->count() - 1) {
                        $grupos[] = $tempGrupo;
                    }
                }
            @endphp

            @forelse($grupos as $grupo)
                @php $rowspan = count($grupo); @endphp

                @foreach($grupo as $index => $supervision)
                    <tr>
                        @if($index === 0)
                            <td rowspan="{{ $rowspan }}" class="numero-general">{{ $contador++ }}</td>

                            <td rowspan="{{ $rowspan }}">
                                {{ $supervision->DocenteNombre }} {{ $supervision->DocenteApellido }}
                            </td>

                            <td rowspan="{{ $rowspan }}" class="nombre-estudiante">
                                {{ $supervision->EstudianteNombre }} {{ $supervision->EstudianteApellido }}
                            </td>

                            <td rowspan="{{ $rowspan }}">{{ $supervision->EmpresaNombre }}</td>
                            <td rowspan="{{ $rowspan }}">{{ $supervision->EmpresaDireccion }}</td>

                            {{-- ✅ MOSTRAR NÚMERO DE CARTA --}}
                            <td rowspan="{{ $rowspan }}">
                                {{ $supervision->nNroCarta ?? '-' }}
                            </td>
                        @endif

                        {{-- No combinados --}}
                        <td class="numero-supervision">{{ $supervision->nNroSupervision }}</td>

                        <td>
                            {{ $supervision->dFechaSupervision
                                ? \Carbon\Carbon::parse($supervision->dFechaSupervision)->format('d/m/Y')
                                : '-' }}
                        </td>

                        @if($index === 0)
                            <td rowspan="{{ $rowspan }}">{{ $supervision->nNota }}</td>
                            <td rowspan="{{ $rowspan }}">{{ $supervision->nHoras }}</td>
                            <td rowspan="{{ $rowspan }}">{{ $supervision->EstadoDescripcion }}</td>
                            <td rowspan="{{ $rowspan }}">{{ $supervision->OficinaDescripcion }}</td>
                        @endif
                    </tr>
                @endforeach

            @empty
                <tr>
                    <td colspan="12" style="text-align:center; padding:10px;">
                        No se encontraron supervisiones según los filtros aplicados.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Generado automáticamente el {{ date('d/m/Y H:i') }}
    </div>

</div>

</body>
</html>








