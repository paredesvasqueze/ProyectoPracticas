<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CartaPresentacion extends Model
{
    use HasFactory;

    // =============================
    // CONFIGURACIÓN DEL MODELO
    // =============================
    protected $table = 'CARTA_PRESENTACION';
    protected $primaryKey = 'IdCartaPresentacion';
    public $timestamps = false;

    // =============================
    // CAMPOS ASIGNABLES
    // =============================
    protected $fillable = [
        'IdEstudiante',
        'nNroExpediente',
        'nNroCarta',
        'dFechaCarta',
        'dFechaRecojo',
        'nNroResibo',
        'cObservacion',
        'bPresentoSupervision',
        'IdEmpresa',
        'nEstado',
        'dFechaRegistro',
        'adjunto',
    ];

    // =============================
    // CONVERSIÓN DE TIPOS (CASTS)
    // =============================
    protected $casts = [
        'dFechaCarta' => 'date',
        'dFechaRecojo' => 'date',
        'dFechaRegistro' => 'datetime',
        'bPresentoSupervision' => 'boolean',
    ];

    // =============================
    // RELACIONES
    // =============================

    /**
     * Una carta pertenece a un estudiante
     */
    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'IdEstudiante', 'IdEstudiante');
    }

    /**
     * Una carta pertenece a una empresa
     */
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'IdEmpresa', 'IdEmpresa');
    }

    /**
     * Relación con los documentos (tabla intermedia DOCUMENTO_CARTA)
     */
    public function documentos()
    {
        return $this->belongsToMany(
            Documento::class,
            'DOCUMENTO_CARTA',
            'IdCartaPresentacion',
            'IdDocumento'
        )->withPivot('dFechaRegistro');
    }

    /**
     * Relación con la supervisión (una carta puede tener una supervisión)
     */
    public function supervision()
    {
        return $this->hasOne(Supervision::class, 'IdCartaPresentacion', 'IdCartaPresentacion');
    }

    // =============================
    // ACCESORES PERSONALIZADOS
    // =============================

    /**
     * Devuelve el nombre legible del estado actual de la carta
     */
    public function getNombreEstadoAttribute()
    {
        return match ((int) $this->nEstado) {
            0 => 'En proceso',
            1 => 'En coordinación',
            2 => 'En jefatura académica',
            3 => 'En JUA',
            4 => 'Observado',
            5 => 'Entregado',
            default => '—',
        };
    }

    // Alias adicional (por si se accede como $carta->nombre_estado)
    public function getnombre_estadoAttribute()
    {
        return $this->getNombreEstadoAttribute();
    }

    // =============================
    // SCOPES PERSONALIZADOS
    // =============================

    /**
     * Scope: cartas activas (en proceso)
     */
    public function scopeActivas($query)
    {
        return $query->where('nEstado', 0);
    }

    /**
     * Scope: ordenar por fecha de registro descendente
     */
    public function scopeRecientes($query)
    {
        return $query->orderByDesc('dFechaRegistro');
    }

    /**
     * Scope: cartas con supervisión finalizada (para INFORME)
     * Solo las que tienen supervisión con nEstado = 2
     */
    public function scopeConSupervisionFinalizada($query)
    {
        return $query->whereHas('supervision', function ($q) {
            $q->where('nEstado', 2);
        });
    }

    /**
     * Scope: cartas sin supervisión finalizada (para MEMORÁNDUM)
     * Incluye las que no tienen supervisión o las que tienen nEstado distinto de 2
     */
    public function scopeSinSupervisionFinalizada($query)
    {
        return $query->where(function ($q) {
            $q->whereDoesntHave('supervision')
              ->orWhereHas('supervision', function ($sub) {
                  $sub->where('nEstado', '!=', 2);
              });
        });
    }
}










