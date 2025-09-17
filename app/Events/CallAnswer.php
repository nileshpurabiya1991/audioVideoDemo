<?php
namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CallAnswer implements ShouldBroadcast
{
    use InteractsWithSockets;

    public $fromUser;
    public $answer;
    protected $toUser;

    public function __construct($fromUser, $toUser, $answer)
    {
        $this->fromUser = $fromUser;
        $this->toUser = $toUser;
        $this->answer = $answer;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('call.' . $this->toUser);
    }

    public function broadcastWith()
    {
        return ['fromUser' => $this->fromUser, 'answer' => $this->answer];
    }

    public function broadcastAs()
    {
        return 'CallAnswer';
    }
}
