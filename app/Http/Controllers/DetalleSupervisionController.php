<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supervision;
use App\Models\SupervisionDetalle;

class DetalleSupervisionController extends Controller
{
    /**
     * Mostrar listado de supervisiones detalle.
     */
    public function index()
    { 
        $detalles = SupervisionDetalle::with('supervision.docente')
            ->orderBy('IdSupervisionDetalle', 'desc')
            ->paginate(10); 

        return view('detalle_supervisiones.index', compact('detalles'));
    }

    /**
     * Mostrar formulario de creación.
     */
    public function create()
    {
        $supervisiones = Supervision::all();
        return view('detalle_supervisiones.create', compact('supervisiones'));
    }

    /**
     * Guardar un nuevo detalle.
     */
    public function store(Request $request)
    {
        $request->validate([
            'IdSupervision'     => 'required|exists:SUPERVISION,IdSupervision',
            'nNroSupervision'   => 'required|string|max:20',
            'dFechaSupervision' => 'required|date',
        ]);

        SupervisionDetalle::create($request->all());

        return redirect()
            ->route('detalle_supervisiones.index')
            ->with('success', 'Detalle de supervisión creado correctamente.');
    }

    /**
     * Mostrar un detalle específico.
     */
    public function show($id)
    {
        $detalle = SupervisionDetalle::with('supervision.docente')->findOrFail($id);
        return view('detalle_supervisiones.show', compact('detalle'));
    }

    /**
     * Mostrar formulario de edición.
     */
    public function edit($id)
    {
        $detalle = SupervisionDetalle::findOrFail($id);
        $supervisiones = Supervision::all();
        return view('detalle_supervisiones.edit', compact('detalle', 'supervisiones'));
    }

    /**
     * Actualizar un detalle.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'IdSupervision'     => 'required|exists:SUPERVISION,IdSupervision',
            'nNroSupervision'   => 'required|string|max:20',
            'dFechaSupervision' => 'required|date',
        ]);

        $detalle = SupervisionDetalle::findOrFail($id);
        $detalle->update($request->all());

        return redirect()
            ->route('detalle_supervisiones.index')
            ->with('success', 'Detalle de supervisión actualizado correctamente.');
    }

    /**
     * Eliminar un detalle.
     */
    public function destroy($id)
    {
        $detalle = SupervisionDetalle::findOrFail($id);
        $detalle->delete();

        return redirect()
            ->route('detalle_supervisiones.index')
            ->with('success', 'Detalle de supervisión eliminado correctamente.');
    }
}




