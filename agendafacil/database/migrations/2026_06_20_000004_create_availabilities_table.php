<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('availabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('professional_id')->constrained()->cascadeOnDelete();
            // 0 = domingo ... 6 = sábado (padrão Carbon::dayOfWeek)
            $table->unsignedTinyInteger('weekday');
            $table->time('start_time');
            $table->time('end_time');
            $table->timestamps();

            $table->unique(['professional_id', 'weekday', 'start_time']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('availabilities');
    }
};
