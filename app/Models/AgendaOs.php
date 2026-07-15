<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgendaOs extends Model
{
    protected $table = 'agenda_os';

    protected $fillable = [
        'os_tecnico_id',
        'tecnico_id',
        'data_agendamento',
        'hora_inicio',
        'hora_fim',
        'status',
        'observacao',
    ];

    public function osTecnico(): BelongsTo
    {
        return $this->belongsTo(OsTecnico::class);
    }

    public function tecnico(): BelongsTo
    {
        return $this->belongsTo(Tecnico::class);
    }
}