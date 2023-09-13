<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdController extends Controller
{

    public function index()
    {
        return response()->json(Ad::where('is_published', 1)->latest()->get());
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required|max:255',
            'url' => 'required|url',
            'ad_owner' => 'required',
            'ad_owner_logo' => 'required|mimes:jpg,jpeg,png,webp,svg|max:5120',
            'background' => 'mimes:jpg,jpeg,png,webp,svg|max:5120',
            'background_color' => 'required|max:9',
        ]);

        $validatedData['is_published'] = $request->is_published ? 1 : 0;

        if($request->hasFile('background')) {
            $validatedData['background'] = $request->file('background')->store('ads/backgrounds','public');
        }

        if($request->hasFile('ad_owner_logo')) {
            $validatedData['ad_owner_logo'] = $request->file('ad_owner_logo')->store('ads/logos','public');
        }

        $created = Ad::create($validatedData);

        if($created) {
            return response()->json([
                'message'=> 'ad created successfully',
                'ad'=> $created
            ],200);
        }

        return response()->json(['message'=> 'server error'],500);
    }


    public function show(Ad $ad)
    {
        return response()->json($ad,200);
    }


    public function update(Request $request, Ad $ad)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required|max:255',
            'url' => 'required|url',
            'ad_owner' => 'required',
            'ad_owner_logo' => 'mimes:jpg,jpeg,png,webp,svg|max:5120',
            'background' => 'mimes:jpg,jpeg,png,webp,svg|max:5120',
            'background_color' => 'required|max:9',
        ]);

        $validatedData['is_published'] = $request->is_published ? 1 : 0;

        if($request->hasFile('background')) {
            Storage::delete('public/' . $ad->background);
            $validatedData['background'] = $request->file('background')->store('ads/backgrounds','public');
        }

        if($request->hasFile('ad_owner_logo')) {
            Storage::delete('public/' . $ad->ad_owner_logo);
            $validatedData['ad_owner_logo'] = $request->file('ad_owner_logo')->store('ads/logos','public');
        }

        $updated = $ad->update($validatedData);

        if($updated) {
            return response()->json([
                'message'=> 'ad updated successfully',
                'ad'=> $ad
            ],200);
        }

        return response()->json(['message'=> 'server error'],500);
    }

    public function destroy(Ad $ad)
    {

        if($ad->delete()) {
            Storage::delete('public/' . $ad->background);
            Storage::delete('public/' . $ad->ad_owner_logo);
            return response()->json([
                'message'=> 'ad deleted successfully',
            ],200);
        }

        return response()->json(['message'=> 'server error'],500);

    }
}
