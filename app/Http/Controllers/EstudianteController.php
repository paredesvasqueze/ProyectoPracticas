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

        if ($request->has('dni') && !empty($request->dni)) {
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
        $programas = Constante::where('nConstGrupo', 'PROGRAMA_ESTUDIO')
            ->where('nConstEstado', 1)
            ->orderBy('nConstOrden')
            ->get();

        $planes = Constante::where('nConstGrupo', 'PLAN_ESTUDIO')
            ->where('nConstEstado', 1)
            ->orderBy('nConstOrden')
            ->get();

        $modulos = Constante::where('nConstGrupo', 'MODULO_FORMATIVO')
            ->where('nConstEstado', 1)
            ->orderBy('nConstOrden')
            ->get();

        $turnos = Constante::where('nConstGrupo', 'TURNO')
            ->where('nConstEstado', 1)
            ->orderBy('nConstOrden')
            ->get();

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
        ]);

        $persona = Persona::create([
            'cNombre'   => $request->cNombre,
            'cApellido' => $request->cApellido,
            'cDNI'      => $request->cDNI,
            'cCorreo'   => $request->cCorreo,
        ]);

        Estudiante::create([
            'IdPersona'         => $persona->IdPersona,
            'nProgramaEstudios' => $request->nProgramaEstudios,
            'nPlanEstudio'      => $request->nPlanEstudio,
            'nModuloFormativo'  => $request->nModuloFormativo,
            'nCelular'          => $request->nCelular,
            'nTurno'            => $request->nTurno,
        ]);

        return redirect()->route('estudiantes.index')->with('success', 'Estudiante registrado correctamente.');
    }

    // Formulario de edición
    public function edit($id)
    {
        $estudiante = Estudiante::with('persona')->findOrFail($id);

        $programas = Constante::where('nConstGrupo', 'PROGRAMA_ESTUDIO')
            ->where('nConstEstado', 1)
            ->orderBy('nConstOrden')
            ->get();

        $planes = Constante::where('nConstGrupo', 'PLAN_ESTUDIO')
            ->where('nConstEstado', 1)
            ->orderBy('nConstOrden')
            ->get();

        $modulos = Constante::where('nConstGrupo', 'MODULO_FORMATIVO')
            ->where('nConstEstado', 1)
            ->orderBy('nConstOrden')
            ->get();

        $turnos = Constante::where('nConstGrupo', 'TURNO')
            ->where('nConstEstado', 1)
            ->orderBy('nConstOrden')
            ->get();

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
        ]);

        $estudiante->persona->update([
            'cNombre'   => $request->cNombre,
            'cApellido' => $request->cApellido,
            'cDNI'      => $request->cDNI,
            'cCorreo'   => $request->cCorreo,
        ]);

        $estudiante->update([
            'nProgramaEstudios' => $request->nProgramaEstudios,
            'nPlanEstudio'      => $request->nPlanEstudio,
            'nModuloFormativo'  => $request->nModuloFormativo,
            'nCelular'          => $request->nCelular,
            'nTurno'            => $request->nTurno,
        ]);

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
}








