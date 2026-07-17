<?php

namespace Database\Seeders;

use App\Models\Tecnico;
use Illuminate\Database\Seeder;

class TecnicoSeeder extends Seeder
{
    public function run(): void
    {
        $tecnicos = [
            ['nome' => 'Weignon', 'regiao' => 'VA'],
            ['nome' => 'Roberto Kallyl', 'regiao' => 'VA'],
            ['nome' => 'Arrhenius', 'regiao' => 'VA'],
            ['nome' => 'Carlos', 'regiao' => 'VA'],
            ['nome' => 'Eduardo', 'regiao' => 'VA'],
            ['nome' => 'Tiago', 'regiao' => 'GV'],
            ['nome' => 'Leyzon', 'regiao' => 'GV'],
            ['nome' => 'Lucas Silva', 'regiao' => 'GV'],
            ['nome' => 'Guilherme', 'regiao' => 'GV'],
        ];

        foreach ($tecnicos as $tecnico) {
            Tecnico::updateOrCreate(
                ['nome' => $tecnico['nome']],
                ['regiao' => $tecnico['regiao']],
            );
        }
    }
}
