<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CarOwnerOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user()->user_type !== 'Car Owner') {
            return response()->json(['message' => 'User is not a car owner', 'status' => '400'], 400);
        }

        return $next($request);
    }
}
