<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\CartaPresentacionController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\SupervisionController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\DocumentoSupervisionController;

// ===============================
// Rutas públicas
// ===============================
Route::get('/', function () {
    return redirect()->route('login');
});

// Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

// ===============================
// Rutas protegidas con middleware auth
// ===============================
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [LoginController::class, 'dashboard'])->name('dashboard');

    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // ===============================
    // Gestión de módulos
    // ===============================

    // Usuarios
    Route::resource('usuarios', UsuarioController::class);

    // Cartas de presentación (trámites)
    Route::resource('cartas', CartaPresentacionController::class);

    // Empresas
    Route::resource('empresas', EmpresaController::class);

    // Estudiantes
    Route::resource('estudiantes', EstudianteController::class);

    // Docentes
    Route::resource('docentes', DocenteController::class);

    // Supervisiones
    Route::resource('supervisiones', SupervisionController::class);

    // Documentos
    Route::resource('documentos', DocumentoController::class);

    // Documento Supervisiones
    Route::resource('documento_supervisiones', DocumentoSupervisionController::class);

    // ===============================
    // 🔍 Rutas especiales de búsqueda AJAX
    // ===============================

    // Buscar estudiante por DNI o nombre (para autocompletar en memorándum y secretaria)
    Route::get('/buscar-estudiante', [EstudianteController::class, 'buscar'])
        ->name('buscar.estudiante');

    // Buscar persona asociada al estudiante (para otros formularios, si aplica)
    Route::get('/documentos/buscar-persona', [EstudianteController::class, 'buscarPersona'])
        ->name('documentos.buscar-persona');
});

















