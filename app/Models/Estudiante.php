<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    protected $table = 'ESTUDIANTE';
    protected $primaryKey = 'IdEstudiante';
    public $timestamps = false;

    protected $fillable = [
        'IdPersona',
        'cCodigo',
        'cCarrera',
        'dFechaIngreso'
    ];

    // Relación con Persona
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'IdPersona');
    }

    // Relación con cartas de presentación
    public function cartasPresentacion()
    {
        return $this->hasMany(CartaPresentacion::class, 'IdEstudiante');
    }
}
