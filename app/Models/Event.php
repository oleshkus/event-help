<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['title', 'start_time', 'end_time', 'total_delay'];

    public function performances()
    {
        return $this->hasMany(Performance::class);
    }

    public function links()
    {
        return $this->hasMany(Link::class);
    }

    // Рассчитываем время окончания события
    public function calculateEndTime()
    {
        $totalDuration = $this->performances()->sum('estimated_duration');
        return $this->start_time->addMinutes($totalDuration + $this->total_delay);
    }
}
