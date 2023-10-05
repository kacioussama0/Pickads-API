<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Category;
use App\Models\SocialMedia;
use App\Models\User;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function influencers(Request $request) {

        $influencers = User::where('is_banned',0)->latest()->paginate(20);

        $category = $request->input('category');
        $search = $request->input('search');
        $followers = $request->input('followers');

        if(!empty($search)) {
          $influencers = User::where('is_banned',0)->where('first_name','LIKE',"%$search%")->orWhere('last_name','LIKE',"%$search%")->latest()->paginate(20);
        }

        if(!empty($category)) {
            $influencers = Category::findOrFail($category)->users;
        }

        if(!empty($followers)) {
            $influencers = SocialMedia::all()->user();
        }

        return UserResource::collection($influencers);
    }
}
