<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SocialMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SocialMediaController extends Controller
{

    public function index()
    {
        $socialMedias = SocialMedia::all();
        return view('admin.social.index',compact('socialMedias'));
    }

    public function create()
    {
        return view('admin.social.create');
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
            return redirect()->to('social-media')->with([
                'success'=> 'social media created successfully',
            ]);
        }


    }

    public function show(SocialMedia $socialMedia)
    {
        //
    }

    public function edit($socialMedia) {

        $socialMedia = SocialMedia::findOrFail($socialMedia);
        return view('admin.social.edit',compact('socialMedia'));

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
            return redirect()->to('social-media')->with([
                'success'=> 'social media updated successfully',
            ]);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($socialMedia)
    {
        $socialMedia = SocialMedia::findOrFail($socialMedia);

        if($socialMedia->delete()) {
            Storage::delete('public/' . $socialMedia->logo);
            return redirect()->to('social-media')->with([
                'success'=> 'social media deleted successfully',
            ]);
        }

    }
}
