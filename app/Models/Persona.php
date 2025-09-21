<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;

    protected $table = 'PERSONA';
    protected $primaryKey = 'IdPersona';
    public $timestamps = false;

    protected $fillable = [
        'cNombre',
        'cApellido',
        'cDNI',
        'cCorreo',
    ];

    // Relación: una persona tiene un usuario
    public function usuario()
    {
        return $this->hasOne(Usuario::class, 'IdPersona', 'IdPersona');
    }

    // Relación: una persona puede ser estudiante
    public function estudiante()
    {
        return $this->hasOne(Estudiante::class, 'IdPersona', 'IdPersona');
    }
}



