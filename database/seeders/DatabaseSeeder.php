<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Rol;
use App\Models\Persona;
use App\Models\Usuario;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ================================
        // Crear Roles
        // ================================
        $rolAdmin = Rol::create(['cNombreRol' => 'Administrador']);
        $rolUser  = Rol::create(['cNombreRol' => 'Usuario']);

        // ================================
        // Crear Persona admin
        // ================================
        $persona = Persona::create([
            'cNombre'   => 'Administrador',
            'cApellido' => 'Principal',
            'cDNI'      => '12345678',
            'cCorreo'   => 'admin@example.com',
        ]);

        // ================================
        // Crear Usuario admin con contraseña Bcrypt
        // ================================
        $usuario = Usuario::create([
            'IdPersona'    => $persona->IdPersona,
            'cUsuario'     => 'admin',
            'cContrasenia' => Hash::make('123456'),
        ]);

        // ================================
        // Asignar rol Administrador al usuario
        // ================================
        $usuario->roles()->attach($rolAdmin->IdRol);

    }
}







