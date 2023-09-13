<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {

        if($request->input('password') != "PICKADSPANEL") {
            return response()->json([
                'message' => 'Password Admin Incorrect!'
            ],401);
        }

        return $next($request);
    }
}
