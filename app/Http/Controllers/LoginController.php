<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;

class LoginController extends Controller
{
    // Mostrar formulario de login
    public function showLoginForm()
    {
        return view('login');
    }

    // Procesar login
    public function login(Request $request)
    {
        // Validar formulario
        $credentials = $request->validate([
            'usuario' => 'required|string',
            'password' => 'required|string',
        ]);

        // Buscar usuario por cUsuario
        $usuario = Usuario::where('cUsuario', $credentials['usuario'])->first();

        if (!$usuario) {
            return back()->withErrors([
                'usuario' => 'Usuario no encontrado.',
            ])->onlyInput('usuario');
        }

        // Verificar contraseña usando Hash::check con el nombre correcto de la columna
        if (!Hash::check($credentials['password'], $usuario->cContrasenia)) {
            return back()->withErrors([
                'usuario' => 'Contraseña incorrecta.',
            ])->onlyInput('usuario');
        }

        // Loguear usuario
        Auth::login($usuario);

        // Regenerar sesión para seguridad
        $request->session()->regenerate();

        // Redirigir al dashboard
        return redirect()->intended('dashboard');
    }

    // Dashboard
    public function dashboard()
    {
        $usuario = auth()->user();
        return view('dashboard', compact('usuario'));
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}













