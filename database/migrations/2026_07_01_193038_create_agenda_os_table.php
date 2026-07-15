<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('agenda_os', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('os_tecnico_id');
            $table->unsignedBigInteger('tecnico_id');

            $table->date('data');

            $table->time('hora_inicio')->nullable();
            $table->time('hora_fim')->nullable();

            $table->integer('ordem')->default(0);

            $table->text('observacao')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agenda_os');
    }
};
