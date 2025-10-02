<?php

namespace App\Http\Controllers;

use App\Models\DocumentoSupervision;
use App\Models\Documento;
use App\Models\Supervision;
use Illuminate\Http\Request;

class DocumentoSupervisionController extends Controller
{
    /**
     * Mostrar listado de Documentos de Supervisión.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $documento_supervisiones = DocumentoSupervision::with(['documento', 'supervision'])
            ->when($search, function ($query, $search) {
                $query->where('nNroSupervision', 'LIKE', "%{$search}%")
                      ->orWhereHas('documento', function ($q) use ($search) {
                          $q->where('cNroDocumento', 'LIKE', "%{$search}%");
                      });
            })
            ->orderBy('dFechaRegistro', 'desc')
            ->paginate(10);

        return view('documento_supervisiones.index', compact('documento_supervisiones', 'search'));
    }

    /**
     * Mostrar formulario de creación.
     */
    public function create()
    {
        $documentos = Documento::all();
        $supervisiones = Supervision::all();
        return view('documento_supervisiones.create', compact('documentos', 'supervisiones'));
    }

    /**
     * Guardar nuevo registro en BD.
     */
    public function store(Request $request)
    {
        $request->validate([
            'dFechaRegistro'    => 'required|date',
            'dFechaSupervision' => 'required|date',
            'nNroSupervision'   => 'required|string|max:20',
            'IdDocumento'       => 'required|exists:DOCUMENTO,IdDocumento',
            'IdSupervision'     => 'required|exists:SUPERVISION,IdSupervision',
        ]);

        DocumentoSupervision::create($request->all());

        return redirect()->route('documento_supervisiones.index')
                         ->with('success', 'Documento de Supervisión creado correctamente.');
    }

    /**
     * Mostrar formulario de edición.
     */
    public function edit($id)
    {
        $docSup = DocumentoSupervision::findOrFail($id);
        $documentos = Documento::all();
        $supervisiones = Supervision::all();
        return view('documento_supervisiones.edit', compact('docSup', 'documentos', 'supervisiones'));
    }

    /**
     * Actualizar un registro existente.
     */
    public function update(Request $request, $id)
    {
        $docSup = DocumentoSupervision::findOrFail($id);

        $request->validate([
            'dFechaRegistro'    => 'required|date',
            'dFechaSupervision' => 'required|date',
            'nNroSupervision'   => 'required|string|max:20',
            'IdDocumento'       => 'required|exists:DOCUMENTO,IdDocumento',
            'IdSupervision'     => 'required|exists:SUPERVISION,IdSupervision',
        ]);

        $docSup->update($request->all());

        return redirect()->route('documento_supervisiones.index')
                         ->with('success', 'Documento de Supervisión actualizado correctamente.');
    }

    /**
     * Mostrar un registro individual.
     */
    public function show($id)
    {
        $docSup = DocumentoSupervision::with(['documento', 'supervision'])->findOrFail($id);
        return view('documento_supervisiones.show', compact('docSup'));
    }

    /**
     * Eliminar un registro.
     */
    public function destroy($id)
    {
        $docSup = DocumentoSupervision::findOrFail($id);
        $docSup->delete();

        return redirect()->route('documento_supervisiones.index')
                         ->with('success', 'Documento de Supervisión eliminado correctamente.');
    }
}


