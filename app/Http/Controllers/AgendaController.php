<?php

namespace App\Http\Controllers;

use App\Models\OsTecnico;

class AgendaController extends Controller
{
    public function index()
{
    $tecnicos = \App\Models\OsTecnico::orderBy('tecnico_nome')
        ->orderBy('ordem_servico')
        ->get()
        ->groupBy('tecnico_nome');

    return view('agenda.index', compact('tecnicos'));
}
}