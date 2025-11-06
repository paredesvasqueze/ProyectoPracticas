<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Obtener alumnos que están por terminar sus prácticas (en los próximos 7 días)
        $alertas = DB::table('SUPERVISION')
            ->join('CARTA_PRESENTACION', 'SUPERVISION.IdCartaPresentacion', '=', 'CARTA_PRESENTACION.IdCartaPresentacion')
            ->join('ESTUDIANTE', 'CARTA_PRESENTACION.IdEstudiante', '=', 'ESTUDIANTE.IdEstudiante')
            ->join('PERSONA', 'ESTUDIANTE.IdPersona', '=', 'PERSONA.IdPersona')
            ->select(
                'PERSONA.cNombre',
                'PERSONA.cApellido',
                'SUPERVISION.dFechaFin'
            )
            ->where('SUPERVISION.dFechaFin', '>=', now()) 
            ->where('SUPERVISION.dFechaFin', '<=', now()->addDays(7)) 
            ->where(function ($q) {
                $q->whereNull('SUPERVISION.nEstado') 
                  ->orWhere('SUPERVISION.nEstado', '<>', 1);
            })
            ->orderBy('SUPERVISION.dFechaFin', 'asc')
            ->get();

        return view('dashboard', compact('alertas'));
    }
}

