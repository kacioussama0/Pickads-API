<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SocialMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SocialMediaController extends Controller
{

    public function index()
    {
        return response()->json(SocialMedia::all(),200);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
           'name' => 'required|unique:social_media|max:255',
           'logo' => 'required|mimes:jpg,jpeg,png,svg,webp|max:5120',
           'url' => 'required|url:http,https'
        ]);

        $validatedData['logo'] = $request->file('logo')->store('socialMedia','public');

        $socialMedia = SocialMedia::create($validatedData);

        if($socialMedia) {
            return response()->json([
                'message'=> 'social media created successfully',
                'social-media '=> $socialMedia
            ],200);
        }

        return response()->json(['message'=> 'server error'],500);

    }

    public function show(SocialMedia $socialMedia)
    {
        //
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$socialMedia)
    {
        $socialMedia = SocialMedia::findOrFail($socialMedia);

        $validatedData = $request->validate([
            'name' => 'required|max:255|unique:social_media,name,' . $socialMedia->id,
            'logo' => 'mimes:jpg,jpeg,png,svg,webp|max:5120',
            'url' => 'required|url:http,https'
        ]);

        if($request->hasFile('logo')) {
            Storage::delete('public/' . $socialMedia->logo);
            $validatedData['logo'] = $request->file('logo')->store('socialMedia','public');
        }

        $updated = $socialMedia->update($validatedData);

        if($updated) {
            return response()->json([
                'message'=> 'social media updated successfully',
            ],200);
        }

        return response()->json(['message'=> 'server error'],500);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($socialMedia)
    {
        $socialMedia = SocialMedia::findOrFail($socialMedia);

        if($socialMedia->delete()) {
            Storage::delete('public/' . $socialMedia->logo);
            return response()->json([
                'message'=> 'social media deleted successfully',
            ],200);
        }

        return response()->json(['message'=> 'server error'],500);
    }
}
