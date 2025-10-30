<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Estudiantes</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 12px;
            margin: 25px;
            color: #333;
        }

        /* Encabezado superior */
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
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
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
            color: #003366;
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

    <!-- Encabezado superior -->
    <div class="header">
        <div class="logo">Sistema EFSRT</div>
        <div class="fecha">Fecha: {{ date('d/m/Y') }}</div>
    </div>

    <h2>Reporte de Estudiantes</h2>

    {{-- Mostrar filtros aplicados --}}
    @if(!empty($filtros))
        <div class="filtros">
            <strong>Filtros aplicados:</strong><br>
            <span>
                @if(!empty($filtros['dni'])) <strong>DNI:</strong> {{ $filtros['dni'] }} &nbsp;&nbsp; @endif
                @if(!empty($filtros['nombre'])) <strong>Nombre:</strong> {{ $filtros['nombre'] }} &nbsp;&nbsp; @endif
                @if(!empty($filtros['programa'])) <strong>Programa:</strong> {{ $filtros['programa_descripcion'] ?? $filtros['programa'] }} &nbsp;&nbsp; @endif
                @if(!empty($filtros['plan'])) <strong>Plan:</strong> {{ $filtros['plan_descripcion'] ?? $filtros['plan'] }} &nbsp;&nbsp; @endif
                @if(!empty($filtros['modulo'])) <strong>Módulo:</strong> {{ $filtros['modulo_descripcion'] ?? $filtros['modulo'] }} &nbsp;&nbsp; @endif
                @if(!empty($filtros['turno'])) <strong>Turno:</strong> {{ $filtros['turno_descripcion'] ?? $filtros['turno'] }} @endif
            </span>
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>N°</th>
                <th>DNI</th>
                <th>Nombre Completo</th>
                <th>Programa de Estudios</th>
                <th>Plan de Estudio</th>
                <th>Módulo Formativo</th>
                <th>Turno</th>
                <th>Celular</th>
            </tr>
        </thead>
        <tbody>
            @forelse($resultados as $index => $estudiante)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $estudiante->cDNI }}</td>
                    <td>{{ $estudiante->cNombre }} {{ $estudiante->cApellido }}</td>
                    <td>{{ $estudiante->ProgramaDescripcion ?? '-' }}</td>
                    <td>{{ $estudiante->PlanDescripcion ?? '-' }}</td>
                    <td>{{ $estudiante->ModuloDescripcion ?? '-' }}</td>
                    <td>{{ $estudiante->TurnoDescripcion ?? '-' }}</td>
                    <td>{{ $estudiante->nCelular ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 10px;">
                        No se encontraron resultados para los filtros aplicados.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Generado automáticamente el {{ date('d/m/Y H:i') }}
    </div>

</body>
</html>






