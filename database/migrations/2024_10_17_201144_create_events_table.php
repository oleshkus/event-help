<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Название мероприятия
            $table->dateTime('start_time'); // Время начала
            $table->dateTime('end_time')->nullable(); // Время окончания (будет рассчитано)
            $table->integer('total_delay')->default(0); // Общее время задержки/спешки (в минутах)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
