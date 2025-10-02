<?php

namespace App\Http\Controllers;

use App\Models\Supervision;
use App\Models\Docente;
use App\Models\CartaPresentacion;
use Illuminate\Http\Request;

class SupervisionController extends Controller
{
    /**
     * Listar todas las supervisiones con opción de búsqueda
     */
    public function index(Request $request)
    {
        $query = Supervision::with(['docente.persona', 'cartaPresentacion']);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function($q) use ($search) {
                $q->whereHas('docente.persona', function($q2) use ($search) {
                    $q2->where('cNombre', 'like', "%{$search}%")
                       ->orWhere('cApellido', 'like', "%{$search}%");
                })
                ->orWhereHas('cartaPresentacion', function($q3) use ($search) {
                    $q3->where('nNroCarta', 'like', "%{$search}%");
                });
            });
        }

        $supervisiones = $query->orderBy('IdSupervision', 'desc')->get();

        return view('supervisiones.index', compact('supervisiones'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $docentes = Docente::with('persona')->get();
        $cartas = CartaPresentacion::with('estudiante.persona')->get();

        $supervisiones = Supervision::with(['docente.persona', 'cartaPresentacion'])
                                    ->orderBy('IdSupervision', 'desc')
                                    ->get();

        return view('supervisiones.create', compact('docentes', 'cartas', 'supervisiones'));
    }

    /**
     * Guardar nueva supervisión con detalles
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

            // Validación de detalles como arreglo
            'detalles' => 'required|array|min:1',
            'detalles.*.nNroSupervision' => 'required|integer|min:1',
            'detalles.*.dFechaSupervision' => 'required|date',
        ]);

        // Crear supervisión principal
        $supervision = Supervision::create([
            'IdDocente' => $request->IdDocente,
            'IdCartaPresentacion' => $request->IdCartaPresentacion,
            'nNota' => $request->nNota,
            'dFechaInicio' => $request->dFechaInicio,
            'dFechaFin' => $request->dFechaFin,
            'nHoras' => $request->nHoras,
        ]);

        // Crear detalles de supervisión
        foreach ($request->detalles as $detalle) {
            $supervision->detalles()->create([
                'nNroSupervision' => $detalle['nNroSupervision'],
                'dFechaSupervision' => $detalle['dFechaSupervision'],
            ]);
        }

        return redirect()->route('supervisiones.create')
                         ->with('success', 'Supervisión registrada correctamente.');
    }

    /**
     * Mostrar una supervisión
     */
    public function show($id)
    {
        $supervision = Supervision::with(['docente.persona', 'cartaPresentacion', 'detalles'])
                                  ->findOrFail($id);
        return view('supervisiones.show', compact('supervision'));
    }

    /**
     * Formulario de edición
     */
    public function edit($id)
    {
        $supervision = Supervision::with('detalles')->findOrFail($id);
        $docentes = Docente::with('persona')->get();
        $cartas = CartaPresentacion::all();

        return view('supervisiones.edit', compact('supervision', 'docentes', 'cartas'));
    }

    /**
     * Actualizar supervisión y detalles
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

            'detalles' => 'required|array|min:1',
            'detalles.*.nNroSupervision' => 'required|integer|min:1',
            'detalles.*.dFechaSupervision' => 'required|date',
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

        // Actualizar detalles: primero eliminamos los antiguos
        $supervision->detalles()->delete();

        // Insertamos los nuevos
        foreach ($request->detalles as $detalle) {
            $supervision->detalles()->create([
                'nNroSupervision' => $detalle['nNroSupervision'],
                'dFechaSupervision' => $detalle['dFechaSupervision'],
            ]);
        }

        return redirect()->route('supervisiones.index')
                         ->with('success', 'Supervisión actualizada correctamente.');
    }

    /**
     * Eliminar supervisión y sus detalles
     */
    public function destroy($id)
    {
        $supervision = Supervision::findOrFail($id);
        $supervision->detalles()->delete();
        $supervision->delete();

        return redirect()->route('supervisiones.index')
                         ->with('success', 'Supervisión eliminada correctamente.');
    }
}











