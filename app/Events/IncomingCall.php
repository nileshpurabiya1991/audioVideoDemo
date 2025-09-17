<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class IncomingCall implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $caller;
    public $receiver;

    public function __construct($caller, $receiver)
    {
        $this->caller = $caller;
        $this->receiver = $receiver;
    }

    public function broadcastOn()
    {
        // Private channel for the receiver
        return new PrivateChannel('call.' . $this->receiver);
    }

    public function broadcastWith()
    {
        return [
            'caller' => $this->caller,
            'message' => 'Incoming call'
        ];
    }
}
