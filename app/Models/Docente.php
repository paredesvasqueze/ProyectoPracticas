<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Docente extends Model
{
    protected $table = 'DOCENTE';
    protected $primaryKey = 'IdDocente';
    public $timestamps = false;

    protected $fillable = [
        'IdPersona',
        'nProgramaEstudios',
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'IdPersona', 'IdPersona');
    }
}

