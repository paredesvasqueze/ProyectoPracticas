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

        $view = match ($tipo) {
            'estudiantes' => 'reportes_pdf.estudiantes_pdf',
            'supervisiones' => 'reportes_pdf.supervisiones_pdf',
            'cartas' => 'reportes_pdf.cartas_pdf',
            'empresas' => 'reportes_pdf.empresas_pdf',
            'documentos' => 'reportes_pdf.documentos_pdf',
            default => 'reportes.show',
        };

        // Formato horizontal del PDF
        $orientation = in_array($tipo, ['estudiantes','supervisiones', 'cartas', 'empresas', 'documentos']) ? 'landscape' : 'portrait';

        $pdf = Pdf::loadView($view, compact('resultados', 'filtros'))
                ->setPaper('a4', $orientation);

        return $pdf->stream("reporte_{$tipo}.pdf");
    }

    /**
     * Filtra los datos según tipo y filtros del request.
     */
    private function filtrarDatos($tipo, Request $request)
    {
        $resultados = collect();

        switch ($tipo) {
        
            // =====================================================
            //  REPORTE DE ESTUDIANTES
            // =====================================================
            case 'estudiantes':
                $query = DB::table('ESTUDIANTE')
                    ->join('PERSONA', 'ESTUDIANTE.IdPersona', '=', 'PERSONA.IdPersona')
                    ->leftJoin('CONSTANTE as PROGRAMA', function ($join) {
                        $join->on('ESTUDIANTE.nProgramaEstudios', '=', 'PROGRAMA.nConstValor');
                    })
                    ->leftJoin('CONSTANTE as PLAN', function ($join) {
                        $join->on('ESTUDIANTE.nPlanEstudio', '=', 'PLAN.nConstValor');
                    })
                    ->leftJoin('CONSTANTE as MODULO', function ($join) {
                        $join->on('ESTUDIANTE.nModuloFormativo', '=', 'MODULO.nConstValor');
                    })
                    ->leftJoin('CONSTANTE as TURNO', function ($join) {
                        $join->on('ESTUDIANTE.nTurno', '=', 'TURNO.nConstValor');
                    })
                    ->where('PROGRAMA.nConstGrupo', '=', 'PROGRAMA_ESTUDIO')
                    ->where('PLAN.nConstGrupo', '=', 'PLAN_ESTUDIO')
                    ->where('MODULO.nConstGrupo', '=', 'MODULO_FORMATIVO')
                    ->where('TURNO.nConstGrupo', '=', 'TURNO')
                    ->select(
                        'ESTUDIANTE.IdEstudiante',
                        'PERSONA.cNombre',
                        'PERSONA.cApellido',
                        'PERSONA.cDNI',
                        'PROGRAMA.nConstDescripcion as ProgramaDescripcion',
                        'PLAN.nConstDescripcion as PlanDescripcion',
                        'MODULO.nConstDescripcion as ModuloDescripcion',
                        'TURNO.nConstDescripcion as TurnoDescripcion'
                    );

                // ===== FILTROS =====
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

            // =====================================================
            //  REPORTE DE SUPERVISIONES
            // =====================================================
            case 'supervisiones':
                $query = DB::table('SUPERVISION')
                    ->join('SUPERVISION_DETALLE', 'SUPERVISION.IdSupervision', '=', 'SUPERVISION_DETALLE.IdSupervision')
                    ->join('DOCENTE', 'SUPERVISION.IdDocente', '=', 'DOCENTE.IdDocente')
                    ->join('PERSONA as PDOC', 'DOCENTE.IdPersona', '=', 'PDOC.IdPersona')
                    ->join('CARTA_PRESENTACION', 'SUPERVISION.IdCartaPresentacion', '=', 'CARTA_PRESENTACION.IdCartaPresentacion')
                    ->join('EMPRESA', 'CARTA_PRESENTACION.IdEmpresa', '=', 'EMPRESA.IdEmpresa')
                    ->join('ESTUDIANTE', 'CARTA_PRESENTACION.IdEstudiante', '=', 'ESTUDIANTE.IdEstudiante')
                    ->join('PERSONA as PEST', 'ESTUDIANTE.IdPersona', '=', 'PEST.IdPersona')
                    
                    ->leftJoin('CONSTANTE as EST', function ($join) {
                        $join->on('SUPERVISION.nEstado', '=', 'EST.nConstValor')
                            ->where('EST.nConstGrupo', '=', 'ESTADO_SUPERVISION');
                    })
                    ->leftJoin('CONSTANTE as OFI', function ($join) {
                        $join->on('SUPERVISION.nOficina', '=', 'OFI.nConstValor')
                            ->where('OFI.nConstGrupo', '=', 'OFICINA');
                    })
                    ->select(
                        'SUPERVISION_DETALLE.nNroSupervision',
                        'SUPERVISION_DETALLE.dFechaSupervision',
                        'SUPERVISION.nNota',
                        'SUPERVISION.dFechaInicio',
                        'SUPERVISION.dFechaFin',
                        'SUPERVISION.nHoras',
                        'EST.nConstDescripcion as EstadoDescripcion',
                        'OFI.nConstDescripcion as OficinaDescripcion',
                        'PDOC.cNombre as DocenteNombre',
                        'PDOC.cApellido as DocenteApellido',
                        'PEST.cNombre as EstudianteNombre',
                        'PEST.cApellido as EstudianteApellido',
                        'EMPRESA.cNombreEmpresa as EmpresaNombre',
                        'EMPRESA.cDireccion as EmpresaDireccion'
                    );

                // ===============================
                // Filtros dinámicos
                // ===============================
                if ($request->filled('docente')) {
                    $query->where(DB::raw("CONCAT(PDOC.cNombre,' ',PDOC.cApellido)"), 'like', "%{$request->docente}%");
                }
                if ($request->filled('estudiante')) {
                    $query->where(DB::raw("CONCAT(PEST.cNombre,' ',PEST.cApellido)"), 'like', "%{$request->estudiante}%");
                }
                if ($request->filled('empresa')) {
                    $query->where('EMPRESA.cNombreEmpresa', 'like', "%{$request->empresa}%");
                }
                if ($request->filled('fecha_inicio')) {
                    $query->where('SUPERVISION_DETALLE.dFechaSupervision', '>=', $request->fecha_inicio);
                }
                if ($request->filled('fecha_fin')) {
                    $query->where('SUPERVISION_DETALLE.dFechaSupervision', '<=', $request->fecha_fin);
                }
                if ($request->filled('nro_supervision')) {
                    $query->where('SUPERVISION_DETALLE.nNroSupervision', 'like', "%{$request->nro_supervision}%");
                }
                if ($request->filled('horas')) {
                    $query->where('SUPERVISION.nHoras', '=', $request->horas);
                }
                if ($request->filled('estado')) {
                    $query->where('SUPERVISION.nEstado', '=', $request->estado);
                }
                if ($request->filled('oficina')) {
                    $query->where('SUPERVISION.nOficina', '=', $request->oficina);
                }

                $resultados = $query->get();
                break;

            // =====================================================
            //  REPORTE DE CARTAS DE PRESENTACIÓN
            // =====================================================
            case 'cartas':
                $query = DB::table('CARTA_PRESENTACION')
                    ->join('ESTUDIANTE', 'CARTA_PRESENTACION.IdEstudiante', '=', 'ESTUDIANTE.IdEstudiante')
                    ->join('PERSONA', 'ESTUDIANTE.IdPersona', '=', 'PERSONA.IdPersona')
                    ->join('EMPRESA', 'CARTA_PRESENTACION.IdEmpresa', '=', 'EMPRESA.IdEmpresa')
                    ->leftJoin('CONSTANTE as ESTADO', function ($join) {
                        $join->on('CARTA_PRESENTACION.nEstado', '=', 'ESTADO.nConstValor')
                            ->where('ESTADO.nConstGrupo', '=', 'ESTADO_CARTA');
                    })
                    ->leftJoin('CONSTANTE as PROGRAMA', function ($join) {
                        $join->on('ESTUDIANTE.nProgramaEstudios', '=', 'PROGRAMA.nConstValor')
                            ->where('PROGRAMA.nConstGrupo', '=', 'PROGRAMA_ESTUDIO');
                    })
                    ->leftJoin('CONSTANTE as PLAN', function ($join) {
                        $join->on('ESTUDIANTE.nPlanEstudio', '=', 'PLAN.nConstValor')
                            ->where('PLAN.nConstGrupo', '=', 'PLAN_ESTUDIO');
                    })
                    ->leftJoin('CONSTANTE as MODULO', function ($join) {
                        $join->on('ESTUDIANTE.nModuloFormativo', '=', 'MODULO.nConstValor')
                            ->where('MODULO.nConstGrupo', '=', 'MODULO_FORMATIVO');
                    })
                    ->leftJoin('CONSTANTE as TURNO', function ($join) {
                        $join->on('ESTUDIANTE.nTurno', '=', 'TURNO.nConstValor')
                            ->where('TURNO.nConstGrupo', '=', 'TURNO');
                    })
                    ->select(
                        'CARTA_PRESENTACION.IdCartaPresentacion',
                        'CARTA_PRESENTACION.nNroCarta',                  
                        'CARTA_PRESENTACION.dFechaCarta',                
                        'CARTA_PRESENTACION.dFechaRecojo',               
                        'CARTA_PRESENTACION.cObservacion',               
                        'CARTA_PRESENTACION.nEstado',
                        'CARTA_PRESENTACION.bPresentoSupervision',
                        'ESTADO.nConstDescripcion as EstadoDescripcion',
                        'PERSONA.cNombre',
                        'PERSONA.cApellido',
                        'EMPRESA.cNombreEmpresa',
                        'EMPRESA.cDireccion',
                        'PROGRAMA.nConstDescripcion as ProgramaEstudios',
                        'PLAN.nConstDescripcion as PlanEstudios',
                        'MODULO.nConstDescripcion as ModuloFormativo',
                        'TURNO.nConstDescripcion as Turno'
                    );

                // ======== FILTROS ========

                if ($request->filled('estudiante_carta')) {
                    $query->where(DB::raw("CONCAT(PERSONA.cNombre,' ',PERSONA.cApellido)"), 'like', "%{$request->estudiante_carta}%");
                }

                if ($request->filled('empresa_carta')) {
                    $query->where('EMPRESA.cNombreEmpresa', 'like', "%{$request->empresa_carta}%");
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

                if ($request->filled('estado')) {
                    $query->where('CARTA_PRESENTACION.nEstado', $request->estado);
                }

                if ($request->filled('fecha_inicio_carta')) {
                    $query->where('CARTA_PRESENTACION.dFechaCarta', '>=', $request->fecha_inicio_carta);
                }

                if ($request->filled('fecha_fin_carta')) {
                    $query->where('CARTA_PRESENTACION.dFechaCarta', '<=', $request->fecha_fin_carta);
                }

                if ($request->filled('presento_supervision')) {
                    $query->where('CARTA_PRESENTACION.bPresentoSupervision', $request->presento_supervision);
                }

                $resultados = $query->get();
                break;

            // =====================================================
            //  REPORTE DE EMPRESAS
            // =====================================================
            case 'empresas':
                $query = DB::table('EMPRESA')
                    ->leftJoin('CONSTANTE as TIPO', function ($join) {
                        $join->on('EMPRESA.nTipoEmpresa', '=', 'TIPO.nConstValor')
                            ->where('TIPO.nConstGrupo', '=', 'TIPO_EMPRESA');
                    })
                    ->leftJoin('CONSTANTE as PROF', function ($join) {
                        $join->on('EMPRESA.nProfesion', '=', 'PROF.nConstValor')
                            ->where('PROF.nConstGrupo', '=', 'PROFESION');
                    })
                    ->leftJoin('CONSTANTE as CARGO', function ($join) {
                        $join->on('EMPRESA.nCargo', '=', 'CARGO.nConstValor')
                            ->where('CARGO.nConstGrupo', '=', 'CARGO');
                    })
                    ->select(
                        'EMPRESA.IdEmpresa',
                        'EMPRESA.cNombreEmpresa',
                        'EMPRESA.nRUC',
                        'EMPRESA.cDireccion',
                        'EMPRESA.cCorreo',
                        'EMPRESA.nTelefono',
                        'EMPRESA.nRepresentanteLegal',
                        'TIPO.nConstDescripcion as TipoEmpresa',
                        'PROF.nConstDescripcion as Profesion',
                        'CARGO.nConstDescripcion as Cargo'
                    );

                // Filtro por Tipo de Empresa
                if ($request->filled('tipo_empresa')) {
                    $query->where('EMPRESA.nTipoEmpresa', '=', $request->tipo_empresa);
                }

                $resultados = $query->orderBy('EMPRESA.cNombreEmpresa')->get();
                break;

        }

        return $resultados;

    }
}
