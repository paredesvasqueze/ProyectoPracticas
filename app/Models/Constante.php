<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Constante extends Model
{
    protected $table = 'CONSTANTE';
    protected $primaryKey = 'IdConstante'; 
    public $timestamps = false;

    protected $fillable = [
        'nConstId',
        'nConstGrupo',
        'nConstValor',
        'nConstDescripcion',
        'nConstEstado',
        'dFechaRegistro',
        'nConstOrden',
    ];
}
