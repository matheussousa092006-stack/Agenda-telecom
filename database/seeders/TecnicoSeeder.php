<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TecnicoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    \App\Models\Tecnico::insert([
        ['nome' => 'Carlos'],
        ['nome' => 'João'],
        ['nome' => 'Pedro'],
        ['nome' => 'Marcos'],
    ]);
}
}
