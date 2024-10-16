<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Performance extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'location', 'description', 'participants', 'expected_duration', 'event_id', 'actual_start_time', 'actual_end_time', 'delay'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    // Рассчет задержки выступления
    public function calculateDelay()
    {
        if ($this->actual_start_time && $this->actual_end_time) {
            $actualDuration = $this->actual_end_time->diffInMinutes($this->actual_start_time);
            return $actualDuration - $this->expected_duration;
        }
        return 0;
    }
}
