<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartaPresentacion;
use App\Models\Estudiante;
use App\Models\Empresa;
use Illuminate\Support\Facades\Storage;

class CartaPresentacionController extends Controller
{
    // Listado de cartas con búsqueda por DNI
    public function index(Request $request)
    {
        $dni = $request->input('dni');

        $cartas = CartaPresentacion::with(['estudiante.persona', 'empresa'])
            ->when($dni, function($query, $dni) {
                $query->whereHas('estudiante.persona', function($q) use ($dni) {
                    $q->where('cDNI', 'like', "%$dni%");
                });
            })
            ->get();

        return view('cartas.index', compact('cartas'));
    }

    // Formulario de registro
    public function create()
    {
        $estudiantes = Estudiante::with('persona')->get();
        $empresas = Empresa::all();
        return view('cartas.create', compact('estudiantes', 'empresas'));
    }

    // Guardar trámite
    public function store(Request $request)
    {
        $data = $this->validateRequest($request);

        // Normalizar booleano
        $data['bPresentoSupervision'] = $request->bPresentoSupervision ? 1 : 0;

        // Subir archivo adjunto si existe
        if ($request->hasFile('adjunto')) {
            $data['adjunto'] = $request->file('adjunto')->store('cartas', 'public');
        }

        // Asignar fecha de registro
        $data['dFechaRegistro'] = now();

        CartaPresentacion::create($data);

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

        // Normalizar booleano
        $data['bPresentoSupervision'] = $request->bPresentoSupervision ? 1 : 0;

        // Reemplazar archivo adjunto si se sube uno nuevo
        if ($request->hasFile('adjunto')) {
            if ($carta->adjunto) {
                Storage::disk('public')->delete($carta->adjunto);
            }
            $data['adjunto'] = $request->file('adjunto')->store('cartas', 'public');
        }

        $carta->update($data);

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
        $carta->load(['estudiante.persona', 'empresa']); // Cargar relaciones
        return view('cartas.show', compact('carta'));
    }

    // ============================================================
    // Método privado para validar datos
    // ============================================================
    private function validateRequest(Request $request): array
    {
        return $request->validate([
            'IdEstudiante' => 'required|integer|exists:ESTUDIANTE,IdEstudiante',
            'IdEmpresa' => 'required|integer|exists:EMPRESA,IdEmpresa',
            'nNroExpediente' => 'required|string|max:50',
            'nNroCarta' => 'required|string|max:50',
            'nNroResibo' => 'nullable|string|max:50', 
            'dFechaCarta' => 'required|date',
            'dFechaRecojo' => 'nullable|date',
            'cObservacion' => 'nullable|string|max:255',
            'bPresentoSupervision' => 'nullable|boolean',
            'nEstado' => 'nullable|string|max:50',
            'adjunto' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);
    }
}







