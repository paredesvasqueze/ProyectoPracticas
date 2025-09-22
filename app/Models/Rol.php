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

    // Relación muchos a muchos con Usuarios
    public function usuarios()
    {
        return $this->belongsToMany(
            Usuario::class,
            'ROL_USUARIO', 
            'IdRol',       
            'IdUsuario',   
            'IdRol',       
            'IdUsuario'    
        );
    }
}




