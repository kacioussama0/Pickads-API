<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Like;
use App\Models\User;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function like(Request $request){
        $validatedData = $request->validate([
           "fingerprint" => "required",
           "user_agent" => "required",
           "user_id" => "required"
        ]);

        $like = Like::where('fingerprint',$validatedData['fingerprint'])->where('user_agent',$validatedData['user_agent'])->where("user_id",$validatedData['user_id'])->first();

        if($like) {
            return response()->json([
                'message' => 'you already liked this profile'
            ],401);
        }

        $liked = User::findOrFail($validatedData['user_id'])->likes()->create($validatedData);

        if($liked) {
            return response()->json([
                'message' => 'you get like this profile'
            ],200);
        }

        return response()->json([
            'message' => 'server error'
        ],500);

    }
}
