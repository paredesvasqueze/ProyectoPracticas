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
        'nProgramaEstudios',
        'nPlanEstudio',
        'nModuloFormativo',
        'nTurno',
        'nCelular',
        'cCentroPracticas', // centro de prácticas
    ];

    // =============================
    // Relaciones
    // =============================

    // Relación con Persona
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'IdPersona');
    }

    // Cartas de presentación
    public function cartasPresentacion()
    {
        return $this->hasMany(CartaPresentacion::class, 'IdEstudiante');
    }

    // Programa de estudios
    public function programa()
    {
        return $this->belongsTo(Constante::class, 'nProgramaEstudios', 'nConstValor')
                    ->where('nConstGrupo', 'PROGRAMA_ESTUDIO');
    }

    // Plan de estudios
    public function plan()
    {
        return $this->belongsTo(Constante::class, 'nPlanEstudio', 'nConstValor')
                    ->where('nConstGrupo', 'PLAN_ESTUDIO');
    }

    // Módulo formativo
    public function modulo()
    {
        return $this->belongsTo(Constante::class, 'nModuloFormativo', 'nConstValor')
                    ->where('nConstGrupo', 'MODULO_FORMATIVO');
    }

    // Turno
    public function turno()
    {
        return $this->belongsTo(Constante::class, 'nTurno', 'nConstValor')
                    ->where('nConstGrupo', 'TURNO');
    }

    // =============================
    // Accesores personalizados
    // =============================

    // Nombre completo del estudiante
    public function getNombreCompletoAttribute()
    {
        return $this->persona ? trim("{$this->persona->cNombre} {$this->persona->cApellido}") : '';
    }

    // DNI del estudiante
    public function getDniAttribute()
    {
        return $this->persona->cDNI ?? '';
    }

    // Último número de expediente (para MEMORÁNDUM)
    public function getNroExpedienteAttribute()
    {
        $carta = $this->cartasPresentacion()->latest('IdCartaPresentacion')->first();
        return $carta ? $carta->nNroExpediente : '';
    }

    // Nombre del programa
    public function getProgramaNombreAttribute()
    {
        return $this->programa->nConstDescripcion ?? '';
    }

    // Centro de prácticas
    public function getCentroPracticasAttribute()
    {
        return $this->cCentroPracticas ?? '';
    }

    // Módulo formativo (nombre)
    public function getModuloNombreAttribute()
    {
        return $this->modulo->nConstDescripcion ?? '';
    }

    // Turno (nombre)
    public function getTurnoNombreAttribute()
    {
        return $this->turno->nConstDescripcion ?? '';
    }
}









