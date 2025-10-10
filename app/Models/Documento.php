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

    protected $fillable = [
        'cNumeroExpediente',   
        'dFechaDocumento',
        'cTipoDocumento',
        'dFechaEntrega',
        'eDocumentoAdjunto',
        'IdEstudiante',
        'IdUsuarioRegistro',
        'dFechaRegistro',
    ];

    protected $casts = [
        'dFechaDocumento' => 'date',
        'dFechaEntrega'   => 'date',
        'dFechaRegistro'  => 'datetime',
    ];

    // =============================
    // RELACIONES
    // =============================

    /**
     * Tipo de documento (tabla CONSTANTE)
     */
    public function tipoDocumento()
    {
        return $this->belongsTo(Constante::class, 'cTipoDocumento', 'IdConstante');
    }

    /**
     * Estudiante relacionado
     */
    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'IdEstudiante', 'IdEstudiante');
    }

    /**
     * Supervisiones relacionadas al documento
     */
    public function supervisiones()
    {
        return $this->hasMany(DocumentoSupervision::class, 'IdDocumento', 'IdDocumento');
    }

    /**
     * Relación con Carta de Presentación (vía tabla intermedia DOCUMENTO_CARTA)
     */
    public function cartaPresentacion()
    {
        return $this->belongsToMany(
            CartaPresentacion::class,
            'DOCUMENTO_CARTA',
            'IdDocumento',
            'IdCartaPresentacion'
        )
        ->withPivot('dFechaRegistro')
        ->with('empresa', 'estudiante.persona'); 
    }

    // =============================
    // ACCESORES Y MÉTODOS DERIVADOS
    // =============================

    /**
     * Obtener nombre completo del estudiante
     */
    public function getNombreEstudianteAttribute()
    {
        return $this->estudiante
            ? trim($this->estudiante->persona->cNombre . ' ' . $this->estudiante->persona->cApellido)
            : '—';
    }

    /**
     * Obtener DNI del estudiante
     */
    public function getDniEstudianteAttribute()
    {
        return $this->estudiante->persona->cDNI ?? '—';
    }

    /**
     * Obtener el nombre del tipo de documento desde la tabla Constante
     */
    public function getNombreTipoDocumentoAttribute()
    {
        return $this->tipoDocumento->nConstDescripcion ?? '—';
    }

    /**
     * Determinar si el documento es de tipo Secretaría
     */
    public function isSecretaria()
    {
        return $this->tipoDocumento &&
               stripos($this->tipoDocumento->nConstDescripcion, 'SECRETARÍA') !== false;
    }

    /**
     * Determinar si el documento es Memorándum
     */
    public function isMemorandum()
    {
        return $this->tipoDocumento &&
               stripos($this->tipoDocumento->nConstDescripcion, 'MEMORÁNDUM') !== false;
    }

    /**
     * Obtener el estado real de la carta asociada (si existe)
     */
    public function getEstadoCartaAttribute()
    {
        $carta = $this->cartaPresentacion->first();

        if (!$carta) {
            return 'No registrado';
        }

        // Si el estado es numérico (0,1,2)
        if (is_numeric($carta->nEstado)) {
            switch ((int) $carta->nEstado) {
                case 1: return 'Aprobado';
                case 2: return 'Rechazado';
                case 0: return 'Pendiente';
                default: return 'No registrado';
            }
        }

        // Si es texto directo
        return $carta->nEstado ?: 'No registrado';
    }
}




