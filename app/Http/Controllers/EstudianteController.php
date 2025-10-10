<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estudiante;
use App\Models\Persona;
use App\Models\Constante;
use Illuminate\Support\Facades\DB;

class EstudianteController extends Controller
{
    /**
     * Mostrar listado de estudiantes con búsqueda por DNI o nombre.
     */
    public function index(Request $request)
    {
        $busqueda = $request->input('dni');

        $estudiantes = Estudiante::with(['persona', 'programa', 'plan', 'modulo', 'turno'])
            ->when($busqueda, function ($query, $busqueda) {
                $query->whereHas('persona', function ($q) use ($busqueda) {
                    $q->where('cDNI', 'like', '%' . $busqueda . '%')
                      ->orWhere('cNombre', 'like', '%' . $busqueda . '%')
                      ->orWhere('cApellido', 'like', '%' . $busqueda . '%');
                });
            })
            ->orderByDesc('IdEstudiante')
            ->get();

        return view('estudiantes.index', compact('estudiantes', 'busqueda'));
    }

    /**
     * Formulario de registro de estudiante.
     */
    public function create()
    {
        $programas = Constante::where('nConstGrupo', 'PROGRAMA_ESTUDIO')->where('nConstEstado', 1)->orderBy('nConstOrden')->get();
        $planes    = Constante::where('nConstGrupo', 'PLAN_ESTUDIO')->where('nConstEstado', 1)->orderBy('nConstOrden')->get();
        $modulos   = Constante::where('nConstGrupo', 'MODULO_FORMATIVO')->where('nConstEstado', 1)->orderBy('nConstOrden')->get();
        $turnos    = Constante::where('nConstGrupo', 'TURNO')->where('nConstEstado', 1)->orderBy('nConstOrden')->get();

        return view('estudiantes.create', compact('programas', 'planes', 'modulos', 'turnos'));
    }

    /**
     * Guardar nuevo estudiante.
     */
    public function store(Request $request)
    {
        $request->validate([
            'cNombre'           => 'required|string|max:100',
            'cApellido'         => 'required|string|max:100',
            'cDNI'              => 'required|digits:8|unique:PERSONA,cDNI',
            'cCorreo'           => 'required|email|max:100',
            'nProgramaEstudios' => 'required|integer',
            'nPlanEstudio'      => 'required|integer',
            'nModuloFormativo'  => 'required|integer',
            'nCelular'          => 'required|digits:9',
            'nTurno'            => 'required|integer',
        ]);

        DB::beginTransaction();

        try {
            $persona = Persona::create([
                'cNombre'   => strtoupper($request->cNombre),
                'cApellido' => strtoupper($request->cApellido),
                'cDNI'      => $request->cDNI,
                'cCorreo'   => strtolower($request->cCorreo),
            ]);

            Estudiante::create([
                'IdPersona'         => $persona->IdPersona,
                'nProgramaEstudios' => $request->nProgramaEstudios,
                'nPlanEstudio'      => $request->nPlanEstudio,
                'nModuloFormativo'  => $request->nModuloFormativo,
                'nCelular'          => $request->nCelular,
                'nTurno'            => $request->nTurno,
            ]);

            DB::commit();

            return redirect()->route('estudiantes.index')->with('success', '✅ Estudiante registrado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', '❌ Error al registrar estudiante: ' . $e->getMessage());
        }
    }

    /**
     * Formulario de edición de estudiante.
     */
    public function edit($id)
    {
        $estudiante = Estudiante::with(['persona', 'programa', 'plan', 'modulo', 'turno'])->findOrFail($id);

        $programas = Constante::where('nConstGrupo', 'PROGRAMA_ESTUDIO')->where('nConstEstado', 1)->orderBy('nConstOrden')->get();
        $planes    = Constante::where('nConstGrupo', 'PLAN_ESTUDIO')->where('nConstEstado', 1)->orderBy('nConstOrden')->get();
        $modulos   = Constante::where('nConstGrupo', 'MODULO_FORMATIVO')->where('nConstEstado', 1)->orderBy('nConstOrden')->get();
        $turnos    = Constante::where('nConstGrupo', 'TURNO')->where('nConstEstado', 1)->orderBy('nConstOrden')->get();

        return view('estudiantes.edit', compact('estudiante', 'programas', 'planes', 'modulos', 'turnos'));
    }

