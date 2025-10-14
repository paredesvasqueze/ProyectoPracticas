<?php

namespace App\Http\Controllers;

use App\Models\DocumentoCarta;
use App\Models\Documento;
use App\Models\CartaPresentacion;
use Illuminate\Http\Request;

class DocumentoCartaController extends Controller
{
    /**
     * Mostrar listado de Documento-Carta.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $documentosCartas = DocumentoCarta::with(['documento', 'cartaPresentacion'])
            ->when($search, function ($query, $search) {
                $query->whereHas('documento', function ($q) use ($search) {
                    $q->where('cNroDocumento', 'LIKE', "%{$search}%");
                })
                ->orWhereHas('cartaPresentacion', function ($q) use ($search) {
                    $q->where('nNroCarta', 'LIKE', "%{$search}%");
                });
            })
            ->orderByDesc('dFechaRegistro')
            ->paginate(10);

        return view('documento_cartas.index', compact('documentosCartas', 'search'));
    }

    /**
     * Mostrar formulario de creación.
     */
    public function create()
    {
        $documentos = Documento::orderBy('cNroDocumento')->get();
        $cartas = CartaPresentacion::orderBy('nNroCarta')->get();

        return view('documento_cartas.create', compact('documentos', 'cartas'));
    }

    /**
     * Guardar nuevo registro DocumentoCarta.
     */
    public function store(Request $request)
    {
        $request->validate([
            'IdDocumento' => 'required|exists:DOCUMENTO,IdDocumento',
            'IdCartaPresentacion' => 'required|exists:CARTA_PRESENTACION,IdCartaPresentacion',
        ], [
            'IdDocumento.required' => 'Debe seleccionar un documento.',
            'IdCartaPresentacion.required' => 'Debe seleccionar una carta de presentación.',
        ]);

        DocumentoCarta::create([
            'IdDocumento' => $request->IdDocumento,
            'IdCartaPresentacion' => $request->IdCartaPresentacion,
            'dFechaRegistro' => now()->format('Y-m-d'), // Se asigna automáticamente
        ]);

        return redirect()->route('documento_cartas.index')
                         ->with('success', 'Documento vinculado correctamente a la carta de presentación.');
    }

    /**
     * Mostrar formulario de edición.
     */
    public function edit($id)
    {
        $docCarta = DocumentoCarta::findOrFail($id);
        $documentos = Documento::orderBy('cNroDocumento')->get();
        $cartas = CartaPresentacion::orderBy('nNroCarta')->get();

        return view('documento_cartas.edit', compact('docCarta', 'documentos', 'cartas'));
    }

    /**
     * Actualizar un DocumentoCarta existente.
     */
    public function update(Request $request, $id)
    {
        $docCarta = DocumentoCarta::findOrFail($id);

        $request->validate([
            'IdDocumento' => 'required|exists:DOCUMENTO,IdDocumento',
            'IdCartaPresentacion' => 'required|exists:CARTA_PRESENTACION,IdCartaPresentacion',
        ]);

        // No se modifica la fecha de registro
        $docCarta->update([
            'IdDocumento' => $request->IdDocumento,
            'IdCartaPresentacion' => $request->IdCartaPresentacion,
        ]);

        return redirect()->route('documento_cartas.index')
                         ->with('success', 'Relación Documento-Carta actualizada correctamente.');
    }

    /**
     * Mostrar detalles de un DocumentoCarta.
     */
    public function show($id)
    {
        $docCarta = DocumentoCarta::with(['documento', 'cartaPresentacion'])->findOrFail($id);

        return view('documento_cartas.show', compact('docCarta'));
    }

    /**
     * Eliminar un DocumentoCarta.
     */
    public function destroy($id)
    {
        $docCarta = DocumentoCarta::findOrFail($id);
        $docCarta->delete();

        return redirect()->route('documento_cartas.index')
                         ->with('success', 'Registro eliminado correctamente.');
    }
}





