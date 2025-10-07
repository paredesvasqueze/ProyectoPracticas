<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estudiante;
use App\Models\Persona;
use App\Models\Constante;

class EstudianteController extends Controller
{
    // Mostrar listado de estudiantes con opción de búsqueda por DNI
    public function index(Request $request)
    {
        $query = Estudiante::with('persona');

        if ($request->filled('dni')) {
            $query->whereHas('persona', function($q) use ($request) {
                $q->where('cDNI', 'like', '%' . $request->dni . '%');
            });
        }

        $estudiantes = $query->get();
        return view('estudiantes.index', compact('estudiantes'));
    }

    // Formulario de registro
    public function create()
    {
        $programas = $this->getConstantes('PROGRAMA_ESTUDIO');
        $planes    = $this->getConstantes('PLAN_ESTUDIO');
        $modulos   = $this->getConstantes('MODULO_FORMATIVO');
        $turnos    = $this->getConstantes('TURNO');

        return view('estudiantes.create', compact('programas', 'planes', 'modulos', 'turnos'));
    }

    // Guardar estudiante
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
            'cCentroPracticas'  => 'nullable|string|max:255',
        ]);

        $persona = Persona::create($request->only('cNombre', 'cApellido', 'cDNI', 'cCorreo'));

        Estudiante::create([
            'IdPersona'         => $persona->IdPersona,
            'nProgramaEstudios' => $request->nProgramaEstudios,
            'nPlanEstudio'      => $request->nPlanEstudio,
            'nModuloFormativo'  => $request->nModuloFormativo,
            'nCelular'          => $request->nCelular,
            'nTurno'            => $request->nTurno,
            'cCentroPracticas'  => $request->cCentroPracticas,
        ]);

        return redirect()->route('estudiantes.index')->with('success', 'Estudiante registrado correctamente.');
    }

    // Formulario de edición
    public function edit($id)
    {
        $estudiante = Estudiante::with('persona')->findOrFail($id);
        $programas  = $this->getConstantes('PROGRAMA_ESTUDIO');
        $planes     = $this->getConstantes('PLAN_ESTUDIO');
        $modulos    = $this->getConstantes('MODULO_FORMATIVO');
        $turnos     = $this->getConstantes('TURNO');

        return view('estudiantes.edit', compact('estudiante', 'programas', 'planes', 'modulos', 'turnos'));
    }

    // Actualizar estudiante
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
            'cCentroPracticas'  => 'nullable|string|max:255',
        ]);

        $estudiante->persona->update($request->only('cNombre', 'cApellido', 'cDNI', 'cCorreo'));

        $estudiante->update($request->only(
            'nProgramaEstudios',
            'nPlanEstudio',
            'nModuloFormativo',
            'nCelular',
            'nTurno',
            'cCentroPracticas'
        ));

        return redirect()->route('estudiantes.index')->with('success', 'Estudiante actualizado correctamente.');
    }

    // Eliminar estudiante
    public function destroy($id)
    {
        $estudiante = Estudiante::findOrFail($id);

        if ($estudiante->persona) {
            $estudiante->persona->delete();
        }

        $estudiante->delete();
        return redirect()->route('estudiantes.index')->with('success', 'Estudiante eliminado correctamente.');
    }

    // ==============================
    // Autocompletado AJAX
    // ==============================
    public function buscarPersona(Request $request)
    {
        $term = $request->get('term', '');

        $resultados = Estudiante::with('persona')
            ->whereHas('persona', function ($q) use ($term) {
                $q->where('cNombre', 'LIKE', "%{$term}%")
                  ->orWhere('cApellido', 'LIKE', "%{$term}%")
                  ->orWhere('cDNI', 'LIKE', "%{$term}%");
            })
            ->limit(10)
            ->get()
            ->map(function ($est) {
                return [
                    'id'               => $est->IdEstudiante,
                    'nombre'           => $est->persona->cNombre . ' ' . $est->persona->cApellido,
                    'dni'              => $est->persona->cDNI,
                    'programa'         => $est->programa_nombre ?? '', // asegúrate de accesor
                    'modulo'           => $est->modulo_nombre ?? '',
                    'nro_expediente'   => $est->nro_expediente ?? '',
                    'centro_practicas' => $est->cCentroPracticas ?? '',
                    'text'             => $est->persona->cNombre . ' ' . $est->persona->cApellido . ' (' . $est->persona->cDNI . ')',
                ];
            });

        return response()->json($resultados);
    }

    // ==============================
    // Función auxiliar para constantes
    // ==============================
    private function getConstantes($grupo)
    {
        return Constante::where('nConstGrupo', $grupo)
                        ->where('nConstEstado', 1)
                        ->orderBy('nConstOrden')
                        ->get();
    }
}












