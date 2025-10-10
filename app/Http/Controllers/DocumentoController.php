<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\Constante;
use App\Models\Supervision;
use App\Models\CartaPresentacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DocumentoController extends Controller
{
    /**
     * Mostrar listado de documentos con su tipo, carta asociada y supervisiones.
     */
    public function index(Request $request)
    {
        $dni = $request->input('dni');

        $documentos = Documento::with([
                'tipoDocumento',
                'supervisiones.estudiante.persona',
                'supervisiones.estudiante.cartaPresentacion'
            ])
            ->when($dni, function ($query, $dni) {
                $query->whereHas('supervisiones.estudiante.persona', function ($q) use ($dni) {
                    $q->where('cDNI', 'like', '%' . $dni . '%');
                });
            })
            ->orderByDesc('dFechaDocumento')
            ->get();

        return view('documentos.index', compact('documentos', 'dni'));
    }

    /**
     * Mostrar formulario de creación de documento.
     */
    public function create()
    {
        $tiposDocumento = Constante::where('nConstGrupo', 'TIPO_DOCUMENTO')
            ->where('nConstEstado', 1)
            ->orderBy('nConstOrden')
            ->pluck('nConstDescripcion', 'IdConstante');

        $cartas = CartaPresentacion::with(['estudiante.persona'])
            ->orderByDesc('dFechaRegistro')
            ->get();

        return view('documentos.create', compact('tiposDocumento', 'cartas'));
    }

    /**
     * Guardar nuevo documento, con archivo, carta y supervisiones.
     */
    public function store(Request $request)
    {
        $request->validate([
            'cNroDocumento'   => 'required|string|max:50',
            'dFechaDocumento' => 'required|date',
            'cTipoDocumento'  => 'required|integer',
            'archivo'         => 'nullable|file|mimes:pdf,docx|max:4096',
        ]);

        DB::beginTransaction();

        try {
            // Guardar archivo si existe
            $rutaArchivo = $request->hasFile('archivo')
                ? $request->file('archivo')->store('documentos', 'public')
                : null;

            // Crear documento
            $documento = Documento::create([
                'cNroDocumento'       => $request->cNroDocumento,
                'dFechaDocumento'     => $request->dFechaDocumento,
                'cTipoDocumento'      => $request->cTipoDocumento,
                'cAsunto'             => $request->cAsunto,
                'cReferencia'         => $request->cReferencia,
                'cArchivo'            => $rutaArchivo,
                'IdUsuarioRegistro'   => Auth::id(),
                'dFechaRegistro'      => now(),
            ]);

            // Relacionar con carta de presentación si se envía
            if ($request->filled('IdCartaPresentacion')) {
                DB::table('DOCUMENTO_CARTA')->insert([
                    'IdDocumento'         => $documento->IdDocumento,
                    'IdCartaPresentacion' => $request->IdCartaPresentacion,
                    'dFechaRegistro'      => now(),
                ]);
            }

            // Registrar supervisiones (si existen)
            if ($request->has('supervisiones') && is_array($request->supervisiones)) {
                foreach ($request->supervisiones as $sup) {
                    if (!isset($sup['IdEstudiante'])) continue;

                    Supervision::create([
                        'IdDocumento'       => $documento->IdDocumento,
                        'IdEstudiante'      => $sup['IdEstudiante'],
                        'cEstado'           => $sup['estado'] ?? 'Pendiente',
                        'IdUsuarioRegistro' => Auth::id(),
                        'dFechaRegistro'    => now(),
                    ]);
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
     * Mostrar formulario de edición de documento.
     */
    public function edit($id)
    {
        $documento = Documento::with([
                'tipoDocumento',
                'supervisiones.estudiante.persona',
                'supervisiones.estudiante.programa',
                'supervisiones.estudiante.modulo',
                'supervisiones.estudiante.empresa',
                'cartasPresentacion.estudiante.persona'
            ])
            ->findOrFail($id);

        $tiposDocumento = Constante::where('nConstGrupo', 'TIPO_DOCUMENTO')
            ->where('nConstEstado', 1)
            ->orderBy('nConstOrden')
            ->pluck('nConstDescripcion', 'IdConstante');

        $cartas = CartaPresentacion::with(['estudiante.persona'])
            ->orderByDesc('dFechaRegistro')
            ->get();

        return view('documentos.edit', compact('documento', 'tiposDocumento', 'cartas'));
    }

    /**
     * Actualizar documento, carta y supervisiones.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'cNroDocumento'   => 'required|string|max:50',
            'dFechaDocumento' => 'required|date',
            'cTipoDocumento'  => 'required|integer',
            'archivo'         => 'nullable|file|mimes:pdf,docx|max:4096',
        ]);

        DB::beginTransaction();

        try {
            $documento = Documento::findOrFail($id);

            // Reemplazar archivo si hay nuevo
            if ($request->hasFile('archivo')) {
                if ($documento->cArchivo && Storage::disk('public')->exists($documento->cArchivo)) {
                    Storage::disk('public')->delete($documento->cArchivo);
                }
                $documento->cArchivo = $request->file('archivo')->store('documentos', 'public');
            }

            // Actualizar datos
            $documento->update([
                'cNroDocumento'         => $request->cNroDocumento,
                'dFechaDocumento'       => $request->dFechaDocumento,
                'cTipoDocumento'        => $request->cTipoDocumento,
                'cAsunto'               => $request->cAsunto,
                'cReferencia'           => $request->cReferencia,
                'IdUsuarioModificacion' => Auth::id(),
                'dFechaModificacion'    => now(),
            ]);

            // Actualizar relación con carta
            DB::table('DOCUMENTO_CARTA')->where('IdDocumento', $documento->IdDocumento)->delete();

            if ($request->filled('IdCartaPresentacion')) {
                DB::table('DOCUMENTO_CARTA')->insert([
                    'IdDocumento'         => $documento->IdDocumento,
                    'IdCartaPresentacion' => $request->IdCartaPresentacion,
                    'dFechaRegistro'      => now(),
                ]);
            }

            // Actualizar supervisiones
            Supervision::where('IdDocumento', $documento->IdDocumento)->delete();

            if ($request->has('supervisiones') && is_array($request->supervisiones)) {
                foreach ($request->supervisiones as $sup) {
                    if (!isset($sup['IdEstudiante'])) continue;

                    Supervision::create([
                        'IdDocumento'       => $documento->IdDocumento,
                        'IdEstudiante'      => $sup['IdEstudiante'],
                        'cEstado'           => $sup['estado'] ?? 'Pendiente',
                        'IdUsuarioRegistro' => Auth::id(),
                        'dFechaRegistro'    => now(),
                    ]);
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
     * Eliminar documento, archivo y relaciones asociadas.
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $documento = Documento::findOrFail($id);

            if ($documento->cArchivo && Storage::disk('public')->exists($documento->cArchivo)) {
                Storage::disk('public')->delete($documento->cArchivo);
            }

            Supervision::where('IdDocumento', $documento->IdDocumento)->delete();
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





