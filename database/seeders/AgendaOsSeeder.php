<?php

namespace Database\Seeders;

use App\Models\AgendaOs;
use App\Models\OsTecnico;
use App\Models\Tecnico;
use Illuminate\Database\Seeder;

class AgendaOsSeeder extends Seeder
{
    public function run(): void
    {
        $programacoes = [
            [
                'ordem_servico' => 'OS-1001',
                'tecnico' => 'Eduardo',
                'hora_inicio' => '09:00',
                'hora_fim' => '12:30',
            ],
            [
                'ordem_servico' => 'OS-1002',
                'tecnico' => 'Arrhenius',
                'hora_inicio' => '06:00',
                'hora_fim' => '07:30',
            ],
        ];

        foreach ($programacoes as $programacao) {
            $osTecnico = OsTecnico::where('ordem_servico', $programacao['ordem_servico'])->firstOrFail();
            $tecnico = Tecnico::where('nome', $programacao['tecnico'])->firstOrFail();

            AgendaOs::firstOrCreate(
                ['os_tecnico_id' => $osTecnico->id],
                [
                    'tecnico_id' => $tecnico->id,
                    'data' => now()->toDateString(),
                    'hora_inicio' => $programacao['hora_inicio'],
                    'hora_fim' => $programacao['hora_fim'],
                    'ordem' => 1,
                    'observacao' => null,
                ],
            );
        }
    }
}
