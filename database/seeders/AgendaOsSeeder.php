<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AgendaOsSeeder extends Seeder
{
    public function run(): void
{
    \App\Models\AgendaOs::insert([

        [
            'os_tecnico_id' => 1,
            'tecnico_id' => 1,
            'data' => now()->toDateString(),
            'hora_inicio' => '08:00',
            'hora_fim' => '10:00',
            'ordem' => 1,
            'observacao' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ],

        [
            'os_tecnico_id' => 2,
            'tecnico_id' => 2,
            'data' => now()->toDateString(),
            'hora_inicio' => '09:00',
            'hora_fim' => '11:00',
            'ordem' => 1,
            'observacao' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ],

    ]);
}
}
