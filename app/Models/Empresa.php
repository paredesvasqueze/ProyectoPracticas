<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

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

    /**
     * Relación con cartas de presentación
     */
    public function cartasPresentacion()
    {
        return $this->hasMany(CartaPresentacion::class, 'IdEmpresa', 'IdEmpresa');
    }

    /**
     * Relación con CONSTANTE: Tipo de Empresa
     */
    public function tipoEmpresa()
    {
        return $this->belongsTo(Constante::class, 'nTipoEmpresa', 'nConstValor')
                    ->where('nConstGrupo', 'TIPO_EMPRESA');
    }

    /**
     * Relación con CONSTANTE: Profesión del representante legal
     */
    public function profesion()
    {
        return $this->belongsTo(Constante::class, 'nProfesion', 'nConstValor')
                    ->where('nConstGrupo', 'PROFESION');
    }

    /**
     * Relación con CONSTANTE: Cargo del representante legal
     */
    public function cargo()
    {
        return $this->belongsTo(Constante::class, 'nCargo', 'nConstValor')
                    ->where('nConstGrupo', 'CARGO');
    }
}





