<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    use HasFactory;

    // Nombre de la tabla en la BD
    protected $table = 'DOCUMENTO';

    // Nombre de la PK
    protected $primaryKey = 'IdDocumento';

    protected $fillable = [
        'cNroDocumento',
        'dFechaDocumento',
        'cTipoDocumento',
        'dFechaEntrega',
        'eDocumentoAdjunto',
    ];

    public $timestamps = false;

    /**
     * Relación con la tabla CONSTANTE (tipo de documento).
     */
    public function tipoDocumento()
    {
        return $this->belongsTo(Constante::class, 'cTipoDocumento', 'IdConstante');
    }
}


