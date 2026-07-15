<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AgendaController;

Route::get('/', [AgendaController::class, 'index']);

Route::post('/agenda/{agendaOs}/mover', [AgendaController::class, 'mover'])
    ->name('agenda.mover');