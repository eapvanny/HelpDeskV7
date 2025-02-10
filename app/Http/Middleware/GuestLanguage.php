<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class GuestLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $locale = null;

        $locale = Session::get('user_locale');
        if ($locale == null) {
            $locale = 'kh';
        }

//        Carbon::setLocale($locale);
        //set user wise locale
        App::setLocale($locale);

        return $next($request);
    }
}
