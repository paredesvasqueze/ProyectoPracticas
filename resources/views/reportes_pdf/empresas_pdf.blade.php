<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Empresas</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 12px;
            margin: 25px;
            color: #333;
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
            background-color: #99001F;
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

    <div class="header">
        <div class="logo">Sistema EFSRT</div>
        <div class="fecha">Fecha: {{ date('d/m/Y') }}</div>
    </div>

    <h2>Reporte de Empresas</h2>

    {{-- Mostrar filtros aplicados --}}
    @if(!empty($filtros))
        <div class="filtros">
            <strong>Filtros aplicados:</strong><br>
            <span>
                @if(!empty($filtros['ruc'])) <strong>RUC:</strong> {{ $filtros['ruc'] }} &nbsp;&nbsp; @endif
                @if(!empty($filtros['nombre_empresa'])) <strong>Nombre:</strong> {{ $filtros['nombre_empresa'] }} &nbsp;&nbsp; @endif
                @if(!empty($filtros['representante'])) <strong>Representante:</strong> {{ $filtros['representante'] }} &nbsp;&nbsp; @endif
                @if(!empty($filtros['direccion'])) <strong>Dirección:</strong> {{ $filtros['direccion'] }} &nbsp;&nbsp; @endif
                @if(!empty($filtros['telefono'])) <strong>Teléfono:</strong> {{ $filtros['telefono'] }} &nbsp;&nbsp; @endif
            </span>
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>N°</th>
                <th>RUC</th>
                <th>Nombre de Empresa</th>
                <th>Dirección</th>
                <th>Teléfono</th>
                <th>Correo</th>
                <th>Representante Legal</th>
                <th>Tipo de Empresa</th>
                <th>Profesión</th>
                <th>Cargo</th>
            </tr>
        </thead>
        <tbody>
            @forelse($resultados as $index => $empresa)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $empresa->nRUC ?? '-' }}</td>
                    <td>{{ $empresa->cNombreEmpresa ?? '-' }}</td>
                    <td>{{ $empresa->cDireccion ?? '-' }}</td>
                    <td>{{ $empresa->nTelefono ?? '-' }}</td>
                    <td>{{ $empresa->cCorreo ?? '-' }}</td>
                    <td>{{ $empresa->nRepresentanteLegal ?? '-' }}</td>
                    <td>{{ $empresa->TipoEmpresa ?? '-' }}</td>
                    <td>{{ $empresa->Profesion ?? '-' }}</td>
                    <td>{{ $empresa->Cargo ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" style="text-align: center; padding: 10px;">
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



