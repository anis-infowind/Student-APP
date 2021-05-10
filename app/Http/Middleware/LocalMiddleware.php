<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class LocalMiddleware
{
	/**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        /*$user=User::find(1);
        Auth::login($user);*/

        if(Auth::user())
        {
            return $next($request);
        }
        else
        {
            return redirect('/login');
        }

    }
}

?>