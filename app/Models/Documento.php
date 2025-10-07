<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    use HasFactory;

    // =============================
    // Configuración del modelo
    // =============================
    protected $table = 'DOCUMENTO';
    protected $primaryKey = 'IdDocumento';
    public $timestamps = false;

    protected $fillable = [
        'cNroDocumento',
        'dFechaDocumento',
        'cTipoDocumento',
        'dFechaEntrega',
        'eDocumentoAdjunto',
        'IdEstudiante', // Relación con estudiante
    ];

    protected $casts = [
        'dFechaDocumento' => 'date',
        'dFechaEntrega'   => 'date',
    ];

    // =============================
    // Relaciones
    // =============================

    /**
     * Tipo de documento (constante)
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
     * Documentos supervisiones relacionados
     */
    public function documentoSupervisiones()
    {
        return $this->hasMany(DocumentoSupervision::class, 'IdDocumento', 'IdDocumento');
    }

    // =============================
    // Accesores
    // =============================

    /**
     * Obtener nombre completo del estudiante
     */
    public function getNombreEstudianteAttribute()
    {
        return $this->estudiante ? $this->estudiante->persona->cNombre . ' ' . $this->estudiante->persona->cApellido : null;
    }

    /**
     * Obtener DNI del estudiante
     */
    public function getDniEstudianteAttribute()
    {
        return $this->estudiante ? $this->estudiante->persona->cDNI : null;
    }

    /**
     * Determinar si el documento es de tipo Secretaría
     */
    public function isSecretaria()
    {
        return $this->tipoDocumento && stripos($this->tipoDocumento->nConstDescripcion, 'SECRETARÍA') !== false;
    }

    /**
     * Determinar si el documento es Memorándum
     */
    public function isMemorandum()
    {
        return $this->tipoDocumento && stripos($this->tipoDocumento->nConstDescripcion, 'MEMORÁNDUM') !== false;
    }
}






