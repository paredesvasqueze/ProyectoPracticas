<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartaPresentacion;
use App\Models\Estudiante;
use App\Models\Empresa;
use Illuminate\Support\Facades\Storage;

class CartaPresentacionController extends Controller
{
    // 🔹 Listado de cartas
    public function index()
    {
        $cartas = CartaPresentacion::with(['estudiante.persona', 'empresa'])->get();
        return view('cartas.index', compact('cartas'));
    }

    // 🔹 Formulario de registro
    public function create()
    {
        $estudiantes = Estudiante::with('persona')->get();
        $empresas = Empresa::all();
        return view('cartas.create', compact('estudiantes', 'empresas'));
    }

    // 🔹 Guardar trámite
    public function store(Request $request)
    {
        $data = $this->validateRequest($request);

        $carta = new CartaPresentacion($data);

        // Subir archivo
        if ($request->hasFile('adjunto')) {
            $carta->adjunto = $request->file('adjunto')->store('cartas', 'public');
        }

        $carta->dFechaRegistro = now();
        $carta->save();

        return redirect()->route('cartas.index')->with('success', 'Trámite registrado correctamente.');
    }

    // 🔹 Formulario de edición
    public function edit(CartaPresentacion $carta)
    {
        $estudiantes = Estudiante::with('persona')->get();
        $empresas = Empresa::all();
        return view('cartas.edit', compact('carta', 'estudiantes', 'empresas'));
    }

    // 🔹 Actualizar trámite
    public function update(Request $request, CartaPresentacion $carta)
    {
        $data = $this->validateRequest($request);

        $carta->fill($data);

        // Reemplazar archivo si existe uno nuevo
        if ($request->hasFile('adjunto')) {
            if ($carta->adjunto) {
                Storage::disk('public')->delete($carta->adjunto);
            }
            $carta->adjunto = $request->file('adjunto')->store('cartas', 'public');
        }

        $carta->save();

        return redirect()->route('cartas.index')->with('success', 'Trámite actualizado correctamente.');
    }

    // 🔹 Eliminar trámite
    public function destroy(CartaPresentacion $carta)
    {
        if ($carta->adjunto) {
            Storage::disk('public')->delete($carta->adjunto);
        }
        $carta->delete();

        return redirect()->route('cartas.index')->with('success', 'Trámite eliminado correctamente.');
    }

    // 🔹 Ver detalle
    public function show(CartaPresentacion $carta)
    {
        return view('cartas.show', compact('carta'));
    }

    // ============================================================
    // 📌 Método privado para validar datos
    // ============================================================
    private function validateRequest(Request $request): array
    {
        return $request->validate([
            'IdEstudiante' => 'required|integer',
            'IdEmpresa' => 'required|integer',
            'nNroExpediente' => 'required|string|max:50',
            'nNroCarta' => 'required|string|max:50',
            'dFechaCarta' => 'required|date',
            'dFechaRecojo' => 'nullable|date',
            'nNroResibo' => 'nullable|string|max:50',
            'cObservacion' => 'nullable|string|max:255',
            'bPresentoSupervision' => 'nullable|boolean',
            'nEstado' => 'nullable|string|max:50',
            'adjunto' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);
    }
}


