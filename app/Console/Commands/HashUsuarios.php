<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class HashUsuarios extends Command
{
    protected $signature = 'usuarios:hash';
    protected $description = 'Actualizar todas las contraseñas de usuarios a bcrypt';

    public function handle()
    {
        $usuarios = Usuario::all();

        foreach ($usuarios as $usuario) {
            // Solo actualiza si la contraseña NO está en bcrypt
            if (!preg_match('/^\$2y\$/', $usuario->cContrasenia)) {
                $this->info("Actualizando usuario: {$usuario->cUsuario}");
                $usuario->cContrasenia = Hash::make($usuario->cContrasenia);
                $usuario->save();
            }
        }

        $this->info("Todas las contraseñas han sido actualizadas a bcrypt.");
        return 0;
    }
}

