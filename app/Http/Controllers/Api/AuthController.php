<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Category;
use App\Models\SocialMedia;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request) {

        $validatedData = $request->validate([
            'first_name' => 'required|max:35',
            'last_name' => 'required|max:35',
            'category_id' => 'required|integer',
            'username' => 'required|max:32|unique:users',
            'date_of_birth' => 'required|date|date_format:Y-m-d',
            'email' => 'required|max:128|unique:users',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
            'description' => 'max: 255',
            'type' => 'required',
            'gender' => 'required',
            'avatar' => 'mimes:jpg,gif,png,svg,webp',
            'mobile' => 'min:11|max:13'
        ]);

        $validatedData['is_banned'] = false;
        $validatedData['password'] = bcrypt($validatedData['password']);

        if($request->hasFile('avatar')) {
            $validatedData['avatar'] = $request->file('avatar')->store('avatars','public');
        }

        $user = Category::findOrFail($validatedData['category_id'])->users()->create($validatedData);

        $accessToken = $user -> createToken('authToken')->accessToken;

        if($user) {

            $userDetails = [
                'username' => $user->username,
                'email' => $user->email
            ];

            Mail::send('emails.register',['user'=>$userDetails],function ($message) use ($userDetails) {
                $message->subject('You Registered Successfully in PickADS');
                $message->from('contact@pickads.net');
                $message->to($userDetails['email']);
            });

            return response()->json([
                'message' => 'user registered successfully',
                'user' => new UserResource($user),
                'access_token' => $accessToken
            ],200);
        }

        return response()->json([
            'message' => 'server error'
        ],500);

    }

    public function login(Request $request) {

        $validatedData = $request->validate([
            'username' => 'required|max:32',
            'password' => 'required',
        ]);

        if(!auth()->attempt($validatedData,true)) {
            return response()->json(['message' => 'invalid credentials'],401);
        }

        $user = auth()->user();
        $accessToken = $user ->createToken('authToken')->accessToken;

        return response()->json([
            'message' => 'logged successfully',
            'user' => $user,
            'access_token' => $accessToken
        ]);
    }

    public function forgetPassword(Request $request) {

        $credentials = $request->validate([
            'email' => 'required|email'
        ]);


        if(!User::where('email',$credentials)->first()) {
            return response()->json(["msg" => 'Email Not Exist'],404);
        }

        $token = Str::random();

        DB::table('password_reset_tokens')->where('email',$credentials['email'])->delete();

        DB::table('password_reset_tokens')->insert([
            'email' => $credentials['email'],
            'token' => $token
        ]);

        Mail::send('emails.forget',[],function ($message) use ($request) {
            $message->subject('Password Reset For Your Account');
            $message->from('contact@pickads.net');
            $message->to($request->email);
        });


        return response()->json(["message" => 'Reset password link sent on your email.']);

    }

    public function resetPassword(Request $request) {

        $token = $request->token;
        if(!$passwordResets = DB::table('password_reset_tokens')->where('token', $token)->first()) {
            return response()->json([
                'message' => 'invalid token!',
            ],400);
        }

        if(!$user = User::where('email', $passwordResets->email)->first()) {
            return response()->json(["msg" => 'User Not Exist'],404);
        }

        $validatedData = $request->validate([
            'password' => 'required|confirmed',
            'password_confirmation' => 'required'
        ]);


        $user -> password = $validatedData['password'];

        if($user->save()) {
            DB::table('password_reset_tokens')->where('email',$user['email'])->delete();
            return response()->json(["message" => 'Password Reset Successfully'],200);
        }

        return response()->json([
            'message' => 'server error'
        ],500);

    }


}
