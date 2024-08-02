<?php

namespace App\Http\Controllers;

use App\Events\CallAccepted;
use App\Events\CallInitiated;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VideoChatController extends Controller
{
    public function index()
    {
        $users = User::where('id', '<>', Auth::id())->get();
        return view('video-chat', ['users' => $users]);
    }

    public function callUser(Request $request)
    {
        $user = User::find($request->userId);

        if ($user) {
            // Dispatch the event to notify the called user
            event(new CallInitiated(Auth::id(), $request->signalData, $user->id));
        }

        return response()->json(['status' => 'Call initiated']);
    }

    public function acceptCall(Request $request)
    {
        $user = User::find($request->userId);

        if ($user) {
            // Dispatch the event to notify the caller that the call was accepted
            event(new CallAccepted(Auth::id(), $request->signalData, $user->id));
        }

        return response()->json(['status' => 'Call accepted']);
    }
}
