<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\Constante;

class EmpresaController extends Controller
{
    /**
     * Mostrar listado de empresas con búsqueda
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $empresas = Empresa::query()
            ->when($search, function ($query, $search) {
                $query->where('cNombreEmpresa', 'like', "%{$search}%")
                    ->orWhere('nRUC', 'like', "%{$search}%");
            })
            ->orderBy('cNombreEmpresa', 'asc')
            ->get();

        return view('empresas.index', compact('empresas', 'search'));
    }

    /**
     * Formulario de registro
     */
    public function create()
    {
        return view('empresas.create', $this->getCombos());
    }

    /**
     * Guardar empresa en la base de datos
     */
    public function store(Request $request)
    {
        $request->validate($this->rules(), $this->messages());

        Empresa::create($request->all());

        return redirect()->route('empresas.index')
            ->with('success', 'Empresa registrada correctamente.');
    }

    /**
     * Formulario de edición
     */
    public function edit($id)
    {
        $empresa = Empresa::findOrFail($id);

        return view('empresas.edit', array_merge(
            ['empresa' => $empresa],
            $this->getCombos()
        ));
    }

    /**
     * Actualizar empresa
     */
    public function update(Request $request, $id)
    {
        $empresa = Empresa::findOrFail($id);

        $request->validate($this->rules($empresa->IdEmpresa), $this->messages());

        $empresa->update($request->all());

        return redirect()->route('empresas.index')
            ->with('success', 'Empresa actualizada correctamente.');
    }

    /**
     * Eliminar empresa
     */
    public function destroy($id)
    {
        $empresa = Empresa::findOrFail($id);
        $empresa->delete();

        return redirect()->route('empresas.index')
            ->with('success', 'Empresa eliminada correctamente.');
    }

    /**
     * Reglas de validación
     */
    private function rules($idEmpresa = null)
    {
        return [
            'nTipoEmpresa' => 'required|integer',
            'cNombreEmpresa' => 'required|string|max:100',
            'nRepresentanteLegal' => 'required|string|max:100',
            'nProfesion' => 'nullable|integer',
            'nCargo' => 'nullable|integer',
            'nRUC' => 'required|digits:11|unique:EMPRESA,nRUC,' . ($idEmpresa ?? 'NULL') . ',IdEmpresa',
            'cDireccion' => 'nullable|string|max:200',
            'cCorreo' => 'nullable|email|max:100',
            'nTelefono' => 'nullable|string|max:20',
        ];
    }

    /**
     * Mensajes personalizados
     */
    private function messages()
    {
        return [
            'nTipoEmpresa.required' => 'El tipo de empresa es obligatorio.',
            'cNombreEmpresa.required' => 'El nombre de la empresa es obligatorio.',
            'nRepresentanteLegal.required' => 'El representante legal es obligatorio.',
            'nRUC.required' => 'El RUC es obligatorio.',
            'nRUC.digits' => 'El RUC debe tener exactamente 11 dígitos.',
            'nRUC.unique' => 'Este RUC ya está registrado.',
            'cCorreo.email' => 'El correo electrónico no es válido.',
        ];
    }

    /**
     * Obtener listas de constantes para selects
     */
    private function getCombos()
    {
        return [
            'tiposEmpresa' => Constante::where('nConstGrupo', 'TIPO_EMPRESA')
                ->where('nConstEstado', 1)
                ->orderBy('nConstOrden')
                ->get(),

            'profesiones' => Constante::where('nConstGrupo', 'PROFESION')
                ->where('nConstEstado', 1)
                ->orderBy('nConstOrden')
                ->get(),

            'cargos' => Constante::where('nConstGrupo', 'CARGO')
                ->where('nConstEstado', 1)
                ->orderBy('nConstOrden')
                ->get(),
        ];
    }
}









