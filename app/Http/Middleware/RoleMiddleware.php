<?php

namespace App\Http\Middleware;

use Closure;

use Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permission)
    {
        if (Auth::guest()) {
            return redirect($urlOfYourLoginPage);
        }

        if (! $request->user()->can($permission)) {

            // return view('errors.403');
           // abort(403);
           // 
           return redirect()->back()->with('errorMessage', 'Anda tidak diizinkan mengakses halaman ' . $permission);
        }

        return $next($request);
    }
}
