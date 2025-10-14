<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DocumentoCarta extends Model
{
    use HasFactory;

    /**
     * ============================
     * CONFIGURACIÓN DEL MODELO
     * ============================
     */
    protected $table = 'DOCUMENTO_CARTA';
    protected $primaryKey = 'IdDocumentoCarta';
    public $timestamps = false;

    protected $fillable = [
        'dFechaRegistro',
        'IdDocumento',
        'IdCartaPresentacion',
    ];

    /**
     * ============================
     * CONVERSIONES DE DATOS
     * ============================
     */
    protected $casts = [
        'dFechaRegistro' => 'date',
    ];

    /**
     * ============================
     * RELACIONES
     * ============================
     */

    // 📄 Relación con DOCUMENTO
    public function documento()
    {
        return $this->belongsTo(Documento::class, 'IdDocumento', 'IdDocumento');
    }

    // 📨 Relación con CARTA_PRESENTACION
    public function cartaPresentacion()
    {
        return $this->belongsTo(CartaPresentacion::class, 'IdCartaPresentacion', 'IdCartaPresentacion');
    }

    /**
     * ============================
     * EVENTOS DEL MODELO
     * ============================
     */
    protected static function booted()
    {
        // Cuando se crea un nuevo registro, asignar automáticamente la fecha actual
        static::creating(function ($model) {
            if (empty($model->dFechaRegistro)) {
                $model->dFechaRegistro = Carbon::now()->format('Y-m-d');
            }
        });

        // Evitar que la fecha de registro sea modificada después de creada
        static::updating(function ($model) {
            if ($model->isDirty('dFechaRegistro')) {
                $model->dFechaRegistro = $model->getOriginal('dFechaRegistro');
            }
        });
    }

    /**
     * ============================
     * ACCESORES Y FORMATO
     * ============================
     */

    // Formato de fecha (para mostrar en vistas)
    public function getFechaRegistroFormateadaAttribute()
    {
        return $this->dFechaRegistro
            ? Carbon::parse($this->dFechaRegistro)->format('d/m/Y')
            : '—';
    }

    // Nombre del documento relacionado
    public function getNombreDocumentoAttribute()
    {
        return $this->documento->cNroDocumento ?? '—';
    }

    // Número de carta de presentación relacionado
    public function getNumeroCartaAttribute()
    {
        return $this->cartaPresentacion->nNroCarta ?? '—';
    }
}



