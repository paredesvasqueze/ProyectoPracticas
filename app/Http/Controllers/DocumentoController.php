<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\Constante;
use App\Models\CartaPresentacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;

class DocumentoController extends Controller
{
    /**
     * 📄 Mostrar listado de documentos (búsqueda por DNI del estudiante)
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
     * 📝 Formulario de creación de documento
     */
    public function create(Request $request)
    {
        // === Tipos de documento disponibles ===
        $tiposDocumento = Constante::where('nConstGrupo', 'TIPO_DOCUMENTO')
            ->where('nConstEstado', 1)
            ->orderBy('nConstOrden')
            ->pluck('nConstDescripcion', 'IdConstante');

        // === Normalizamos el tipo de documento (puede venir como INFORME, informe, etc.) ===
        $tipo = strtolower(trim($request->input('tipo'))); // 🔥 CORRECCIÓN PRINCIPAL

        // === FILTRADO SEGÚN EL TIPO ===
        if ($tipo === 'informe') {
            // ✅ Solo cartas con supervisión finalizada (nEstado = 2)
            $cartas = CartaPresentacion::with(['estudiante.persona', 'supervision'])
                ->conSupervisionFinalizada()
                ->orderByDesc('dFechaRegistro')
                ->get();

        } elseif ($tipo === 'memorandum') {
            // ✅ Solo cartas sin supervisión finalizada (sin registro o nEstado != 2)
            $cartas = CartaPresentacion::with(['estudiante.persona', 'supervision'])
                ->sinSupervisionFinalizada()
                ->orderByDesc('dFechaRegistro')
                ->get();

        } else {
            // 🔄 Si no se pasa tipo, mostrar todas las cartas
            $cartas = CartaPresentacion::with(['estudiante.persona', 'supervision'])
                ->orderByDesc('dFechaRegistro')
                ->get();
        }

        return view('documentos.create', compact('tiposDocumento', 'cartas', 'tipo'));
    }

