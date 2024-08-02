<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CallAccepted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $receiverId;
    public $signalData;
    public $callerId;

    public function __construct($receiverId, $signalData, $callerId)
    {
        $this->receiverId = $receiverId;
        $this->signalData = $signalData;
        $this->callerId = $callerId;
    }

    public function broadcastOn()
    {
        return new Channel('user.' . $this->callerId);
    }
}