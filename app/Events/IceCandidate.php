<?php
namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class IceCandidate implements ShouldBroadcast
{
    use InteractsWithSockets;

    public $fromUser;
    public $candidate;
    protected $toUser;

    public function __construct($fromUser, $toUser, $candidate)
    {
        $this->fromUser = $fromUser;
        $this->toUser = $toUser;
        $this->candidate = $candidate;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('call.' . $this->toUser);
    }

    public function broadcastWith()
    {
        return ['fromUser' => $this->fromUser, 'candidate' => $this->candidate];
    }

    public function broadcastAs()
    {
        return 'IceCandidate';
    }
}
