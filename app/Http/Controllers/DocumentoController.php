<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\DocumentoCarta;
use App\Models\Constante;
use App\Models\CartaPresentacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class DocumentoController extends Controller
{
    /**
     * Mostrar listado de documentos
     */
    public function index(Request $request)
    {
        $dni = $request->input('dni');

        $documentos = Documento::with([
                'tipoDocumento',
                'cartaPresentacion.estudiante.persona'
            ])
            ->when($dni, function ($query, $dni) {
                $query->whereHas('cartaPresentacion.estudiante.persona', function ($q) use ($dni) {
                    $q->where('cDNI', 'like', '%' . $dni . '%');
                });
            })
            ->orderByDesc('dFechaDocumento')
            ->get();

        return view('documentos.index', compact('documentos', 'dni'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $tiposDocumento = Constante::where('nConstGrupo', 'TIPO_DOCUMENTO')
            ->where('nConstEstado', '1')
            ->orderBy('nConstOrden')
            ->pluck('nConstDescripcion', 'IdConstante');

        $cartas = CartaPresentacion::with(['estudiante.persona'])
            ->orderByDesc('dFechaRegistro')
            ->get();

        return view('documentos.create', compact('tiposDocumento', 'cartas'));
    }

    /**
     * Guardar nuevo documento
     */
    public function store(Request $request)
    {
        $request->validate([
            'cNroDocumento'   => 'required|string|max:50',
            'dFechaDocumento' => 'required|date',
            'cTipoDocumento'  => 'required|integer',
            'dFechaEntrega'   => 'nullable|date',
            'eDocumentoAdjunto' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'IdCartaPresentacion' => 'nullable|integer',
            'documento_carta_memorandum' => 'nullable|array',
            'documento_carta_secretaria' => 'nullable|array',
        ]);

        DB::beginTransaction();

        try {
            // Procesar archivo adjunto
            $archivo = null;
            if ($request->hasFile('eDocumentoAdjunto')) {
                $archivo = file_get_contents($request->file('eDocumentoAdjunto')->getRealPath());
            }

            // Crear documento
            $documento = Documento::create([
                'cNroDocumento'   => $request->cNroDocumento,
                'dFechaDocumento' => $request->dFechaDocumento,
                'cTipoDocumento'  => $request->cTipoDocumento,
                'dFechaEntrega'   => $request->dFechaEntrega ?? null,
                'eDocumentoAdjunto' => $archivo,
            ]);

            $fechaServidor = Carbon::now()->toDateString();

            // Insertar relaciones dinámicas (memorandum / secretaría)
            foreach (['documento_carta_memorandum', 'documento_carta_secretaria'] as $tipo) {
                if ($request->filled($tipo) && is_array($request->$tipo)) {
                    foreach ($request->$tipo as $fila) {
                        $idCarta = $fila['IdCartaPresentacion'] ?? null;
                        if (!empty($idCarta)) {
                            DB::table('DOCUMENTO_CARTA')->insert([
                                'IdDocumento' => $documento->IdDocumento,
                                'IdCartaPresentacion' => $idCarta,
                                'dFechaRegistro' => $fechaServidor,
                            ]);
                        }
                    }
                }
            }

            // Insertar relación simple (IdCartaPresentacion)
            if ($request->filled('IdCartaPresentacion') && !empty($request->IdCartaPresentacion)) {
                DB::table('DOCUMENTO_CARTA')->insert([
                    'IdDocumento' => $documento->IdDocumento,
                    'IdCartaPresentacion' => $request->IdCartaPresentacion,
                    'dFechaRegistro' => $fechaServidor,
                ]);
            }

            DB::commit();
            return redirect()->route('documentos.index')->with('success', '✅ Documento registrado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', '❌ Error al registrar documento: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        $documento = Documento::with(['cartaPresentacion.estudiante.persona'])->findOrFail($id);

        $tiposDocumento = Constante::where('nConstGrupo', 'TIPO_DOCUMENTO')
            ->where('nConstEstado', '1')
            ->orderBy('nConstOrden')
            ->pluck('nConstDescripcion', 'IdConstante');

        $cartas = CartaPresentacion::with(['estudiante.persona'])
            ->orderByDesc('dFechaRegistro')
            ->get();

        return view('documentos.edit', compact('documento', 'tiposDocumento', 'cartas'));
    }

    /**
     * Actualizar documento existente
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'cNroDocumento'   => 'required|string|max:50',
            'dFechaDocumento' => 'required|date',
            'cTipoDocumento'  => 'required|integer',
            'dFechaEntrega'   => 'nullable|date',
            'eDocumentoAdjunto' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'IdCartaPresentacion' => 'nullable|integer',
            'documento_carta_memorandum' => 'nullable|array',
            'documento_carta_secretaria' => 'nullable|array',
        ]);

        DB::beginTransaction();

        try {
            $documento = Documento::findOrFail($id);

            // Actualizar archivo adjunto si existe
            if ($request->hasFile('eDocumentoAdjunto')) {
                $documento->eDocumentoAdjunto = file_get_contents($request->file('eDocumentoAdjunto')->getRealPath());
            }

            // Actualizar datos principales
            $documento->update([
                'cNroDocumento'   => $request->cNroDocumento,
                'dFechaDocumento' => $request->dFechaDocumento,
                'cTipoDocumento'  => $request->cTipoDocumento,
                'dFechaEntrega'   => $request->dFechaEntrega ?? $documento->dFechaEntrega,
            ]);

            // Eliminar relaciones previas
            DB::table('DOCUMENTO_CARTA')->where('IdDocumento', $documento->IdDocumento)->delete();

            $fechaServidor = Carbon::now()->toDateString();

            // Insertar nuevas relaciones dinámicas
            foreach (['documento_carta_memorandum', 'documento_carta_secretaria'] as $tipo) {
                if ($request->filled($tipo) && is_array($request->$tipo)) {
                    foreach ($request->$tipo as $fila) {
                        $idCarta = $fila['IdCartaPresentacion'] ?? null;
                        if (!empty($idCarta)) {
                            DB::table('DOCUMENTO_CARTA')->insert([
                                'IdDocumento' => $documento->IdDocumento,
                                'IdCartaPresentacion' => $idCarta,
                                'dFechaRegistro' => $fechaServidor,
                            ]);
                        }
                    }
                }
            }

            // Insertar relación simple
            if ($request->filled('IdCartaPresentacion') && !empty($request->IdCartaPresentacion)) {
                DB::table('DOCUMENTO_CARTA')->insert([
                    'IdDocumento' => $documento->IdDocumento,
                    'IdCartaPresentacion' => $request->IdCartaPresentacion,
                    'dFechaRegistro' => $fechaServidor,
                ]);
            }

            DB::commit();
            return redirect()->route('documentos.index')->with('success', '✅ Documento actualizado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', '❌ Error al actualizar documento: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar documento
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $documento = Documento::findOrFail($id);

            // Eliminar relaciones con cartas
            DB::table('DOCUMENTO_CARTA')->where('IdDocumento', $documento->IdDocumento)->delete();

            // Eliminar documento
            $documento->delete();

            DB::commit();
            return redirect()->route('documentos.index')->with('success', '🗑️ Documento eliminado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', '❌ Error al eliminar documento: ' . $e->getMessage());
        }
    }
}












