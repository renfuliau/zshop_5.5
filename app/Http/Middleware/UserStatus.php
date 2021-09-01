<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Session::has('user')) {
            // dd(Auth::user()->status);
            $user_status = Auth::user()->status;
            if ($user_status == 'inactive') {
                request()->session()->flash('error', __('frontend.cart-user-inactive'));
                return redirect()->route('index');
            }
        }
        
        return $next($request);
    }
}
