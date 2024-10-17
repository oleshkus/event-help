<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Performance extends Model
{
    protected $fillable = [
        'event_id', 'title', 'duration',
        'delay', 'performers', 'location', 'description'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
