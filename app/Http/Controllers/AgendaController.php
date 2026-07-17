<?php

namespace App\Http\Controllers;

use App\Models\AgendaOs;
use App\Models\Tecnico;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    public function index(Request $request)
    {
        Carbon::setLocale('pt_BR');

        $dataSelecionada = $request->get('data', now()->toDateString());

        $regiao = $request->get('regiao', 'VA');

        $tecnicos = Tecnico::where('regiao', $regiao)
            ->orderBy('nome')
            ->get();

        $agenda = AgendaOs::with('osTecnico')
            ->whereDate('data', $dataSelecionada)
            ->orderBy('hora_inicio')
            ->get()
            ->groupBy('tecnico_id');

        return view('agenda.index', compact(
            'agenda',
            'tecnicos',
            'dataSelecionada',
            'regiao'
        ));
    }

    public function mover(Request $request, AgendaOs $agendaOs)
    {
        $dados = $request->validate([
            'tecnico_id' => ['required', 'integer', 'exists:tecnicos,id'],
            'hora_inicio' => ['required', 'date_format:H:i'],
        ]);

        $inicioAtual = Carbon::parse($agendaOs->hora_inicio);
        $fimAtual = $agendaOs->hora_fim
            ? Carbon::parse($agendaOs->hora_fim)
            : $inicioAtual->copy()->addHour();

        $duracaoEmMinutos = $inicioAtual->diffInMinutes($fimAtual);
        $novoInicio = Carbon::createFromFormat('H:i', $dados['hora_inicio']);
        $novoFim = $novoInicio->copy()->addMinutes($duracaoEmMinutos);

        if ($novoFim->gt(Carbon::createFromTime(18))) {
            return response()->json([
                'message' => 'A atividade não pode terminar depois das 18:00.',
            ], 422);
        }

        if ($this->possuiConflito(
            $agendaOs,
            (int) $dados['tecnico_id'],
            $novoInicio,
            $novoFim,
        )) {
            return response()->json([
                'message' => 'Este horário conflita com outra atividade do técnico.',
            ], 422);
        }

        $agendaOs->update([
            'tecnico_id' => $dados['tecnico_id'],
            'hora_inicio' => $novoInicio->format('H:i:s'),
            'hora_fim' => $novoFim->format('H:i:s'),
        ]);

        return response()->json([
            'success' => true,
        ]);
    }

    public function atualizarDuracao(Request $request, AgendaOs $agendaOs)
    {
        $dados = $request->validate([
            'hora_fim' => ['required', 'date_format:H:i'],
        ]);

        $inicio = Carbon::parse($agendaOs->hora_inicio);
        $fim = Carbon::createFromFormat('H:i', $dados['hora_fim']);

        if ($fim->lte($inicio) || $inicio->diffInMinutes($fim) < 30) {
            return response()->json([
                'message' => 'A atividade precisa ter pelo menos 30 minutos.',
            ], 422);
        }

        if ($fim->minute % 30 !== 0 || $fim->gt(Carbon::createFromTime(18))) {
            return response()->json([
                'message' => 'Use intervalos de 30 minutos, com término até 18:00.',
            ], 422);
        }

        if ($this->possuiConflito(
            $agendaOs,
            (int) $agendaOs->tecnico_id,
            $inicio,
            $fim,
        )) {
            return response()->json([
                'message' => 'Este período conflita com outra atividade do técnico.',
            ], 422);
        }

        $agendaOs->update([
            'hora_fim' => $fim->format('H:i:s'),
        ]);

        return response()->json([
            'success' => true,
            'hora_fim' => $fim->format('H:i'),
        ]);
    }

    private function possuiConflito(
        AgendaOs $agendaOs,
        int $tecnicoId,
        Carbon $inicio,
        Carbon $fim,
    ): bool {
        return AgendaOs::query()
            ->where('tecnico_id', $tecnicoId)
            ->whereDate('data', $agendaOs->data)
            ->whereKeyNot($agendaOs->getKey())
            ->where('hora_inicio', '<', $fim->format('H:i:s'))
            ->where('hora_fim', '>', $inicio->format('H:i:s'))
            ->exists();
    }
}
