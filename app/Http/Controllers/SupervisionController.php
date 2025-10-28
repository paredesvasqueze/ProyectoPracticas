<?php

namespace App\Http\Controllers;

use App\Models\Supervision;
use App\Models\Docente;
use App\Models\CartaPresentacion;
use App\Models\Constante;
use Illuminate\Http\Request;

class SupervisionController extends Controller
{
    /**
     * Listar todas las supervisiones con opción de búsqueda
     */
    public function index(Request $request)
    {
        $query = Supervision::with([
            'docente.persona',
            'cartaPresentacion.estudiante.persona',
            'detalles'
        ]);

        // Búsqueda por nombre del docente o número de carta
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('docente.persona', function ($sub) use ($search) {
                    $sub->where('cNombre', 'like', "%{$search}%")
                        ->orWhere('cApellido', 'like', "%{$search}%");
                })
                ->orWhereHas('cartaPresentacion', function ($sub) use ($search) {
                    $sub->where('nNroCarta', 'like', "%{$search}%");
                });
            });
        }

        // Orden correcto: más recientes primero
        $supervisiones = $query->orderByDesc('IdSupervision')->get();

        // Obtener nombres legibles de estados y oficinas
        $estados = Constante::where('nConstGrupo', 'ESTADO_SUPERVISION')->pluck('nConstDescripcion', 'nConstValor');
        $oficinas = Constante::where('nConstGrupo', 'OFICINA')->pluck('nConstDescripcion', 'nConstValor');

        // Asignar nombres legibles a cada registro
        foreach ($supervisiones as $item) {
            $item->estado_nombre = $estados[(string)$item->nEstado] ?? '—';
            $item->oficina_nombre = $oficinas[(string)$item->nOficina] ?? '—';
        }

        return view('supervisiones.index', compact('supervisiones'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $docentes = Docente::with('persona')->get();
        $cartas = CartaPresentacion::with('estudiante.persona')->get();

        $estados = Constante::where('nConstGrupo', 'ESTADO_SUPERVISION')
            ->where('nConstEstado', 1)
            ->orderBy('nConstOrden')
            ->get();

        $oficinas = Constante::where('nConstGrupo', 'OFICINA')
            ->where('nConstEstado', 1)
            ->orderBy('nConstOrden')
            ->get();

        return view('supervisiones.create', compact('docentes', 'cartas', 'estados', 'oficinas'));
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
            'nEstado' => 'required|integer',
            'nOficina' => 'required|integer',
            'detalles' => 'required|array|min:1',
            'detalles.*.nNroSupervision' => 'required|integer|min:1',
            'detalles.*.dFechaSupervision' => 'required|date',
        ]);

        $supervision = Supervision::create($request->only([
            'IdDocente', 'IdCartaPresentacion', 'nNota', 'dFechaInicio', 'dFechaFin', 'nHoras', 'nEstado', 'nOficina'
        ]));

        foreach ($request->detalles as $detalle) {
            $supervision->detalles()->create([
                'nNroSupervision'   => $detalle['nNroSupervision'],
                'dFechaSupervision' => $detalle['dFechaSupervision'],
            ]);
        }

        return redirect()->route('supervisiones.index')->with('success', '✅ Supervisión registrada correctamente.');
    }

    /**
     * Mostrar una supervisión
     */
    public function show($id)
    {
        $supervision = Supervision::with([
            'docente.persona',
            'cartaPresentacion.estudiante.persona',
            'detalles'
        ])->findOrFail($id);

        $supervision->estado_nombre = Constante::where('nConstGrupo', 'ESTADO_SUPERVISION')
            ->where('nConstValor', (string)$supervision->nEstado)
            ->value('nConstDescripcion') ?? '—';

        $supervision->oficina_nombre = Constante::where('nConstGrupo', 'OFICINA')
            ->where('nConstValor', (string)$supervision->nOficina)
            ->value('nConstDescripcion') ?? '—';

        return view('supervisiones.show', compact('supervision'));
    }

    /**
     * Formulario de edición
     */
    public function edit($id)
    {
        $supervision = Supervision::with(['detalles', 'docente.persona', 'cartaPresentacion.estudiante.persona'])
            ->findOrFail($id);

        $docentes = Docente::with('persona')->get();
        $cartas = CartaPresentacion::with('estudiante.persona')->get();

        $estados = Constante::where('nConstGrupo', 'ESTADO_SUPERVISION')
            ->where('nConstEstado', 1)
            ->orderBy('nConstOrden')
            ->get();

        $oficinas = Constante::where('nConstGrupo', 'OFICINA')
            ->where('nConstEstado', 1)
            ->orderBy('nConstOrden')
            ->get();

        return view('supervisiones.edit', compact('supervision', 'docentes', 'cartas', 'estados', 'oficinas'));
    }

    /**
     * Actualizar supervisión y sus detalles
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
            'nEstado' => 'required|integer',
            'nOficina' => 'required|integer',
            'detalles' => 'required|array|min:1',
            'detalles.*.nNroSupervision' => 'required|integer|min:1',
            'detalles.*.dFechaSupervision' => 'required|date',
        ]);

        $supervision = Supervision::findOrFail($id);
        $supervision->update($request->only([
            'IdDocente', 'IdCartaPresentacion', 'nNota', 'dFechaInicio', 'dFechaFin', 'nHoras', 'nEstado', 'nOficina'
        ]));

        $supervision->detalles()->delete();
        foreach ($request->detalles as $detalle) {
            $supervision->detalles()->create([
                'nNroSupervision'   => $detalle['nNroSupervision'],
                'dFechaSupervision' => $detalle['dFechaSupervision'],
            ]);
        }

        return redirect()->route('supervisiones.index')->with('success', '✅ Supervisión actualizada correctamente.');
    }

    /**
     * Eliminar supervisión y sus detalles
     */
    public function destroy($id)
    {
        $supervision = Supervision::findOrFail($id);
        $supervision->detalles()->delete();
        $supervision->delete();

        return redirect()->route('supervisiones.index')->with('success', '🗑️ Supervisión eliminada correctamente.');
    }
}

