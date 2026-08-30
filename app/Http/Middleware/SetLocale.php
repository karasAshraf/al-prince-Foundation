<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $isDashboard = $request->is('dashboard') 
            || $request->is('dashboard/*') 
            || $request->is('profile') 
            || $request->is('profile/*') 
            || $request->is('login') 
            || $request->is('register') 
            || $request->is('forgot-password') 
            || $request->is('reset-password');

        $sessionKey = $isDashboard ? 'dashboard_locale' : 'frontend_locale';

        $locale = session($sessionKey, config('app.locale', 'ar'));

        if (in_array($locale, ['ar', 'en'])) {
            App::setLocale($locale);
        }

        return $next($request);
    }
}
