<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    protected $table = 'ESTUDIANTE';
    protected $primaryKey = 'IdEstudiante';
    public $timestamps = false;

    // =============================
    // Campos asignables
    // =============================
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

    /**
     * Relación con la tabla PERSONA
     * (Cada estudiante pertenece a una persona)
     */
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'IdPersona');
    }

    /**
     * Relación UNO a UNO con CARTA_PRESENTACION
     * (Cada estudiante tiene una carta de presentación)
     */
    public function cartaPresentacion()
    {
        return $this->hasOne(CartaPresentacion::class, 'IdEstudiante', 'IdEstudiante')
                    ->withDefault([
                        'nNroExpediente' => '—',
                        'cEstado' => 'Pendiente',
                    ]);
    }

    /**
     * Relación con la tabla EMPRESA a través de CARTA_PRESENTACION
     * (Centro de prácticas = Empresa)
     */
    public function empresa()
    {
        return $this->hasOneThrough(
            Empresa::class,               
            CartaPresentacion::class,     
            'IdEstudiante',               
            'IdEmpresa',                  
            'IdEstudiante',               
            'IdEmpresa'                   
        );
    }

    /**
     * Relación con la tabla CONSTANTE (Programa de Estudios)
     */
    public function programa()
    {
        return $this->belongsTo(Constante::class, 'nProgramaEstudios', 'nConstValor')
                    ->where('nConstGrupo', 'PROGRAMA_ESTUDIO');
    }

    /**
     * Relación con la tabla CONSTANTE (Plan de Estudios)
     */
    public function plan()
    {
        return $this->belongsTo(Constante::class, 'nPlanEstudio', 'nConstValor')
                    ->where('nConstGrupo', 'PLAN_ESTUDIO');
    }

    /**
     * Relación con la tabla CONSTANTE (Módulo Formativo)
     */
    public function modulo()
    {
        return $this->belongsTo(Constante::class, 'nModuloFormativo', 'nConstValor')
                    ->where('nConstGrupo', 'MODULO_FORMATIVO');
    }

    /**
     * Relación con la tabla CONSTANTE (Turno)
     */
    public function turno()
    {
        return $this->belongsTo(Constante::class, 'nTurno', 'nConstValor')
                    ->where('nConstGrupo', 'TURNO');
    }
}












