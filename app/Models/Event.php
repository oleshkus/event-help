<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'start_time', 'end_time', 'user_id'];

    public function performances()
    {
        return $this->hasMany(Performance::class);
    }

    // Рассчет общего времени окончания мероприятия на основе длительности номеров
    public function calculateEndTime()
    {
        $totalDuration = $this->performances()->sum('expected_duration');
        return $this->start_time->addMinutes($totalDuration);
    }
}
