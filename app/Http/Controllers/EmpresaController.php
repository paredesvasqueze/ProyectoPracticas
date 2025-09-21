<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;

class EmpresaController extends Controller
{
    // 🔹 Mostrar listado de empresas
    public function index()
    {
        $empresas = Empresa::all(); // Obtener todas las empresas
        return view('empresas.index', compact('empresas'));
    }

    // 🔹 Formulario de registro
    public function create()
    {
        return view('empresas.create');
    }

    // 🔹 Guardar empresa (solo visual)
    public function store(Request $request)
    {
        // Aquí normalmente guardarías la empresa, pero como es visual:
        return redirect()->route('empresas.index')->with('success', 'Empresa registrada correctamente (simulado).');
    }

    // 🔹 Formulario de edición
    public function edit($id)
    {
        $empresa = Empresa::findOrFail($id); // Busca la empresa o lanza 404
        return view('empresas.edit', compact('empresa'));
    }

    // 🔹 Actualizar empresa (solo visual)
    public function update(Request $request, $id)
    {
        // Normalmente actualizarías los datos, pero es solo visual:
        return redirect()->route('empresas.index')->with('success', 'Empresa actualizada correctamente (simulado).');
    }

    // 🔹 Eliminar empresa (solo visual)
    public function destroy($id)
    {
        // Normalmente eliminarías la empresa, pero es solo visual:
        return redirect()->route('empresas.index')->with('success', 'Empresa eliminada correctamente (simulado).');
    }
}


