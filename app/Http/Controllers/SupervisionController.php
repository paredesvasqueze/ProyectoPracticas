<?php

namespace App\Http\Controllers;

use App\Models\Supervision;
use App\Models\Docente;
use App\Models\CartaPresentacion;
use Illuminate\Http\Request;

class SupervisionController extends Controller
{
    /**
     * Listar todas las supervisiones
     */
    public function index()
    {
        // Incluimos las relaciones Docente->Persona y CartaPresentacion
        $supervisiones = Supervision::with(['docente.persona', 'cartaPresentacion'])->get();
        return view('supervisiones.index', compact('supervisiones')); 
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        // Traemos docentes con su persona
        $docentes = Docente::with('persona')->get();
        $cartas = CartaPresentacion::all();

        return view('supervisiones.create', compact('docentes', 'cartas')); 
    }

    /**
     * Guardar nueva supervisión
     */
    public function store(Request $request)
    {
        $request->validate([
            'IdDocente' => 'required|exists:docente,IdDocente',
            'IdCartaPresentacion' => 'required|exists:carta_presentacion,IdCartaPresentacion',
            'nNota' => 'nullable|numeric|min:0|max:20',
            'dFechaInicio' => 'required|date',
            'dFechaFin' => 'required|date|after_or_equal:dFechaInicio',
            'nHoras' => 'required|integer|min:1',
        ]);

        Supervision::create([
            'IdDocente' => $request->IdDocente,
            'IdCartaPresentacion' => $request->IdCartaPresentacion,
            'nNota' => $request->nNota,
            'dFechaInicio' => $request->dFechaInicio,
            'dFechaFin' => $request->dFechaFin,
            'nHoras' => $request->nHoras,
        ]);

        return redirect()->route('supervisiones.index')
                         ->with('success', 'Supervisión registrada correctamente.');
    }

    /**
     * Mostrar una supervisión
     */
    public function show($id)
    {
        $supervision = Supervision::with(['docente.persona', 'cartaPresentacion'])
                                  ->findOrFail($id);
        return view('supervisiones.show', compact('supervision')); 
    }

    /**
     * Formulario de edición
     */
    public function edit($id)
    {
        $supervision = Supervision::findOrFail($id);
        $docentes = Docente::with('persona')->get();
        $cartas = CartaPresentacion::all();

        return view('supervisiones.edit', compact('supervision', 'docentes', 'cartas')); 
    }

    /**
     * Actualizar supervisión
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'IdDocente' => 'required|exists:docente,IdDocente',
            'IdCartaPresentacion' => 'required|exists:carta_presentacion,IdCartaPresentacion',
            'nNota' => 'nullable|numeric|min:0|max:20',
            'dFechaInicio' => 'required|date',
            'dFechaFin' => 'required|date|after_or_equal:dFechaInicio',
            'nHoras' => 'required|integer|min:1',
        ]);

        $supervision = Supervision::findOrFail($id);
        $supervision->update([
            'IdDocente' => $request->IdDocente,
            'IdCartaPresentacion' => $request->IdCartaPresentacion,
            'nNota' => $request->nNota,
            'dFechaInicio' => $request->dFechaInicio,
            'dFechaFin' => $request->dFechaFin,
            'nHoras' => $request->nHoras,
        ]);

        return redirect()->route('supervisiones.index') 
                         ->with('success', 'Supervisión actualizada correctamente.');
    }

    /**
     * Eliminar supervisión
     */
    public function destroy($id)
    {
        $supervision = Supervision::findOrFail($id);
        $supervision->delete();

        return redirect()->route('supervisiones.index') 
                         ->with('success', 'Supervisión eliminada correctamente.');
    }
}







