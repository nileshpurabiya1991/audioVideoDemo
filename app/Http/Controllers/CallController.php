<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\CallOffer;
use App\Events\CallAnswer;
use App\Events\IceCandidate;
use App\Events\CallSignal;

class CallController extends Controller
{
    public function index($user) {
        // dd($user);
        return view('call', compact('user'));
    }

    public function sendSignal(Request $request) {
        // Validate input
        $data = $request->validate([
            'to' => 'required|string',
            'signal' => 'required|array'
        ]);

        // Broadcast signal to other user
        broadcast(new CallSignal(auth()->user()->name, $data['to'], $data['signal']))
            ->toOthers();

        return response()->json(['status' => 'signal sent']);
        // broadcast(new CallSignal(auth()->user()->name, $request->to, $request->signal));
        // return response()->json(['status' => 'signal sent']);
    }
    // public function sendOffer(Request $req)
    // {
    //     $req->validate(['to' => 'required|integer', 'offer' => 'required']);
    //     event(new CallOffer(auth()->id(), $req->to, $req->offer));
    //     return response()->json(['ok' => true]);
    // }

    // public function sendAnswer(Request $req)
    // {
    //     $req->validate(['to' => 'required|integer', 'answer' => 'required']);
    //     event(new CallAnswer(auth()->id(), $req->to, $req->answer));
    //     return response()->json(['ok' => true]);
    // }

    // public function sendIce(Request $req)
    // {
    //     $req->validate(['to' => 'required|integer', 'candidate' => 'required']);
    //     event(new IceCandidate(auth()->id(), $req->to, $req->candidate));
    //     return response()->json(['ok' => true]);
    // }
}
