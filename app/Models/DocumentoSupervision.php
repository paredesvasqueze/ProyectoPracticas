<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentoSupervision extends Model
{
    protected $table = 'DOCUMENTO_SUPERVISION';   
    protected $primaryKey = 'IdDocumentoSupervision'; 
    public $timestamps = false;  

    protected $fillable = [
        'dFechaRegistro',
        'dFechaSupervision',
        'nNroSupervision',
        'IdDocumento',
        'IdSupervision'
    ];

    /**
     * Relación con Documento.
     */
    public function documento()
    {
        return $this->belongsTo(Documento::class, 'IdDocumento');
    }

    /**
     * Relación con Supervisión.
     */
    public function supervision()
    {
        return $this->belongsTo(Supervision::class, 'IdSupervision');
    }
}
