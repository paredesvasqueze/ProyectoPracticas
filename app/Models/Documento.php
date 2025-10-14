<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    use HasFactory;

    // =============================
    // CONFIGURACIÓN DEL MODELO
    // =============================
    protected $table = 'DOCUMENTO';
    protected $primaryKey = 'IdDocumento';
    public $timestamps = false;

    // =============================
    // CAMPOS ASIGNABLES
    // =============================
    protected $fillable = [
        'cNroDocumento',
        'dFechaDocumento',
        'cTipoDocumento',
        'dFechaEntrega',
        'eDocumentoAdjunto',
    ];

    // =============================
    // CASTS (CONVERSIÓN DE FECHAS)
    // =============================
    protected $casts = [
        'dFechaDocumento' => 'date',
        'dFechaEntrega'   => 'date',
    ];

    // =============================
    // RELACIONES
    // =============================

    /**
     * Tipo de documento (Constante)
     */
    public function tipoDocumento()
    {
        return $this->belongsTo(Constante::class, 'cTipoDocumento', 'IdConstante');
    }

    /**
     * Relación muchos a muchos con CartaPresentacion
     * mediante la tabla DOCUMENTO_CARTA
     */
    public function cartaPresentacion()
    {
        return $this->belongsToMany(
            CartaPresentacion::class,
            'DOCUMENTO_CARTA',
            'IdDocumento',
            'IdCartaPresentacion'
        )->withPivot('dFechaRegistro');
    }

    // =============================
    // ACCESORES Y MÉTODOS AUXILIARES
    // =============================

    /**
     * Devuelve el nombre completo del estudiante vinculado a la primera carta
     */
    public function getNombreEstudianteAttribute()
    {
        $carta = $this->cartaPresentacion->first();
        return $carta && $carta->estudiante && $carta->estudiante->persona
            ? trim($carta->estudiante->persona->cNombre . ' ' . $carta->estudiante->persona->cApellido)
            : '—';
    }

    /**
     * Devuelve el DNI del estudiante vinculado
     */
    public function getDniEstudianteAttribute()
    {
        $carta = $this->cartaPresentacion->first();
        return $carta && $carta->estudiante && $carta->estudiante->persona
            ? $carta->estudiante->persona->cDNI
            : '—';
    }

    /**
     * Devuelve el nombre del tipo de documento
     */
    public function getNombreTipoDocumentoAttribute()
    {
        return $this->tipoDocumento->nConstDescripcion ?? '—';
    }

    /**
     * Indica si el documento pertenece al tipo Secretaría
     */
    public function isSecretaria()
    {
        return $this->tipoDocumento &&
               stripos($this->tipoDocumento->nConstDescripcion, 'SECRETARÍA') !== false;
    }

    /**
     * Indica si el documento pertenece al tipo Memorándum
     */
    public function isMemorandum()
    {
        return $this->tipoDocumento &&
               stripos($this->tipoDocumento->nConstDescripcion, 'MEMORÁNDUM') !== false;
    }

    /**
     * Devuelve el estado de la carta vinculada
     */
    public function getEstadoCartaAttribute()
    {
        $carta = $this->cartaPresentacion->first();

        if (!$carta) {
            return 'No registrado';
        }

        if (is_numeric($carta->nEstado)) {
            return match ((int) $carta->nEstado) {
                1 => 'Aprobado',
                2 => 'Rechazado',
                0 => 'Pendiente',
                default => 'No registrado',
            };
        }

        return $carta->nEstado ?: 'No registrado';
    }
}










