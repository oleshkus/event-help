<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('performances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade'); // Связь с мероприятиями
            $table->string('title'); // Название выступления
            $table->integer('duration'); // Примерная длительность (в минутах)
            $table->integer('delay')->default(0); // Время задержки/спешки
            $table->string('performers'); // Имена выступающих
            $table->string('location'); // Место выхода
            $table->text('description')->nullable(); // Описание выступления
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('performances');
    }
};
