<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LikeResource;
use App\Http\Resources\UserResource;
use App\Models\Category;
use App\Models\Like;
use App\Models\SocialMedia;
use App\Models\User;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function influencers(Request $request) {

        $influencers = User::where('is_banned',0)->latest()->paginate(20);
        $socialMedias = $request->input('social');
        $search = $request->input('search');

        if(!empty($search)) {
          $influencers = User::where('is_banned',0)->where('first_name','LIKE',"%$search%")->orWhere('last_name','LIKE',"%$search%")->latest()->paginate(20);
        }

        if(!empty($socialMedias)) {
            $influencers = SocialMedia::findOrFail($socialMedias)->users;
        }

        return UserResource::collection($influencers);
    }

    public function whoLike($fingerprint) {
        $likes = Like::where('fingerprint',$fingerprint)->latest()->get();
        return LikeResource::collection($likes);
    }
}
