<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;

class SiteController extends Controller
{
    public function influencers() {

        $influencers = User::where('type','influencer')->where('is_banned',0)->latest()->paginate(20);
        return UserResource::collection($influencers);

    }
}
