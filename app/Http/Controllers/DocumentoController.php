<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\Constante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentoController extends Controller
{
    /**
     * Mostrar listado de documentos con búsqueda.
     */
    public function index(Request $request)
    {
        // Traer documentos junto con el nombre del tipo
        $query = Documento::query()
            ->leftJoin('constante', 'documento.cTipoDocumento', '=', 'constante.IdConstante')
            ->select('documento.*', 'constante.nConstDescripcion as nombreTipoDocumento');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('documento.cNroDocumento', 'LIKE', "%{$search}%")
                  ->orWhere('constante.nConstDescripcion', 'LIKE', "%{$search}%");
            });
        }

        $documentos = $query->orderBy('documento.IdDocumento', 'desc')->get();

        return view('documentos.index', compact('documentos'));
    }

    /**
     * Mostrar formulario de creación.
     */
    public function create()
    {
        $tiposDocumento = Constante::where('nConstGrupo', 'TIPO_DOCUMENTO')
            ->where('nConstEstado', '1')
            ->orderBy('nConstOrden')
            ->pluck('nConstDescripcion', 'IdConstante'); // valor => texto

        return view('documentos.create', compact('tiposDocumento'));
    }

    /**
     * Guardar un documento nuevo.
     */
    public function store(Request $request)
    {
        $request->validate([
            'cNroDocumento'     => 'required|string|max:50',
            'dFechaDocumento'   => 'required|date',
            'cTipoDocumento'    => 'required|integer|exists:constante,IdConstante',
            'dFechaEntrega'     => 'nullable|date',
            'eDocumentoAdjunto' => 'nullable|file|mimes:pdf,doc,docx,png,jpg,jpeg|max:5120',
        ]);

        $rutaArchivo = null;
        if ($request->hasFile('eDocumentoAdjunto')) {
            $rutaArchivo = $request->file('eDocumentoAdjunto')->store('documentos', 'public');
        }

        Documento::create([
            'cNroDocumento'     => $request->cNroDocumento,
            'dFechaDocumento'   => $request->dFechaDocumento,
            'cTipoDocumento'    => $request->cTipoDocumento, // se guarda IdConstante
            'dFechaEntrega'     => $request->dFechaEntrega,
            'eDocumentoAdjunto' => $rutaArchivo,
        ]);

        return redirect()->route('documentos.index')->with('success', 'Documento registrado correctamente.');
    }

    /**
     * Mostrar formulario de edición.
     */
    public function edit($id)
    {
        $documento = Documento::findOrFail($id);

        $tiposDocumento = Constante::where('nConstGrupo', 'TIPO_DOCUMENTO')
            ->where('nConstEstado', '1')
            ->orderBy('nConstOrden')
            ->pluck('nConstDescripcion', 'IdConstante');

        return view('documentos.edit', compact('documento', 'tiposDocumento'));
    }

    /**
     * Actualizar documento.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'cNroDocumento'     => 'required|string|max:50',
            'dFechaDocumento'   => 'required|date',
            'cTipoDocumento'    => 'required|integer|exists:constante,IdConstante',
            'dFechaEntrega'     => 'nullable|date',
            'eDocumentoAdjunto' => 'nullable|file|mimes:pdf,doc,docx,png,jpg,jpeg|max:5120',
        ]);

        $documento = Documento::findOrFail($id);

        if ($request->hasFile('eDocumentoAdjunto')) {
            if ($documento->eDocumentoAdjunto && Storage::disk('public')->exists($documento->eDocumentoAdjunto)) {
                Storage::disk('public')->delete($documento->eDocumentoAdjunto);
            }
            $documento->eDocumentoAdjunto = $request->file('eDocumentoAdjunto')->store('documentos', 'public');
        }

        $documento->update([
            'cNroDocumento'     => $request->cNroDocumento,
            'dFechaDocumento'   => $request->dFechaDocumento,
            'cTipoDocumento'    => $request->cTipoDocumento,
            'dFechaEntrega'     => $request->dFechaEntrega,
            'eDocumentoAdjunto' => $documento->eDocumentoAdjunto,
        ]);

        return redirect()->route('documentos.index')->with('success', 'Documento actualizado correctamente.');
    }

    /**
     * Eliminar documento.
     */
    public function destroy($id)
    {
        $documento = Documento::findOrFail($id);

        if ($documento->eDocumentoAdjunto && Storage::disk('public')->exists($documento->eDocumentoAdjunto)) {
            Storage::disk('public')->delete($documento->eDocumentoAdjunto);
        }

        $documento->delete();

        return redirect()->route('documentos.index')->with('success', 'Documento eliminado correctamente.');
    }
}




