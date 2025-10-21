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
            color: #99001F;
        }

        .fecha {
            font-size: 11px;
            color: #666;
        }

        h2 {
            text-align: center;
            color: #99001F;
            border-bottom: 2px solid #99001F;
            padding-bottom: 5px;
            margin: 10px 0 15px;
        }

        .filtros {
            font-size: 11px;
            margin-bottom: 15px;
            background-color: #f9f9f9;
            border-left: 4px solid #99001F;
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
            background-color: #99001F;
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

        td:first-child {
            text-align: center;
            font-weight: bold;
            color: #99001F;
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
                <th>N° Supervisión</th>
                <th>Fecha Supervisión</th>
                <th>Nota</th>
                <th>Horas</th>
                <th>Estado</th>
                <th>Oficina</th>
            </tr>
        </thead>
        <tbody>
            @forelse($resultados as $index => $supervision)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $supervision->DocenteNombre ?? '-' }} {{ $supervision->DocenteApellido ?? '' }}</td>
                    <td>{{ $supervision->EstudianteNombre ?? '-' }} {{ $supervision->EstudianteApellido ?? '' }}</td>
                    <td>{{ $supervision->EmpresaNombre ?? '-' }}</td>
                    <td>{{ $supervision->EmpresaDireccion ?? '-' }}</td>
                    <td>{{ $supervision->nNroSupervision ?? '-' }}</td>
                    <td>
                        {{ !empty($supervision->dFechaSupervision)
                            ? \Carbon\Carbon::parse($supervision->dFechaSupervision)->format('d/m/Y')
                            : '-' }}
                    </td>
                    <td>{{ $supervision->nNota ?? '-' }}</td>
                    <td>{{ $supervision->nHoras ?? '-' }}</td>
                    {{-- Usar descripciones de CONSTANTE --}}
                    <td>{{ $supervision->EstadoDescripcion ?? '-' }}</td>
                    <td>{{ $supervision->OficinaDescripcion ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" style="text-align: center; padding: 10px;">
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





