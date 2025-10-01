<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Docente;
use App\Models\Persona;
use App\Models\Constante;

class DocenteController extends Controller
{
    /**
     * Listar todos los docentes con opción de búsqueda por DNI, nombre o apellido.
     */
    public function index(Request $request)
    {
        $query = Docente::with('persona');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->whereHas('persona', function ($q) use ($search) {
                $q->where('cDNI', 'like', "%{$search}%")
                  ->orWhere('cNombre', 'like', "%{$search}%")
                  ->orWhere('cApellido', 'like', "%{$search}%");
            });
        }

        $docentes = $query->paginate(10);

        return view('docentes.index', compact('docentes'));
    }

    /**
     * Mostrar formulario para crear un nuevo docente.
     */
    public function create()
    {
        $programas = Constante::where('nConstGrupo', 'PROGRAMA_ESTUDIO')
            ->where('nConstEstado', '1')
            ->orderBy('nConstOrden', 'ASC')
            ->get();

        return view('docentes.create', compact('programas'));
    }

    /**
     * Guardar un nuevo docente en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'cNombre'           => 'required|string|max:100',
            'cApellido'         => 'required|string|max:100',
            'cDNI'              => 'required|string|max:20|unique:PERSONA,cDNI',
            'cCorreo'           => 'nullable|email|max:100',
            'nProgramaEstudios' => 'required|string|max:50',
        ]);

        $persona = Persona::create($request->only(['cNombre', 'cApellido', 'cDNI', 'cCorreo']));

        Docente::create([
            'IdPersona'         => $persona->IdPersona,
            'nProgramaEstudios' => $request->nProgramaEstudios,
        ]);

        return redirect()->route('docentes.index')->with('success', 'Docente registrado correctamente.');
    }

    /**
     * Mostrar un docente específico.
     */
    public function show($id)
    {
        $docente = Docente::with('persona')->findOrFail($id);
        return view('docentes.show', compact('docente'));
    }

    /**
     * Mostrar formulario de edición.
     */
    public function edit($id)
    {
        $docente = Docente::with('persona')->findOrFail($id);

        $programas = Constante::where('nConstGrupo', 'PROGRAMA_ESTUDIO')
            ->where('nConstEstado', '1')
            ->orderBy('nConstOrden', 'ASC')
            ->get();

        return view('docentes.edit', compact('docente', 'programas'));
    }

    /**
     * Actualizar un docente en la base de datos.
     */
    public function update(Request $request, $id)
    {
        $docente = Docente::findOrFail($id);
        $persona = $docente->persona;

        $request->validate([
            'cNombre'           => 'required|string|max:100',
            'cApellido'         => 'required|string|max:100',
            'cDNI'              => 'required|string|max:20|unique:PERSONA,cDNI,' . $persona->IdPersona . ',IdPersona',
            'cCorreo'           => 'nullable|email|max:100',
            'nProgramaEstudios' => 'required|string|max:50',
        ]);

        $persona->update($request->only(['cNombre', 'cApellido', 'cDNI', 'cCorreo']));
        $docente->update(['nProgramaEstudios' => $request->nProgramaEstudios]);

        return redirect()->route('docentes.index')->with('success', 'Docente actualizado correctamente.');
    }

    /**
     * Eliminar un docente y su persona asociada.
     */
    public function destroy($id)
    {
        $docente = Docente::findOrFail($id);

        if ($docente->persona) {
            $docente->persona->delete();
        }

        $docente->delete();

        return redirect()->route('docentes.index')->with('success', 'Docente eliminado correctamente.');
    }
}



