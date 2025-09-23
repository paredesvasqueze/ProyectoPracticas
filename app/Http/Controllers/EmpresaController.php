<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;

class EmpresaController extends Controller
{
    // Mostrar listado de empresas con búsqueda
    public function index(Request $request)
    {
        $search = $request->input('search');

        $empresas = Empresa::query()
            ->when($search, function($query, $search) {
                $query->where('cNombreEmpresa', 'like', "%{$search}%")
                      ->orWhere('nRUC', 'like', "%{$search}%");
            })
            ->get();

        return view('empresas.index', compact('empresas'));
    }

    // Formulario de registro
    public function create()
    {
        return view('empresas.create');
    }

    // Guardar empresa en la base de datos
    public function store(Request $request)
    {
        $request->validate([
            'nTipoEmpresa' => 'required|string|max:20',
            'cNombreEmpresa' => 'required|string|max:100',
            'nRepresentanteLegal' => 'required|string|max:100',
            'nProfesion' => 'nullable|string|max:100',
            'nCargo' => 'nullable|string|max:20',
            'nRUC' => 'required|digits:11|unique:EMPRESA,nRUC',
            'cDireccion' => 'nullable|string|max:200',
            'cCorreo' => 'nullable|email|max:100',
            'nTelefono' => 'nullable|string|max:20',
        ], [
            'nTipoEmpresa.required' => 'El tipo de empresa es obligatorio.',
            'cNombreEmpresa.required' => 'El nombre de la empresa es obligatorio.',
            'nRepresentanteLegal.required' => 'El representante legal es obligatorio.',
            'nRUC.required' => 'El RUC es obligatorio.',
            'nRUC.digits' => 'El RUC debe tener exactamente 11 dígitos.',
            'nRUC.unique' => 'Este RUC ya está registrado.',
            'cCorreo.email' => 'El correo electrónico no es válido.',
        ]);

        Empresa::create([
            'nTipoEmpresa' => $request->nTipoEmpresa,
            'cNombreEmpresa' => $request->cNombreEmpresa,
            'nRepresentanteLegal' => $request->nRepresentanteLegal,
            'nProfesion' => $request->nProfesion,
            'nCargo' => $request->nCargo,
            'nRUC' => $request->nRUC,
            'cDireccion' => $request->cDireccion,
            'cCorreo' => $request->cCorreo,
            'nTelefono' => $request->nTelefono,
        ]);

        return redirect()->route('empresas.index')->with('success', 'Empresa registrada correctamente.');
    }

    // Formulario de edición
    public function edit($id)
    {
        $empresa = Empresa::findOrFail($id);
        return view('empresas.edit', compact('empresa'));
    }

    // Actualizar empresa
    public function update(Request $request, $id)
    {
        $empresa = Empresa::findOrFail($id);

        $request->validate([
            'nTipoEmpresa' => 'required|string|max:20',
            'cNombreEmpresa' => 'required|string|max:100',
            'nRepresentanteLegal' => 'required|string|max:100',
            'nProfesion' => 'nullable|string|max:100',
            'nCargo' => 'nullable|string|max:20',
            'nRUC' => 'required|digits:11|unique:EMPRESA,nRUC,' . $empresa->IdEmpresa . ',IdEmpresa',
            'cDireccion' => 'nullable|string|max:200',
            'cCorreo' => 'nullable|email|max:100',
            'nTelefono' => 'nullable|string|max:20',
        ], [
            'nTipoEmpresa.required' => 'El tipo de empresa es obligatorio.',
            'cNombreEmpresa.required' => 'El nombre de la empresa es obligatorio.',
            'nRepresentanteLegal.required' => 'El representante legal es obligatorio.',
            'nRUC.required' => 'El RUC es obligatorio.',
            'nRUC.digits' => 'El RUC debe tener exactamente 11 dígitos.',
            'nRUC.unique' => 'Este RUC ya está registrado.',
            'cCorreo.email' => 'El correo electrónico no es válido.',
        ]);

        $empresa->update([
            'nTipoEmpresa' => $request->nTipoEmpresa,
            'cNombreEmpresa' => $request->cNombreEmpresa,
            'nRepresentanteLegal' => $request->nRepresentanteLegal,
            'nProfesion' => $request->nProfesion,
            'nCargo' => $request->nCargo,
            'nRUC' => $request->nRUC,
            'cDireccion' => $request->cDireccion,
            'cCorreo' => $request->cCorreo,
            'nTelefono' => $request->nTelefono,
        ]);

        return redirect()->route('empresas.index')->with('success', 'Empresa actualizada correctamente.');
    }

    // Eliminar empresa
    public function destroy($id)
    {
        $empresa = Empresa::findOrFail($id);
        $empresa->delete();

        return redirect()->route('empresas.index')->with('success', 'Empresa eliminada correctamente.');
    }
}





