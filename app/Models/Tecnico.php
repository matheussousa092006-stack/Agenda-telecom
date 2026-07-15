<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tecnico extends Model
{
    protected $table = 'tecnicos';

    protected $fillable = [
        'nome',
        'regiao',
    ];

    public function agenda()
    {
        return $this->hasMany(AgendaOs::class);
    }
}
