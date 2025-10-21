<?php

namespace App\Models;

class Reporte
{
    public $id;
    public $nombre;
    public $created_at;

    public function __construct($id, $nombre, $created_at)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->created_at = $created_at;
    }
}
