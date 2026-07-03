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
        Schema::create('os_tecnicos', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('task_id'); 
            $table->unsignedBigInteger('parent_task_id');
        
            $table->string('tecnico_nome');
            $table->string('ordem_servico');
            $table->string('titulo');
            $table->string('task_code')->nullable();
            $table->string('categoria')->nullable();
            $table->string('regiao');
            $table->string('status')->nullable();
            $table->string('protocolo')->nullable();
            $table->string('prioridade')->nullable();
            $table->dateTime('data_criacao')->nullable();
            $table->dateTime('data_conclusao')->nullable();
            $table->dateTime('criada_em')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('os_tecnico');
    }
};
