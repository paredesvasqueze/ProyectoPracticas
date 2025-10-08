<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\Constante;
use App\Models\Estudiante;
use App\Models\DocumentoSupervision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class DocumentoController extends Controller
{
    /**
     * Mostrar listado de documentos con búsqueda.
     */
    public function index(Request $request)
    {
        $query = Documento::with('estudiante.persona', 'tipoDocumento');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('cNroDocumento', 'LIKE', "%{$search}%")
                  ->orWhereHas('tipoDocumento', function ($qt) use ($search) {
                      $qt->where('nConstDescripcion', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('estudiante.persona', function ($qp) use ($search) {
                      $qp->where('cNombre', 'LIKE', "%{$search}%")
                         ->orWhere('cApellido', 'LIKE', "%{$search}%")
                         ->orWhere('cDNI', 'LIKE', "%{$search}%");
                  });
            });
        }

        $documentos = $query->orderBy('IdDocumento', 'desc')->get();

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
     * Guardar documento nuevo.
     */
    public function store(Request $request)
    {
        $request->validate([
            'cNroDocumento'     => 'required|string|max:50',
            'dFechaDocumento'   => 'required|date',
            'cTipoDocumento'    => 'required|integer|exists:constante,IdConstante',
            'dFechaEntrega'     => 'nullable|date',
            'IdEstudiante'      => 'required|integer|exists:estudiante,IdEstudiante',
            'eDocumentoAdjunto' => 'nullable|file|mimes:pdf,doc,docx,png,jpg,jpeg|max:5120',
        ]);

        DB::beginTransaction();

        try {
            // Guardar archivo si existe
            $rutaArchivo = $request->hasFile('eDocumentoAdjunto')
                ? $request->file('eDocumentoAdjunto')->store('documentos', 'public')
                : null;

            // Crear documento principal
            $documento = Documento::create([
                'cNroDocumento'     => $request->cNroDocumento,
                'dFechaDocumento'   => $request->dFechaDocumento,
                'cTipoDocumento'    => $request->cTipoDocumento,
                'dFechaEntrega'     => $request->dFechaEntrega,
                'eDocumentoAdjunto' => $rutaArchivo,
                'IdEstudiante'      => $request->IdEstudiante,
            ]);

            // Detectar tipo de documento
            $tipoDescripcion = strtoupper($documento->tipoDocumento->nConstDescripcion ?? '');

            // 1️⃣ INFORME A SECRETARÍA ACADÉMICA
            if (str_contains($tipoDescripcion, 'SECRETAR') && $request->has('secretaria')) {
                foreach ($request->secretaria as $fila) {
                    if (!empty($fila['nombre'])) {
                        DocumentoSupervision::create([
                            'IdDocumento'    => $documento->IdDocumento,
                            'dFechaRegistro' => now(),
                            'nro_secuencial' => $fila['nro_secuencial'] ?? null,
                            'programa'       => $fila['programa'] ?? null,
                            'nombre'         => $fila['nombre'] ?? null,
                            'dni'            => $fila['dni'] ?? null,
                            'modulo'         => $fila['modulo'] ?? null,
                        ]);
                    }
                }
            }
            // 2️⃣ MEMORÁNDUM A COORDINACIÓN
            elseif (str_contains($tipoDescripcion, 'MEMORAND') && $request->has('memorandum')) {
                foreach ($request->memorandum as $fila) {
                    if (!empty($fila['nombre'])) {
                        DocumentoSupervision::create([
                            'IdDocumento'      => $documento->IdDocumento,
                            'dFechaRegistro'   => now(),
                            'nro_expediente'   => $fila['nro_expediente'] ?? null,
                            'programa'         => $fila['programa'] ?? null,
                            'nombre'           => $fila['nombre'] ?? null,
                            'centro_practicas' => $fila['centro_practicas'] ?? null,
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('documentos.index')->with('success', 'Documento registrado correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al registrar el documento: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar formulario de edición.
     */
    public function edit($id)
    {
        $documento = Documento::with('estudiante.persona', 'tipoDocumento')->findOrFail($id);

        $tiposDocumento = Constante::where('nConstGrupo', 'TIPO_DOCUMENTO')
            ->where('nConstEstado', 1)
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
            'IdEstudiante'      => 'required|integer|exists:estudiante,IdEstudiante',
            'eDocumentoAdjunto' => 'nullable|file|mimes:pdf,doc,docx,png,jpg,jpeg|max:5120',
        ]);

        $documento = Documento::findOrFail($id);

        // Actualizar archivo si hay uno nuevo
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
            'IdEstudiante'      => $request->IdEstudiante,
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

    /**
     * Buscar estudiantes/personas (AJAX) para autocompletar.
     */
    public function buscarPersona(Request $request)
    {
        $term = $request->get('q', '');

        $estudiantes = Estudiante::with('persona', 'programa', 'modulo')
            ->whereHas('persona', function ($q) use ($term) {
                $q->where('cNombre', 'LIKE', "%{$term}%")
                  ->orWhere('cApellido', 'LIKE', "%{$term}%")
                  ->orWhere('cDNI', 'LIKE', "%{$term}%");
            })
            ->limit(10)
            ->get()
            ->map(function ($est) {
                return [
                    'id'               => $est->IdEstudiante,
                    'nombre'           => $est->persona->cNombre . ' ' . $est->persona->cApellido,
                    'dni'              => $est->persona->cDNI,
                    'programa'         => $est->programa->nombre ?? '',
                    'modulo'           => $est->modulo->nombre ?? '',
                    'nro_expediente'   => $est->nro_expediente ?? '',
                    'centro_practicas' => $est->centro_practicas ?? '',
                    'text'             => $est->persona->cNombre . ' ' . $est->persona->cApellido . ' (' . $est->persona->cDNI . ')',
                ];
            });

        return response()->json($estudiantes);
    }
}













