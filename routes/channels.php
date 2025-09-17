<?php
use Illuminate\Support\Facades\Broadcast;

/*
| Broadcast channels
*/
Broadcast::channel('call.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});
