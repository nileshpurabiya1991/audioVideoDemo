<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CallSignal implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $from;
    public string $to;
    public array $signal;

    public function __construct($from, $to, $signal)
    {
        $this->from = $from;
        $this->to = $to;
        $this->signal = $signal;
    }

    public function broadcastOn()
    {
        return new PrivateChannel("call.{$this->to}");
    }
}
