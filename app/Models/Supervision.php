<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supervision extends Model
{
    use HasFactory;

    /**
     * Nombre real de la tabla en la BD
     */
    protected $table = 'supervision'; 
    protected $primaryKey = 'IdSupervision';
    public $timestamps = false;

    /**
     * Campos que se pueden asignar masivamente
     */
    protected $fillable = [
        'IdDocente',
        'IdCartaPresentacion',
        'nNota',
        'dFechaInicio',
        'dFechaFin',
        'nHoras',
    ];

    /**
     * Relaciones
     */

    // Una supervisión pertenece a un docente
    public function docente()
    {
        return $this->belongsTo(Docente::class, 'IdDocente', 'IdDocente');
    }

    // Una supervisión pertenece a una carta de presentación
    public function cartaPresentacion()
    {
        return $this->belongsTo(CartaPresentacion::class, 'IdCartaPresentacion', 'IdCartaPresentacion');
    }

    // Una supervisión puede tener varios detalles
    public function detalles()
    {
        return $this->hasMany(SupervisionDetalle::class, 'IdSupervision', 'IdSupervision');
    }
}



