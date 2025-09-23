<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = 'EMPRESA';
    protected $primaryKey = 'IdEmpresa';
    public $timestamps = false;

    protected $fillable = [
        'nTipoEmpresa',
        'cNombreEmpresa',
        'nRepresentanteLegal',
        'nProfesion',
        'nCargo',
        'nRUC',
        'cDireccion',
        'cCorreo',
        'nTelefono'
    ];

    // Relación con cartas de presentación
    public function cartasPresentacion()
    {
        return $this->hasMany(CartaPresentacion::class, 'IdEmpresa');
    }
}


