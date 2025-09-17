<?php
namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CallOffer implements ShouldBroadcast
{
    use InteractsWithSockets;

    public $fromUser;
    public $offer;
    protected $toUser;

    public function __construct($fromUser, $toUser, $offer)
    {
        $this->fromUser = $fromUser;
        $this->toUser = $toUser;
        $this->offer = $offer;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('call.' . $this->toUser);
    }

    public function broadcastWith()
    {
        return ['fromUser' => $this->fromUser, 'offer' => $this->offer];
    }

    public function broadcastAs()
    {
        return 'CallOffer';
    }
}
