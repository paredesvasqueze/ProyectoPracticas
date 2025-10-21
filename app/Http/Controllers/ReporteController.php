<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteController extends Controller
{
    
    /**
     * Mostrar la página principal de reportes (redirige al formulario de filtros)
     */
    public function index()
    {
        return redirect()->route('reportes.create');
    }

    /**
     * Mostrar formulario para generar reportes.
     */
    public function create()
    {
        $turnos = DB::table('CONSTANTE')->where('nConstGrupo', 'TURNO')->get();
        $programas = DB::table('CONSTANTE')->where('nConstGrupo', 'PROGRAMA_ESTUDIO')->get();
        $planes = DB::table('CONSTANTE')->where('nConstGrupo', 'PLAN_ESTUDIO')->get();
        $modulos = DB::table('CONSTANTE')->where('nConstGrupo', 'MODULO_FORMATIVO')->get();
        $tipos_documento = DB::table('CONSTANTE')->where('nConstGrupo', 'TIPO_DOCUMENTO')->get();
        $estados = DB::table('CONSTANTE')->where('nConstGrupo', 'ESTADO_SUPERVISION')->get();

        return view('reportes.create', compact(
            'turnos', 'programas', 'planes', 'modulos', 'tipos_documento', 'estados'
        ));
    }

    /**
     * Filtrar datos y mostrar resultados en HTML.
     */
    public function store(Request $request)
    {
        $tipo = $request->input('tipo');
        $resultados = $this->filtrarDatos($tipo, $request);

        // Guardar filtros en sesión para el PDF
        session(['filtros_reportes' => $request->all()]);

        return view('reportes.show', compact('tipo', 'resultados'));
    }

    /**
     * Generar PDF de los resultados filtrados.
     */
    public function vistaPrevia($tipo, Request $request)
    {
        // Usar filtros del request o de la sesión
        $filtros = $request->all();
        if (empty($filtros)) {
            $filtros = session('filtros_reportes', []);
            $request->replace($filtros);
        }

        $resultados = $this->filtrarDatos($tipo, $request);

        $view = match($tipo) {
            'estudiantes' => 'reportes_pdf.estudiantes_pdf',
            'supervisiones' => 'reportes_pdf.supervisiones_pdf',
            'cartas' => 'reportes_pdf.cartas_pdf',
            'empresas' => 'reportes_pdf.empresas_pdf',
            'documentos' => 'reportes_pdf.documentos_pdf',
            default => 'reportes.show',
        };

        $pdf = Pdf::loadView($view, compact('resultados', 'filtros'));
        return $pdf->stream("reporte_{$tipo}.pdf");
    }

    /**
     * Filtra los datos según tipo y filtros del request.
     */
    private function filtrarDatos($tipo, Request $request)
    {
        $resultados = collect();

        switch ($tipo) {
            case 'estudiantes':
                $query = DB::table('ESTUDIANTE')
                    ->join('PERSONA', 'ESTUDIANTE.IdPersona', '=', 'PERSONA.IdPersona')
                    ->leftJoin('CONSTANTE as prog', fn($join) =>
                        $join->on('ESTUDIANTE.nProgramaEstudios', '=', 'prog.nConstValor')
                             ->where('prog.nConstGrupo', 'PROGRAMA_ESTUDIO')
                    )
                    ->leftJoin('CONSTANTE as plan', fn($join) =>
                        $join->on('ESTUDIANTE.nPlanEstudio', '=', 'plan.nConstValor')
                             ->where('plan.nConstGrupo', 'PLAN_ESTUDIO')
                    )
                    ->leftJoin('CONSTANTE as mod', fn($join) =>
                        $join->on('ESTUDIANTE.nModuloFormativo', '=', 'mod.nConstValor')
                             ->where('mod.nConstGrupo', 'MODULO_FORMATIVO')
                    )
                    ->leftJoin('CONSTANTE as turno', fn($join) =>
                        $join->on('ESTUDIANTE.nTurno', '=', 'turno.nConstValor')
                             ->where('turno.nConstGrupo', 'TURNO')
                    )
                    ->select(
                        'ESTUDIANTE.*',
                        'PERSONA.cNombre',
                        'PERSONA.cApellido',
                        'PERSONA.cDNI',
                        'prog.nConstDescripcion as ProgramaDescripcion',
                        'plan.nConstDescripcion as PlanDescripcion',
                        'mod.nConstDescripcion as ModuloDescripcion',
                        'turno.nConstDescripcion as TurnoDescripcion'
                    );

                if ($request->filled('dni')) {
                    $query->where('PERSONA.cDNI', 'like', "%{$request->dni}%");
                }
                if ($request->filled('nombre')) {
                    $query->where(DB::raw("CONCAT(PERSONA.cNombre,' ',PERSONA.cApellido)"), 'like', "%{$request->nombre}%");
                }
                if ($request->filled('programa')) {
                    $query->where('ESTUDIANTE.nProgramaEstudios', $request->programa);
                }
                if ($request->filled('plan')) {
                    $query->where('ESTUDIANTE.nPlanEstudio', $request->plan);
                }
                if ($request->filled('modulo')) {
                    $query->where('ESTUDIANTE.nModuloFormativo', $request->modulo);
                }
                if ($request->filled('turno')) {
                    $query->where('ESTUDIANTE.nTurno', $request->turno);
                }

                $resultados = $query->get();
                break;

            case 'supervisiones':
                $query = DB::table('SUPERVISION')
                    ->join('DOCENTE', 'SUPERVISION.IdDocente', '=', 'DOCENTE.IdDocente')
                    ->join('CARTA_PRESENTACION', 'SUPERVISION.IdCartaPresentacion', '=', 'CARTA_PRESENTACION.IdCartaPresentacion')
                    ->join('ESTUDIANTE', 'CARTA_PRESENTACION.IdEstudiante', '=', 'ESTUDIANTE.IdEstudiante')
                    ->join('PERSONA', 'ESTUDIANTE.IdPersona', '=', 'PERSONA.IdPersona')
                    ->select(
                        'SUPERVISION.*',
                        'DOCENTE.IdPersona as DocenteId',
                        'PERSONA.cNombre as EstudianteNombre',
                        'PERSONA.cApellido as EstudianteApellido'
                    );

                if ($request->filled('docente')) {
                    $query->where('DOCENTE.IdPersona', $request->docente);
                }
                if ($request->filled('estudiante')) {
                    $query->where(DB::raw("CONCAT(PERSONA.cNombre,' ',PERSONA.cApellido)"), 'like', "%{$request->estudiante}%");
                }
                if ($request->filled('empresa')) {
                    $query->where('CARTA_PRESENTACION.IdEmpresa', $request->empresa);
                }
                if ($request->filled('fecha_inicio')) {
                    $query->where('SUPERVISION.dFechaInicio', '>=', $request->fecha_inicio);
                }
                if ($request->filled('fecha_fin')) {
                    $query->where('SUPERVISION.dFechaFin', '<=', $request->fecha_fin);
                }

                $resultados = $query->get();
                break;

            case 'cartas':
                $query = DB::table('CARTA_PRESENTACION')
                    ->join('ESTUDIANTE', 'CARTA_PRESENTACION.IdEstudiante', '=', 'ESTUDIANTE.IdEstudiante')
                    ->join('PERSONA', 'ESTUDIANTE.IdPersona', '=', 'PERSONA.IdPersona')
                    ->select('CARTA_PRESENTACION.*', 'PERSONA.cNombre', 'PERSONA.cApellido');

                if ($request->filled('estudiante_carta')) {
                    $query->where(DB::raw("CONCAT(PERSONA.cNombre,' ',PERSONA.cApellido)"), 'like', "%{$request->estudiante_carta}%");
                }
                if ($request->filled('empresa_carta')) {
                    $query->where('CARTA_PRESENTACION.IdEmpresa', $request->empresa_carta);
                }
                if ($request->filled('estado')) {
                    $query->where('CARTA_PRESENTACION.nEstado', $request->estado);
                }
                if ($request->filled('fecha_inicio_carta')) {
                    $query->where('CARTA_PRESENTACION.dFechaCarta', '>=', $request->fecha_inicio_carta);
                }
                if ($request->filled('fecha_fin_carta')) {
                    $query->where('CARTA_PRESENTACION.dFechaCarta', '<=', $request->fecha_fin_carta);
                }

                $resultados = $query->get();
                break;

            case 'empresas':
                $query = DB::table('EMPRESA')->select('*');

                if ($request->filled('nombre_empresa')) {
                    $query->where('cNombreEmpresa', 'like', "%{$request->nombre_empresa}%");
                }
                if ($request->filled('ruc')) {
                    $query->where('nRUC', 'like', "%{$request->ruc}%");
                }
                if ($request->filled('representante')) {
                    $query->where('nRepresentanteLegal', 'like', "%{$request->representante}%");
                }

                $resultados = $query->get();
                break;

            case 'documentos':
                $query = DB::table('DOCUMENTO')
                    ->join('DOCUMENTO_CARTA', 'DOCUMENTO.IdDocumento', '=', 'DOCUMENTO_CARTA.IdDocumento')
                    ->join('CARTA_PRESENTACION', 'DOCUMENTO_CARTA.IdCartaPresentacion', '=', 'CARTA_PRESENTACION.IdCartaPresentacion')
                    ->join('ESTUDIANTE', 'CARTA_PRESENTACION.IdEstudiante', '=', 'ESTUDIANTE.IdEstudiante')
                    ->join('PERSONA', 'ESTUDIANTE.IdPersona', '=', 'PERSONA.IdPersona')
                    ->select('DOCUMENTO.*', 'PERSONA.cNombre as EstudianteNombre', 'PERSONA.cApellido as EstudianteApellido');

                if ($request->filled('tipo_documento')) {
                    $query->where('DOCUMENTO.cTipoDocumento', $request->tipo_documento);
                }
                if ($request->filled('estudiante_doc')) {
                    $query->where(DB::raw("CONCAT(PERSONA.cNombre,' ',PERSONA.cApellido)"), 'like', "%{$request->estudiante_doc}%");
                }
                if ($request->filled('carta_doc')) {
                    $query->where('DOCUMENTO_CARTA.IdCartaPresentacion', $request->carta_doc);
                }
                if ($request->filled('fecha_inicio_doc')) {
                    $query->where('DOCUMENTO.dFechaDocumento', '>=', $request->fecha_inicio_doc);
                }
                if ($request->filled('fecha_fin_doc')) {
                    $query->where('DOCUMENTO.dFechaDocumento', '<=', $request->fecha_fin_doc);
                }

                $resultados = $query->get();
                break;
        }

        return $resultados;
    }
}










