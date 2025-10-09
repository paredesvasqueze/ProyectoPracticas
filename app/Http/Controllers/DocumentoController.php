<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\Constante;
use App\Models\Supervision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DocumentoController extends Controller
{
    /**
     * Mostrar listado de documentos.
     */
    public function index()
    {
        $documentos = Documento::with('tipoDocumento')->get();
        return view('documentos.index', compact('documentos'));
    }

    /**
     * Mostrar formulario de creación.
     */
    public function create()
    {
        $tiposDocumento = Constante::where('nConstGrupo', 'TIPO_DOCUMENTO')
            ->where('nConstEstado', 1)
            ->orderBy('nConstOrden')
            ->pluck('nConstDescripcion', 'IdConstante');

        return view('documentos.create', compact('tiposDocumento'));
    }

    /**
     * Guardar nuevo documento.
     */
    public function store(Request $request)
    {
        $request->validate([
            'cNroDocumento' => 'required|string|max:50',
            'dFechaDocumento' => 'required|date',
            'cTipoDocumento' => 'required|integer',
            'archivo' => 'nullable|file|mimes:pdf,docx|max:2048',
        ]);

        DB::beginTransaction();

        try {
            // Guardar archivo si se sube
            $nombreArchivo = null;
            if ($request->hasFile('archivo')) {
                $nombreArchivo = $request->file('archivo')->store('documentos', 'public');
            }

            // Crear el documento
            $documento = Documento::create([
                'cNroDocumento' => $request->cNroDocumento,
                'dFechaDocumento' => $request->dFechaDocumento,
                'cTipoDocumento' => $request->cTipoDocumento,
                'cAsunto' => $request->cAsunto,
                'cReferencia' => $request->cReferencia,
                'cArchivo' => $nombreArchivo,
                'IdUsuarioRegistro' => Auth::id(),
                'dFechaRegistro' => now(),
            ]);

            // Registrar supervisiones si existen
            if ($request->has('supervisiones')) {
                foreach ($request->supervisiones as $sup) {
                    Supervision::create([
                        'IdDocumento' => $documento->IdDocumento,
                        'IdEstudiante' => $sup['IdEstudiante'],
                        'IdUsuarioRegistro' => Auth::id(),
                        'dFechaRegistro' => now(),
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('documentos.index')->with('success', 'Documento registrado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al registrar: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar formulario de edición con toda la información.
     */
    public function edit($id)
    {
        $documento = Documento::with([
            'tipoDocumento',
            'supervisiones.estudiante.persona',
            'supervisiones.estudiante.programa',
            'supervisiones.estudiante.modulo',
            'supervisiones.estudiante.empresa'
        ])->findOrFail($id);

        $tiposDocumento = Constante::where('nConstGrupo', 'TIPO_DOCUMENTO')
            ->where('nConstEstado', 1)
            ->orderBy('nConstOrden')
            ->pluck('nConstDescripcion', 'IdConstante');

        return view('documentos.edit', compact('documento', 'tiposDocumento'));
    }

    /**
     * Actualizar documento y supervisiones.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'cNroDocumento' => 'required|string|max:50',
            'dFechaDocumento' => 'required|date',
            'cTipoDocumento' => 'required|integer',
            'archivo' => 'nullable|file|mimes:pdf,docx|max:2048',
        ]);

        DB::beginTransaction();

        try {
            $documento = Documento::findOrFail($id);

            // Si hay nuevo archivo, eliminar anterior
            if ($request->hasFile('archivo')) {
                if ($documento->cArchivo && Storage::disk('public')->exists($documento->cArchivo)) {
                    Storage::disk('public')->delete($documento->cArchivo);
                }
                $documento->cArchivo = $request->file('archivo')->store('documentos', 'public');
            }

            // Actualizar datos del documento
            $documento->update([
                'cNroDocumento' => $request->cNroDocumento,
                'dFechaDocumento' => $request->dFechaDocumento,
                'cTipoDocumento' => $request->cTipoDocumento,
                'cAsunto' => $request->cAsunto,
                'cReferencia' => $request->cReferencia,
                'IdUsuarioModificacion' => Auth::id(),
                'dFechaModificacion' => now(),
            ]);

            // Actualizar supervisiones
            if ($request->has('supervisiones')) {
                // Eliminar supervisiones anteriores
                Supervision::where('IdDocumento', $documento->IdDocumento)->delete();

                // Insertar las nuevas
                foreach ($request->supervisiones as $sup) {
                    Supervision::create([
                        'IdDocumento' => $documento->IdDocumento,
                        'IdEstudiante' => $sup['IdEstudiante'],
                        'IdUsuarioRegistro' => Auth::id(),
                        'dFechaRegistro' => now(),
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('documentos.index')->with('success', 'Documento actualizado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar documento y supervisiones relacionadas.
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $documento = Documento::findOrFail($id);

            // Eliminar archivo si existe
            if ($documento->cArchivo && Storage::disk('public')->exists($documento->cArchivo)) {
                Storage::disk('public')->delete($documento->cArchivo);
            }

            // Eliminar supervisiones
            Supervision::where('IdDocumento', $documento->IdDocumento)->delete();

            // Eliminar documento
            $documento->delete();

            DB::commit();
            return redirect()->route('documentos.index')->with('success', 'Documento eliminado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }
}



