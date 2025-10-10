<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CartaPresentacion extends Model
{
    use HasFactory;

    protected $table = 'CARTA_PRESENTACION';
    protected $primaryKey = 'IdCartaPresentacion';
    public $timestamps = false;

    // =============================
    // Campos asignables
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
        'adjunto'
    ];

    // =============================
    // Conversión de tipos
    // =============================
    protected $casts = [
        'dFechaCarta' => 'date',
        'dFechaRecojo' => 'date',
        'dFechaRegistro' => 'datetime',
        'bPresentoSupervision' => 'boolean',
    ];

    // =============================
    // Relaciones
    // =============================

    /**
     * Relación con el estudiante
     */
    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'IdEstudiante', 'IdEstudiante');
    }

    /**
     * Relación con la empresa
     */
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'IdEmpresa', 'IdEmpresa');
    }

    /**
     * Relación con los documentos (vía tabla intermedia DOCUMENTO_CARTA)
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

    // =============================
    // Accesores personalizados
    // =============================

    /**
     * Obtener nombre legible del estado
     */
    public function getNombreEstadoAttribute()
    {
        switch ($this->nEstado) {
            case 0:
                return 'Pendiente';
            case 1:
                return 'Activo';
            case 2:
                return 'Finalizado';
            case 3:
                return 'Anulado';
            default:
                return '—';
        }
    }

    // =============================
    // Scopes útiles
    // =============================

    /**
     * Scope para cartas activas
     */
    public function scopeActivas($query)
    {
        return $query->where('nEstado', 1);
    }

    /**
     * Scope para ordenar por fecha de registro descendente
     */
    public function scopeRecientes($query)
    {
        return $query->orderByDesc('dFechaRegistro');
    }
}




