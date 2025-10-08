<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    protected $table = 'ESTUDIANTE';
    protected $primaryKey = 'IdEstudiante';
    public $timestamps = false;

    // Campos que se pueden asignar en masa
    protected $fillable = [
        'IdPersona',
        'nProgramaEstudios',
        'nPlanEstudio',
        'nModuloFormativo',
        'nCelular',
        'nTurno',
    ];

    // =============================
    // Relaciones
    // =============================

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

    // Relaciones con la tabla CONSTANTE
    public function programa()
    {
        return $this->belongsTo(Constante::class, 'nProgramaEstudios', 'nConstValor')
                    ->where('nConstGrupo', 'PROGRAMA_ESTUDIO');
    }

    public function plan()
    {
        return $this->belongsTo(Constante::class, 'nPlanEstudio', 'nConstValor')
                    ->where('nConstGrupo', 'PLAN_ESTUDIO');
    }

    public function modulo()
    {
        return $this->belongsTo(Constante::class, 'nModuloFormativo', 'nConstValor')
                    ->where('nConstGrupo', 'MODULO_FORMATIVO');
    }

    public function turno()
    {
        return $this->belongsTo(Constante::class, 'nTurno', 'nConstValor')
                    ->where('nConstGrupo', 'TURNO');
    }
}









