<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supervision extends Model
{
    use HasFactory;

    protected $table = 'SUPERVISION';
    protected $primaryKey = 'IdSupervision';
    public $timestamps = false;

    protected $fillable = [
        'IdDocente',
        'IdCartaPresentacion',
        'nNota',
        'dFechaInicio',
        'dFechaFin',
        'nHoras',
        'nEstado',
        'nOficina',
    ];

    // ==========================
    // RELACIONES
    // ==========================

    // Una supervisión pertenece a un docente
    public function docente()
    {
        return $this->belongsTo(Docente::class, 'IdDocente', 'IdDocente')
                    ->with('persona'); // permite acceder al nombre del docente
    }

    // Una supervisión pertenece a una carta de presentación
    public function cartaPresentacion()
    {
        return $this->belongsTo(CartaPresentacion::class, 'IdCartaPresentacion', 'IdCartaPresentacion')
                    ->with('estudiante.persona');
    }

    // Una supervisión tiene varios detalles
    public function detalles()
    {
        return $this->hasMany(SupervisionDetalle::class, 'IdSupervision', 'IdSupervision');
    }

    // ==========================
    // MÉTODOS PERSONALIZADOS
    // ==========================

    public static function crearConDetalles(array $data, array $detallesData = [])
    {
        $supervision = self::create($data);

        if (!empty($detallesData)) {
            foreach ($detallesData as $detalle) {
                $supervision->detalles()->create([
                    'nNroSupervision'   => $detalle['nNroSupervision'] ?? null,
                    'dFechaSupervision' => $detalle['dFechaSupervision'] ?? null,
                    'cObservacion'      => $detalle['cObservacion'] ?? null,
                    'nPuntaje'          => $detalle['nPuntaje'] ?? null,
                ]);
            }
        }

        return $supervision;
    }
}

