<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as ResizeImage;

class UserController extends Controller
{
    public function updatePassword(Request $request) {

        $user = auth()->user();

        if(!Hash::check($request->current_password,$user->password)) {
            return response()->json(['message' => 'wrong actual password'],401);
        }

        $validatedData = $request->validate([
            'current_password' => 'required',
            'new_password'=> 'required|confirmed',
            'new_password_confirmation' => 'required'
        ]);

        $user -> password = bcrypt($validatedData['new_password']);

        if($user -> save()) {
            return response()->json(['message' => 'password updated successfully'],200);
        }

        return response()->json(['message' => 'server error'],500);
    }

    public function updateProfile(Request $request) {

        $user = auth()->user();

        $validatedData = $request->validate([
            'first_name' => 'required|max:35',
            'last_name' => 'required|max:35',
            'username' => 'required|max:32|unique:users,username,' . auth()->id(),
            'date_of_birth' => 'required|date|date_format:Y-m-d',
            'email' => 'required|max:128|unique:users,email,' . auth()->id(),
            'description' => 'max: 255',
            'gender' => 'required',
            'category_id' => 'required',
            'mobile' => 'max:13'
        ]);

        $updateUser = $user -> update($validatedData);

        if($updateUser) {
            return response()->json(['message' => 'profile updated successfully'],200);
        }

        return response()->json(['message' => 'server error'],500);
    }

    public function updateAvatar(Request $request) {

        $validateData = $request->validate([
            'avatar' => 'required|max:5120|file|mimes:jpg,png,webp,gif'
        ]);

        $username = auth()->user()->username;

        $originalName = $username . '-original.' . $request->avatar->extension();
        $smallName = $username . '-small.' . $request->avatar->extension();
        $mediumName = $username . '-medium.' . $request->avatar->extension();
        $largeName = $username . '-large.' . $request->avatar->extension();

        $path = 'storage/users/' . 'avatars/'. auth()->id() . '/';

        Storage::deleteDirectory('public/users/avatars/'. auth()->id());

        $usersPath = 'public/users/';
        $avatarPath = 'avatars/';

        $user = auth()->user();

        Storage::makeDirectory($usersPath);
        Storage::makeDirectory($usersPath . $avatarPath);
        Storage::makeDirectory($usersPath . $avatarPath . auth()->id());


        ResizeImage::make($request->file('avatar'))
            ->resize(150, 150)
            ->save($path . $smallName);

        ResizeImage::make($request->file('avatar'))
            ->save($path . $originalName);

        ResizeImage::make($request->file('avatar'))
            ->resize(300, 300)
            ->save($path . $mediumName);

        ResizeImage::make($request->file('avatar'))
            ->resize(1024, 1024)
            ->save($path . $largeName);

        Storage::delete('public/' . $user -> avatar);

        $user -> avatar = 'users/' . 'avatars/'. auth()->id() . '/' . $originalName;

        if($user->save()) {
            return response()->json(['message' => 'avatar updated successfully'],200);
        }

        return response()->json(['message' => 'server error'],500);

    }

    public function socialMedia() {
        return response()->json(auth()->user()->socialMedia,200);
    }

    public function profile($username) {
        $user = User::where('username',$username)->first();
        return new UserResource($user);
    }

    public function user() {
        return new UserResource(auth()->user());
    }



}
