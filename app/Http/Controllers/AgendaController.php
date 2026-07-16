<?php

namespace App\Http\Controllers;

use App\Models\OsTecnico;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\AgendaOs;
use App\Models\Tecnico;

class AgendaController extends Controller
{
   public function index(Request $request)
{
    $dataSelecionada = $request->get('data', now()->toDateString());

    $tecnicos = Tecnico::orderBy('nome')->get();

    $agenda = AgendaOs::with('osTecnico')
        ->whereDate('data', $dataSelecionada)
        ->orderBy('hora_inicio')
        ->get()
        ->groupBy('tecnico_id');

    return view('agenda.index', compact(
        'agenda',
        'tecnicos',
        'dataSelecionada'
    ));
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