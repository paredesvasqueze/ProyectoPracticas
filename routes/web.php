<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\CartaPresentacionController;
use App\Http\Controllers\EmpresaController; // 🔹 Nuevo controlador

// ===============================
// 🌐 Rutas públicas
// ===============================
Route::get('/', function () {
    return redirect()->route('login');
});

// Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

// ===============================
// 🔐 Rutas protegidas con middleware auth
// ===============================
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [LoginController::class, 'dashboard'])->name('dashboard');

    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // ===============================
    // 📌 Gestión de módulos
    // ===============================

    // Usuarios
    Route::resource('usuarios', UsuarioController::class);

    // Cartas de presentación (trámites)
    Route::resource('cartas', CartaPresentacionController::class);

    // 🔹 Empresas
    Route::resource('empresas', EmpresaController::class);
});








