<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class UserCanvaser
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
        $permission = array("Kasir", "Canvaser", "Supervisor", "Super Admin", "Kepala Cabang");
        if (!in_array(Auth::user()->level_user, $os)) {
            return redirect('/');
        }
        return $next($request);
    }
}
