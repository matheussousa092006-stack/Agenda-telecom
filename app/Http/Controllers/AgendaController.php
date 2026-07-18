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

        $visao = in_array($request->get('visao'), ['diaria', 'semanal'], true)
            ? $request->get('visao')
            : 'diaria';

        $regiao = in_array($request->get('regiao'), ['VA', 'GV'], true)
            ? $request->get('regiao')
            : 'VA';

        try {
            $data = Carbon::createFromFormat('Y-m-d', $request->get('data', now()->toDateString()))
                ->startOfDay();
        } catch (\Throwable) {
            $data = now()->startOfDay();
        }

        $dataSelecionada = $data->toDateString();

        $tecnicos = Tecnico::where('regiao', $regiao)
            ->orderBy('nome')
            ->get();

        $tecnicoSelecionado = null;
        $semanaInicio = null;
        $semanaFim = null;

        if ($visao === 'semanal') {
            $tecnicoSelecionado = $tecnicos->firstWhere('id', (int) $request->get('tecnico'))
                ?? $tecnicos->first();

            $semanaInicio = $data->copy()->startOfWeek(Carbon::MONDAY);
            $semanaFim = $semanaInicio->copy()->addDays(6);

            $agenda = $tecnicoSelecionado
                ? AgendaOs::with('osTecnico')
                    ->where('tecnico_id', $tecnicoSelecionado->id)
                    ->whereBetween('data', [$semanaInicio->toDateString(), $semanaFim->toDateString()])
                    ->orderBy('data')
                    ->orderBy('hora_inicio')
                    ->get()
                    ->groupBy(fn (AgendaOs $item) => Carbon::parse($item->data)->toDateString())
                : collect();

            $colunas = collect(range(0, 6))->map(function (int $dia) use (
                $agenda,
                $semanaInicio,
                $tecnicoSelecionado,
            ) {
                $dataColuna = $semanaInicio->copy()->addDays($dia);

                return [
                    'titulo' => ucfirst($dataColuna->translatedFormat('D')),
                    'subtitulo' => $dataColuna->format('d/m'),
                    'tecnico_id' => $tecnicoSelecionado?->id,
                    'data' => $dataColuna->toDateString(),
                    'itens' => $agenda->get($dataColuna->toDateString(), collect()),
                ];
            });
        } else {
            $agenda = AgendaOs::with('osTecnico')
                ->whereDate('data', $dataSelecionada)
                ->orderBy('hora_inicio')
                ->get()
                ->groupBy('tecnico_id');

            $colunas = $tecnicos->map(fn (Tecnico $tecnico) => [
                'titulo' => $tecnico->nome,
                'subtitulo' => null,
                'tecnico_id' => $tecnico->id,
                'data' => $dataSelecionada,
                'itens' => $agenda->get($tecnico->id, collect()),
            ]);
        }

        return view('agenda.index', compact(
            'colunas',
            'dataSelecionada',
            'regiao',
            'semanaFim',
            'semanaInicio',
            'tecnicoSelecionado',
            'tecnicos',
            'visao',
        ));
    }

    public function mover(Request $request, AgendaOs $agendaOs)
    {
        $dados = $request->validate([
            'tecnico_id' => ['required', 'integer', 'exists:tecnicos,id'],
            'data' => ['required', 'date_format:Y-m-d'],
            'hora_inicio' => ['required', 'date_format:H:i'],
        ]);

        $inicioAtual = Carbon::parse($agendaOs->hora_inicio);
        $fimAtual = $agendaOs->hora_fim
            ? Carbon::parse($agendaOs->hora_fim)
            : $inicioAtual->copy()->addHour();

        $duracaoEmMinutos = $inicioAtual->diffInMinutes($fimAtual);
        $novoInicio = Carbon::createFromFormat('H:i', $dados['hora_inicio']);
        $novoFim = $novoInicio->copy()->addMinutes($duracaoEmMinutos);

        if ($novoFim->gt(Carbon::createFromTime(23, 30))) {
            return response()->json([
                'message' => 'A atividade não pode terminar depois das 23:30.',
            ], 422);
        }

        if ($this->possuiConflito(
            $agendaOs,
            (int) $dados['tecnico_id'],
            $dados['data'],
            $novoInicio,
            $novoFim,
        )) {
            return response()->json([
                'message' => 'Este horário conflita com outra atividade do técnico.',
            ], 422);
        }

        $agendaOs->update([
            'tecnico_id' => $dados['tecnico_id'],
            'data' => $dados['data'],
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

        if ($fim->minute % 30 !== 0 || $fim->gt(Carbon::createFromTime(23, 30))) {
            return response()->json([
                'message' => 'Use intervalos de 30 minutos, com término até 23:30.',
            ], 422);
        }

        if ($this->possuiConflito(
            $agendaOs,
            (int) $agendaOs->tecnico_id,
            $agendaOs->data,
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
        string $data,
        Carbon $inicio,
        Carbon $fim,
    ): bool {
        return AgendaOs::query()
            ->where('tecnico_id', $tecnicoId)
            ->whereDate('data', $data)
            ->whereKeyNot($agendaOs->getKey())
            ->where('hora_inicio', '<', $fim->format('H:i:s'))
            ->where('hora_fim', '>', $inicio->format('H:i:s'))
            ->exists();
    }
}
