<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;

class SignalController extends Controller
{
    public function sendSignal(Request $request)
    {
        Broadcast::channel('call-channel', function() { return true; });

        broadcast(new \App\Events\CallSignal(
            $request->signal,
            $request->from,
            $request->to
        ));

        return response()->json(['status' => 'signal sent']);
    }
}
