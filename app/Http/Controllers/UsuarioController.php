<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Persona;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = Usuario::with('persona', 'roles')->get();
        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        $roles = Rol::all();
        return view('usuarios.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cNombre' => 'required|string|max:100',
            'cApellido' => 'required|string|max:100',
            'cDNI' => 'required|string|max:20|unique:persona,cDNI',
            'cCorreo' => 'required|email|max:100|unique:persona,cCorreo',
            'cUsuario' => 'required|string|max:50|unique:usuario,cUsuario',
            'cContrasenia' => 'required|string|min:6',
            'roles' => 'required|array',
        ]);

        $persona = Persona::create([
            'cNombre' => $request->cNombre,
            'cApellido' => $request->cApellido,
            'cDNI' => $request->cDNI,
            'cCorreo' => $request->cCorreo,
        ]);

        $usuario = Usuario::create([
            'IdPersona' => $persona->IdPersona,
            'cUsuario' => $request->cUsuario,
            'cContrasenia' => Hash::make($request->cContrasenia),
        ]);

        $usuario->roles()->sync($request->roles);

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente.');
    }

    public function edit($id)
    {
        $usuario = Usuario::with('persona', 'roles')->findOrFail($id);
        $roles = Rol::all();
        $usuarioRoles = $usuario->roles->pluck('IdRol')->toArray();
        return view('usuarios.edit', compact('usuario', 'roles', 'usuarioRoles'));
    }

    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);
        $persona = $usuario->persona;

        $request->validate([
            'cNombre' => 'required|string|max:100',
            'cApellido' => 'required|string|max:100',
            'cDNI' => 'required|string|max:20|unique:persona,cDNI,' . $persona->IdPersona . ',IdPersona',
            'cCorreo' => 'required|email|max:100|unique:persona,cCorreo,' . $persona->IdPersona . ',IdPersona',
            'cUsuario' => 'required|string|max:50|unique:usuario,cUsuario,' . $usuario->IdUsuario . ',IdUsuario',
            'cContrasenia' => 'nullable|string|min:6',
            'roles' => 'required|array',
        ]);

        $persona->update([
            'cNombre' => $request->cNombre,
            'cApellido' => $request->cApellido,
            'cDNI' => $request->cDNI,
            'cCorreo' => $request->cCorreo,
        ]);

        $usuario->cUsuario = $request->cUsuario;
        if ($request->filled('cContrasenia')) {
            $usuario->cContrasenia = Hash::make($request->cContrasenia);
        }
        $usuario->save();

        $usuario->roles()->sync($request->roles);

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy($id)
    {
        $usuario = Usuario::findOrFail($id);
        $persona = $usuario->persona;

        $usuario->roles()->detach();
        $usuario->delete();
        $persona->delete();

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente.');
    }
}




