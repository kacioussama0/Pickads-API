<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function send(Request $request) {
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required',
            'message' => 'required|min:10',
        ]);

        $message = Message::create($validatedData);

        if($message) {
            return response()->json(['message' => 'message sent successfully'],200);
        }

        return response()->json(['message'=> 'server error'],500);
    }

    public function delete(Message $message) {
        if($message->delete()){
            return response()->json(['message' => 'message deleted successfully'],200);
        }
        return response()->json(['message'=> 'server error'],500);
    }
}
