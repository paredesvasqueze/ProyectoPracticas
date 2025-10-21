<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Cartas de Presentación</title>
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

    <h2>Reporte de Cartas de Presentación</h2>

    {{-- Mostrar filtros aplicados --}}
    @if(!empty($filtros))
        <div class="filtros">
            <strong>Filtros aplicados:</strong><br>
            <span>
                @if(!empty($filtros['estudiante_carta'])) <strong>Estudiante:</strong> {{ $filtros['estudiante_carta'] }} &nbsp;&nbsp; @endif
                @if(!empty($filtros['empresa_carta'])) <strong>Empresa:</strong> {{ $filtros['empresa_carta'] }} &nbsp;&nbsp; @endif
                @if(!empty($filtros['estado'])) <strong>Estado:</strong> {{ $filtros['estado'] }} &nbsp;&nbsp; @endif
                @if(!empty($filtros['fecha_inicio_carta'])) <strong>Desde:</strong> {{ $filtros['fecha_inicio_carta'] }} &nbsp;&nbsp; @endif
                @if(!empty($filtros['fecha_fin_carta'])) <strong>Hasta:</strong> {{ $filtros['fecha_fin_carta'] }} @endif
            </span>
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>N°</th>
                <th>Estudiante</th>
                <th>Empresa</th>
                <th>Dirección Empresa</th>
                <th>N° Carta</th>
                <th>Fecha Carta</th>
                <th>Fecha Recojo</th>
                <th>Nota / Observación</th>
                <th>Presentó Supervisión</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($resultados as $index => $carta)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $carta->cNombre ?? '-' }} {{ $carta->cApellido ?? '' }}</td>
                    <td>{{ $carta->cNombreEmpresa ?? '-' }}</td>
                    <td>{{ $carta->cDireccion ?? '-' }}</td>
                    <td>{{ $carta->nNroCarta ?? '-' }}</td>
                    <td>
                        {{ !empty($carta->dFechaCarta)
                            ? \Carbon\Carbon::parse($carta->dFechaCarta)->format('d/m/Y')
                            : '-' }}
                    </td>
                    <td>
                        {{ !empty($carta->dFechaRecojo)
                            ? \Carbon\Carbon::parse($carta->dFechaRecojo)->format('d/m/Y')
                            : '-' }}
                    </td>
                    <td>{{ $carta->cObservacion ?? '-' }}</td>
                    <td>{{ $carta->bPresentoSupervision ? 'Sí' : 'No' }}</td>
                    <td>{{ $carta->nEstado ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" style="text-align: center; padding: 10px;">
                        No se encontraron cartas según los filtros aplicados.
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
