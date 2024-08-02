<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CallInitiated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $callerId;
    public $signalData;
    public $receiverId;

    public function __construct($callerId, $signalData, $receiverId)
    {
        $this->callerId = $callerId;
        $this->signalData = $signalData;
        $this->receiverId = $receiverId;
    }

    public function broadcastOn()
    {
        return new Channel('user.' . $this->receiverId);
    }
}