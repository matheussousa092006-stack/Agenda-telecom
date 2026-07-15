<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OsTecnicoSeeder extends Seeder
{
    public function run(): void
{
    \App\Models\OsTecnico::insert([
        [
            'task_id' => 1001,
            'parent_task_id' => 1001,
            'tecnico_nome' => 'Carlos',
            'ordem_servico' => 'OS-1001',
            'titulo' => 'Troca de CTO',
            'task_code' => 'VL-ATD-0151',
            'categoria' => 'Atendimento',
            'regiao' => 'Vale do Aço',
            'status' => 'Pendente',
            'protocolo' => '123456',
            'prioridade' => 'Alta',
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'task_id' => 1002,
            'parent_task_id' => 1001,
            'tecnico_nome' => 'João',
            'ordem_servico' => 'OS-1002',
            'titulo' => 'Lançamento de Cabo',
            'task_code' => 'VL-ATD-0151',
            'categoria' => 'Atendimento',
            'regiao' => 'Vale do Aço',
            'status' => 'Pendente',
            'protocolo' => '654321',
            'prioridade' => 'Normal',
            'created_at' => now(),
            'updated_at' => now(),
        ],
    ]);
}
}
