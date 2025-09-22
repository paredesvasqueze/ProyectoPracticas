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

    // Campos que se pueden llenar masivamente
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

    // Casts para tipos correctos
    protected $casts = [
        'dFechaCarta' => 'date',
        'dFechaRecojo' => 'date',
        'dFechaRegistro' => 'datetime',
        'bPresentoSupervision' => 'boolean',
    ];

    // Relación con Estudiante
    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'IdEstudiante', 'IdEstudiante');
    }

    // Relación con Empresa
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'IdEmpresa', 'IdEmpresa');
    }
}



