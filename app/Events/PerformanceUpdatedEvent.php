<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use App\Models\Performance;


class PerformanceUpdatedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $performance;

    public function __construct(Performance $performance)
    {
        $this->performance = $performance;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('performance.' . $this->performance->id);
    }
}
