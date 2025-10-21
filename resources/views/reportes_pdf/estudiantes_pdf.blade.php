<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Estudiantes</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        th, td { border: 1px solid #333; padding: 5px; text-align: left; }
        th { background-color: #f2f2f2; }
        .filtros { font-size: 11px; margin-bottom: 10px; }
        .footer { text-align: center; font-size: 10px; margin-top: 15px; }
    </style>
</head>
<body>
    <h2>Reporte de Estudiantes</h2>

    {{-- Mostrar filtros aplicados --}}
    @if(!empty($filtros))
        <div class="filtros">
            <strong>Filtros aplicados:</strong>
            <span>
                @if(!empty($filtros['dni'])) DNI: {{ $filtros['dni'] }} @endif
                @if(!empty($filtros['nombre'])) | Nombre: {{ $filtros['nombre'] }} @endif
                @if(!empty($filtros['programa'])) | Programa: {{ $filtros['programa_descripcion'] ?? $filtros['programa'] }} @endif
                @if(!empty($filtros['plan'])) | Plan: {{ $filtros['plan_descripcion'] ?? $filtros['plan'] }} @endif
                @if(!empty($filtros['modulo'])) | Módulo: {{ $filtros['modulo_descripcion'] ?? $filtros['modulo'] }} @endif
                @if(!empty($filtros['turno'])) | Turno: {{ $filtros['turno_descripcion'] ?? $filtros['turno'] }} @endif
            </span>
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>#</th>
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
                    <td colspan="8" style="text-align: center;">No se encontraron resultados para los filtros aplicados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Generado el {{ date('d/m/Y H:i') }}
    </div>
</body>
</html>




