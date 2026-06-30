<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->foreignId('professional_id')->constrained()->cascadeOnDelete();
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->enum('status', ['agendado', 'cancelado', 'concluido'])->default('agendado');
            $table->timestamps();

            // Acelera a busca por conflitos de horário do profissional
            $table->index(['professional_id', 'start_at', 'end_at']);
            $table->index(['user_id', 'start_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
