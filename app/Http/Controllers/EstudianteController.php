<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estudiante;
use App\Models\Persona;

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
        return view('estudiantes.create');
    }

    // Guardar estudiante
    public function store(Request $request)
    {
        $request->validate([
            'cNombre' => 'required|string|max:100',
            'cApellido' => 'required|string|max:100',
            'cDNI' => 'required|digits:8|unique:PERSONA,cDNI',
            'cCorreo' => 'required|email|max:100',
            'nProgramaEstudios' => 'required|string|max:100',
        ], [
            'cNombre.required' => 'El nombre es obligatorio.',
            'cApellido.required' => 'El apellido es obligatorio.',
            'cDNI.required' => 'El DNI es obligatorio.',
            'cDNI.digits' => 'El DNI debe tener exactamente 8 números.',
            'cDNI.unique' => 'Este DNI ya está registrado.',
            'cCorreo.required' => 'El correo es obligatorio.',
            'cCorreo.email' => 'El correo no es válido.',
            'nProgramaEstudios.required' => 'El programa de estudios es obligatorio.',
        ]);

        $persona = Persona::create([
            'cNombre' => $request->cNombre,
            'cApellido' => $request->cApellido,
            'cDNI' => $request->cDNI,
            'cCorreo' => $request->cCorreo,
        ]);

        Estudiante::create([
            'IdPersona' => $persona->IdPersona,
            'nProgramaEstudios' => $request->nProgramaEstudios,
        ]);

        return redirect()->route('estudiantes.index')->with('success', 'Estudiante registrado correctamente.');
    }

    // Formulario de edición
    public function edit($id)
    {
        $estudiante = Estudiante::with('persona')->findOrFail($id);
        return view('estudiantes.edit', compact('estudiante'));
    }

    // Actualizar estudiante
    public function update(Request $request, $id)
    {
        $estudiante = Estudiante::with('persona')->findOrFail($id);

        $request->validate([
            'cNombre' => 'required|string|max:100',
            'cApellido' => 'required|string|max:100',
            'cDNI' => 'required|digits:8|unique:PERSONA,cDNI,'.$estudiante->persona->IdPersona.',IdPersona',
            'cCorreo' => 'required|email|max:100',
            'nProgramaEstudios' => 'required|string|max:100',
        ], [
            'cNombre.required' => 'El nombre es obligatorio.',
            'cApellido.required' => 'El apellido es obligatorio.',
            'cDNI.required' => 'El DNI es obligatorio.',
            'cDNI.digits' => 'El DNI debe tener exactamente 8 números.',
            'cDNI.unique' => 'Este DNI ya está registrado.',
            'cCorreo.required' => 'El correo es obligatorio.',
            'cCorreo.email' => 'El correo no es válido.',
            'nProgramaEstudios.required' => 'El programa de estudios es obligatorio.',
        ]);

        $estudiante->persona->update([
            'cNombre' => $request->cNombre,
            'cApellido' => $request->cApellido,
            'cDNI' => $request->cDNI,
            'cCorreo' => $request->cCorreo,
        ]);

        $estudiante->update([
            'nProgramaEstudios' => $request->nProgramaEstudios,
        ]);

        return redirect()->route('estudiantes.index')->with('success', 'Estudiante actualizado correctamente.');
    }

    // Eliminar estudiante
    public function destroy($id)
    {
        $estudiante = Estudiante::findOrFail($id);

        if($estudiante->persona) {
            $estudiante->persona->delete();
        }

        $estudiante->delete();

        return redirect()->route('estudiantes.index')->with('success', 'Estudiante eliminado correctamente.');
    }
}



