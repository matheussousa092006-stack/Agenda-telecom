<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TecnicoSeeder extends Seeder
{
    public function run(): void
    {
        \App\Models\Tecnico::firstOrCreate(['nome' => 'Carlos']);
        \App\Models\Tecnico::firstOrCreate(['nome' => 'João']);
        \App\Models\Tecnico::firstOrCreate(['nome' => 'Pedro']);
        \App\Models\Tecnico::firstOrCreate(['nome' => 'Marcos']);
    }
}