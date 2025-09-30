<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupervisionDetalle extends Model
{
    use HasFactory;

    protected $table = 'supervision_detalle'; // nombre real de la tabla

    protected $primaryKey = 'IdSupervisionDetalle'; // clave primaria

    public $timestamps = false; // tu tabla no tiene created_at/updated_at

    protected $fillable = [
        'IdSupervision',
        'nNroSupervision',
        'dFechaSupervision',
    ];

    // Relación: un detalle pertenece a una supervisión
    public function supervision()
    {
        return $this->belongsTo(Supervision::class, 'IdSupervision', 'IdSupervision');
    }
}