    /**
     * Actualizar datos del estudiante.
     */
    public function update(Request $request, $id)
    {
        $estudiante = Estudiante::with('persona')->findOrFail($id);

        $request->validate([
            'cNombre'           => 'required|string|max:100',
            'cApellido'         => 'required|string|max:100',
            'cDNI'              => 'required|digits:8|unique:PERSONA,cDNI,' . $estudiante->persona->IdPersona . ',IdPersona',
            'cCorreo'           => 'required|email|max:100',
            'nProgramaEstudios' => 'required|integer',
            'nPlanEstudio'      => 'required|integer',
            'nModuloFormativo'  => 'required|integer',
            'nCelular'          => 'required|digits:9',
            'nTurno'            => 'required|integer',
        ]);

        DB::beginTransaction();

        try {
            $estudiante->persona->update([
                'cNombre'   => strtoupper($request->cNombre),
                'cApellido' => strtoupper($request->cApellido),
                'cDNI'      => $request->cDNI,
                'cCorreo'   => strtolower($request->cCorreo),
            ]);

            $estudiante->update([
                'nProgramaEstudios' => $request->nProgramaEstudios,
                'nPlanEstudio'      => $request->nPlanEstudio,
                'nModuloFormativo'  => $request->nModuloFormativo,
                'nCelular'          => $request->nCelular,
                'nTurno'            => $request->nTurno,
            ]);

            DB::commit();

            return redirect()->route('estudiantes.index')->with('success', '✅ Estudiante actualizado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', '❌ Error al actualizar estudiante: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar estudiante y su persona asociada.
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $estudiante = Estudiante::with('persona')->findOrFail($id);

            if ($estudiante->persona) {
                $estudiante->persona->delete();
            }

            $estudiante->delete();

            DB::commit();

            return redirect()->route('estudiantes.index')->with('success', '🗑️ Estudiante eliminado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', '❌ Error al eliminar estudiante: ' . $e->getMessage());
        }
    }

    /**
     * 🔍 Método AJAX para búsqueda de estudiantes (para autocompletar).
     */
    public function buscar(Request $request)
    {
        $query = $request->get('q', '');

        $estudiantes = Estudiante::with([
                'persona',
                'programa',
                'modulo',
                'cartaPresentacion.empresa'
            ])
            ->whereHas('persona', function ($q) use ($query) {
                $q->where('cDNI', 'like', '%' . $query . '%')
                  ->orWhere('cNombre', 'like', '%' . $query . '%')
                  ->orWhere('cApellido', 'like', '%' . $query . '%');
            })
            ->limit(20)
            ->get()
            ->map(function ($est) {
                $carta = optional($est->cartaPresentacion);

                return [
                    'id'               => $est->IdEstudiante,
                    'dni'              => optional($est->persona)->cDNI ?? '',
                    'nombre'           => trim((optional($est->persona)->cApellido ?? '') . ' ' . (optional($est->persona)->cNombre ?? '')),
                    'programa'         => optional($est->programa)->nConstDescripcion ?? '',
                    'modulo'           => optional($est->modulo)->nConstDescripcion ?? '',
                    'nro_expediente'   => $carta->nNroExpediente ?? '—',
                    'centro_practicas' => optional($carta->empresa)->cNombreEmpresa ?? '—',
                    'estado'           => match ($carta->nEstado ?? 0) {
                        1 => 'Pendiente',
                        2 => 'Enviado',
                        3 => 'Aprobado',
                        default => 'Pendiente',
                    },
                ];
            });

        return response()->json($estudiantes);
    }
}




