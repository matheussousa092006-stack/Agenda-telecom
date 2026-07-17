<?php

use App\Http\Controllers\AgendaController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AgendaController::class, 'index']);

Route::post('/agenda/{agendaOs}/mover', [AgendaController::class, 'mover'])
    ->name('agenda.mover');

Route::patch('/agenda/{agendaOs}/duracao', [AgendaController::class, 'atualizarDuracao'])
    ->name('agenda.duracao');
