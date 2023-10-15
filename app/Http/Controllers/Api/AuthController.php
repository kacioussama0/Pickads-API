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
            'description' => 'max:255',
            'gender' => 'required',
            'avatar' => 'mimes:jpg,gif,png,svg,webp',
            'mobile' => 'max:13'
        ]);

        $social_medias = json_decode($request->social_media);

        $validatedData['is_banned'] = false;
        $validatedData['username'] = strtolower(trim($validatedData['username']));
        $validatedData['password'] = bcrypt($validatedData['password']);

        $user = Category::findOrFail($validatedData['category_id'])->users()->create($validatedData);



        if($user) {

            $accessToken = $user ->createToken('authToken')->accessToken;

            foreach ($social_medias as $social_media) {
                $user->socialMedia()->attach($social_media->social_media_id,[
                    'followers' => $social_media->info->followers,
                    'url' => $social_media->info->url
                ]);
            }

            $userDetails = [
                'username' => $user->username,
                'email' => $user->email
            ];

            Mail::send('emails.register',['user'=>$userDetails],function ($message) use ($userDetails) {
                $message->subject('Vous vous êtes inscrit avec succès dans PickADS');
                $message->from('no-reply@pickads.net');
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
            return response()->json(['message' => 'les informations invalides'],401);
        }

        $user = auth()->user();

        if($user['is_banned']) {
            return response()->json(['message' => 'Votre compte est restreint'],401);
        }

        $accessToken = $user ->createToken('authToken')->accessToken;

        return response()->json([
            'message' => 'logged successfully',
            'user' => new UserResource($user),
            'access_token' => $accessToken
        ]);
    }

    public function forgetPassword(Request $request) {

        $credentials = $request->validate([
            'email' => 'required|email'
        ]);


        if(!User::where('email',$credentials)->first()) {
            return response()->json(["msg" => 'L\'e-mail n\'existe pas'],404);
        }

        $token = Str::random();

        DB::table('password_reset_tokens')->where('email',$credentials['email'])->delete();

        DB::table('password_reset_tokens')->insert([
            'email' => $credentials['email'],
            'token' => $token
        ]);

        Mail::send('emails.forget',['token' => $token],function ($message) use ($request) {
            $message->subject('Password Reset For Your Account');
            $message->from('no-reply@pickads.net');
            $message->to($request->email);
        });


        return response()->json(["message" => 'Lien de réinitialisation du mot de passe envoyé sur votre e-mail.']);

    }

    public function resetPassword(Request $request) {

        $token = $request->token;
        if(!$passwordResets = DB::table('password_reset_tokens')->where('token', $token)->first()) {
            return response()->json([
                'message' => 'jeton invalide!',
            ],400);
        }

        if(!$user = User::where('email', $passwordResets->email)->first()) {
            return response()->json(["msg" => 'L\'utilisateur n\'existe pas'],404);
        }

        $validatedData = $request->validate([
            'password' => 'required|confirmed',
            'password_confirmation' => 'required'
        ]);


        $user -> password = $validatedData['password'];

        if($user->save()) {
            DB::table('password_reset_tokens')->where('email',$user['email'])->delete();
            return response()->json(["message" => 'Réinitialisation du mot de passe avec succès'],200);
        }

        return response()->json([
            'message' => 'server error'
        ],500);

    }


}
