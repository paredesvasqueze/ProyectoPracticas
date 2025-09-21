<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Usuario;

class Rol extends Model
{
    protected $table = 'ROL';
    protected $primaryKey = 'IdRol';
    public $timestamps = false;

    protected $fillable = ['cNombreRol'];

    // 🔹 Relación muchos a muchos con Usuarios
    public function usuarios()
    {
        return $this->belongsToMany(
            Usuario::class,
            'ROL_USUARIO', // tabla pivote
            'IdRol',       // FK de Rol en pivote
            'IdUsuario',   // FK de Usuario en pivote
            'IdRol',       // PK local (Rol)
            'IdUsuario'    // PK relacionado (Usuario)
        );
    }
}




