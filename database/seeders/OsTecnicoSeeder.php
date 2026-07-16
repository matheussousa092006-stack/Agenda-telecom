<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OsTecnicoSeeder extends Seeder
{
    public function run(): void
    {
        \App\Models\OsTecnico::firstOrCreate(

            ['ordem_servico' => 'OS-1001'],

            [
                'task_id' => 1001,
                'parent_task_id' => 1001,
                'tecnico_nome' => 'Carlos',
                'titulo' => 'Troca de CTO',
                'task_code' => 'VL-ATD-0151',
                'categoria' => 'Atendimento',
                'regiao' => 'Vale do Aço',
                'status' => 'Pendente',
                'protocolo' => '123456',
                'prioridade' => 'Alta',
            ]
        );

        \App\Models\OsTecnico::firstOrCreate(

            ['ordem_servico' => 'OS-1002'],

            [
                'task_id' => 1002,
                'parent_task_id' => 1001,
                'tecnico_nome' => 'João',
                'titulo' => 'Lançamento de Cabo',
                'task_code' => 'VL-ATD-0151',
                'categoria' => 'Atendimento',
                'regiao' => 'Vale do Aço',
                'status' => 'Pendente',
                'protocolo' => '654321',
                'prioridade' => 'Normal',
            ]
        );
    }
}