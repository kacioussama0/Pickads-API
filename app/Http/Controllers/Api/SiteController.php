<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LikeResource;
use App\Http\Resources\UserResource;
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
          $influencers = User::where('is_banned',0)->where('first_name','LIKE',"%$search%")->orWhere('last_name','LIKE',"%$search%")->where('is_banned',0)->latest()->paginate(20);
        }

        if(!empty($socialMedias)) {
            $influencers = SocialMedia::findOrFail($socialMedias)->users;
        }

        if(!empty($request->followersFrom) && !empty($request->followersTo)) {

            $influencers = User::whereHas('socialMedia',function ($query) use ($request) {
                     $query->where('user_social_media.followers','>=',$request->followersFrom)->where('user_social_media.followers','<=' , $request->followersTo);
                }
            )->get();

        }

        return UserResource::collection($influencers);
    }

    public function whoLike($fingerprint,Request $request) {
        $likes = Like::where('fingerprint',$fingerprint)->where('user_agent',$request->user_agent)->latest()->get();
        return LikeResource::collection($likes);
    }
}
