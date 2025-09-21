<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    // Tabla y PK
    protected $table = 'USUARIO';
    protected $primaryKey = 'IdUsuario';

    // ❌ Desactivar timestamps para evitar errores de created_at / updated_at
    public $timestamps = false;

    // Columnas fillable
    protected $fillable = [
        'IdPersona',
        'cUsuario',
        'cContrasenia',
    ];

    // Ocultar contraseña
    protected $hidden = [
        'cContrasenia',
    ];

    // Relación con Persona
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'IdPersona', 'IdPersona');
    }

    // Relación muchos a muchos con Roles
    public function roles()
    {
        return $this->belongsToMany(
            Rol::class,
            'ROL_USUARIO', // tabla pivote
            'IdUsuario',   // FK del usuario en la tabla pivote
            'IdRol',       // FK del rol en la tabla pivote
            'IdUsuario',   // PK local
            'IdRol'        // PK del modelo relacionado
        );
    }

    // 🔹 Método requerido por Auth
    public function getAuthPassword()
    {
        return $this->cContrasenia;
    }
}









