<?php

namespace App\Http\Controllers;

use App\Models\OsTecnico;
use Illuminate\Http\Request;
use App\Models\AgendaOs;

class AgendaController extends Controller
{
    public function index()
{
    dd([
        'tecnicos' => \App\Models\Tecnico::count(),
        'agenda' => \App\Models\AgendaOs::count(),
        'os' => \App\Models\OsTecnico::count(),
    ]);
}

public function mover(Request $request, AgendaOs $agendaOs)
{
    $agendaOs->update([
        'tecnico_id' => $request->tecnico_id,
        'hora_inicio' => $request->hora_inicio,
    ]);

    return response()->json([
        'success' => true
    ]);
}

}