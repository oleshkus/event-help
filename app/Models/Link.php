<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    protected $fillable = ['event_id', 'type', 'token'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    // Генерация уникального токена
    public static function generateToken()
    {
        return bin2hex(random_bytes(16));
    }
}