    /**
     * 💾 Guardar nuevo documento
     */
    public function store(Request $request)
    {
        $request->validate([
            'cNroDocumento'       => 'required|string|max:50',
            'dFechaDocumento'     => 'required|date',
            'cTipoDocumento'      => 'required|integer',
            'dFechaEntrega'       => 'nullable|date',
            'eDocumentoAdjunto'   => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        DB::beginTransaction();
        try {
            // === Guardar archivo si existe ===
            $rutaArchivo = null;
            if ($request->hasFile('eDocumentoAdjunto')) {
                $archivo = $request->file('eDocumentoAdjunto');
                $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
                $rutaArchivo = $archivo->storeAs('public/documentos', $nombreArchivo);
            }

            // === Crear documento ===
            $documento = Documento::create([
                'cNroDocumento'     => strtoupper($request->cNroDocumento),
                'dFechaDocumento'   => $request->dFechaDocumento,
                'cTipoDocumento'    => $request->cTipoDocumento,
                'dFechaEntrega'     => $request->dFechaEntrega,
                'eDocumentoAdjunto' => $rutaArchivo ? str_replace('public/', 'storage/', $rutaArchivo) : null,
            ]);

            // === Registrar relaciones en DOCUMENTO_CARTA ===
            $fechaServidor = Carbon::now()->toDateString();
            $relaciones = collect();

            foreach (['documento_carta_memorandum', 'documento_carta_secretaria'] as $tipo) {
                if ($request->has($tipo)) {
                    foreach ($request->$tipo as $fila) {
                        $idCarta = $fila['IdCartaPresentacion'] ?? null;
                        if ($idCarta && !$relaciones->contains($idCarta)) {
                            DB::table('DOCUMENTO_CARTA')->insert([
                                'IdDocumento'         => $documento->IdDocumento,
                                'IdCartaPresentacion' => $idCarta,
                                'dFechaRegistro'      => $fechaServidor,
                            ]);
                            $relaciones->push($idCarta);
                        }
                    }
                }
            }

            DB::commit();
            return redirect()->route('documentos.index')->with('success', '✅ Documento registrado correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', '❌ Error al registrar documento: ' . $e->getMessage());
        }
    }

    /**
     * ✏️ Formulario de edición de documento
     */
    public function edit($id)
    {
        $documento = Documento::with(['cartaPresentacion.estudiante.persona', 'cartaPresentacion.supervision'])
            ->findOrFail($id);

        $tiposDocumento = Constante::where('nConstGrupo', 'TIPO_DOCUMENTO')
            ->where('nConstEstado', 1)
            ->orderBy('nConstOrden')
            ->pluck('nConstDescripcion', 'IdConstante');

        // === FILTRO SEGÚN EL TIPO DE DOCUMENTO ===
        if ($documento->cTipoDocumento == 1) {
            $cartas = CartaPresentacion::with(['estudiante.persona', 'supervision'])
                ->conSupervisionFinalizada()
                ->orderByDesc('dFechaRegistro')
                ->get();

        } elseif ($documento->cTipoDocumento == 2) {
            $cartas = CartaPresentacion::with(['estudiante.persona', 'supervision'])
                ->sinSupervisionFinalizada()
                ->orderByDesc('dFechaRegistro')
                ->get();

        } else {
            $cartas = CartaPresentacion::with(['estudiante.persona', 'supervision'])
                ->orderByDesc('dFechaRegistro')
                ->get();
        }

        return view('documentos.edit', compact('documento', 'tiposDocumento', 'cartas'));
    }

    /**
     * 🔄 Actualizar documento existente
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'cNroDocumento'       => 'required|string|max:50',
            'dFechaDocumento'     => 'required|date',
            'cTipoDocumento'      => 'required|integer',
            'dFechaEntrega'       => 'nullable|date',
            'eDocumentoAdjunto'   => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        DB::beginTransaction();
        try {
            $documento = Documento::findOrFail($id);

            // === Actualizar archivo si hay uno nuevo ===
            if ($request->hasFile('eDocumentoAdjunto')) {
                if ($documento->eDocumentoAdjunto && Storage::exists(str_replace('storage/', 'public/', $documento->eDocumentoAdjunto))) {
                    Storage::delete(str_replace('storage/', 'public/', $documento->eDocumentoAdjunto));
                }

                $archivo = $request->file('eDocumentoAdjunto');
                $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
                $rutaArchivo = $archivo->storeAs('public/documentos', $nombreArchivo);
                $documento->eDocumentoAdjunto = str_replace('public/', 'storage/', $rutaArchivo);
            }

            // === Actualizar datos del documento ===
            $documento->update([
                'cNroDocumento'   => strtoupper($request->cNroDocumento),
                'dFechaDocumento' => $request->dFechaDocumento,
                'cTipoDocumento'  => $request->cTipoDocumento,
                'dFechaEntrega'   => $request->dFechaEntrega,
            ]);

            // === Actualizar relaciones DOCUMENTO_CARTA ===
            DB::table('DOCUMENTO_CARTA')->where('IdDocumento', $documento->IdDocumento)->delete();

            $fechaServidor = Carbon::now()->toDateString();
            $relaciones = collect();

            foreach (['documento_carta_memorandum', 'documento_carta_secretaria'] as $tipo) {
                if ($request->has($tipo)) {
                    foreach ($request->$tipo as $fila) {
                        $idCarta = $fila['IdCartaPresentacion'] ?? null;
                        if ($idCarta && !$relaciones->contains($idCarta)) {
                            DB::table('DOCUMENTO_CARTA')->insert([
                                'IdDocumento'         => $documento->IdDocumento,
                                'IdCartaPresentacion' => $idCarta,
                                'dFechaRegistro'      => $fechaServidor,
                            ]);
                            $relaciones->push($idCarta);
                        }
                    }
                }
            }

            DB::commit();
            return redirect()->route('documentos.index')->with('success', '✅ Documento actualizado correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', '❌ Error al actualizar documento: ' . $e->getMessage());
        }
    }

    /**
     * 🗑️ Eliminar documento
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $documento = Documento::findOrFail($id);

            if ($documento->eDocumentoAdjunto && Storage::exists(str_replace('storage/', 'public/', $documento->eDocumentoAdjunto))) {
                Storage::delete(str_replace('storage/', 'public/', $documento->eDocumentoAdjunto));
            }

            DB::table('DOCUMENTO_CARTA')->where('IdDocumento', $documento->IdDocumento)->delete();

            $documento->delete();

            DB::commit();
            return redirect()->route('documentos.index')->with('success', '🗑️ Documento eliminado correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', '❌ Error al eliminar documento: ' . $e->getMessage());
        }
    }
}











