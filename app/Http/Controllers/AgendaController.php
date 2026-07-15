<?php

namespace App\Http\Controllers;

use App\Models\OsTecnico;
use Illuminate\Http\Request;
use App\Models\AgendaOs;

class AgendaController extends Controller
{
    public function index()
{
    $tecnicos = \App\Models\Tecnico::orderBy('nome')->get();

    $agenda = \App\Models\AgendaOs::with('osTecnico')
        ->orderBy('data')
        ->orderBy('hora_inicio')
        ->get()
        ->groupBy('tecnico_id');

    return view('agenda.index', compact('tecnicos', 'agenda'));
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