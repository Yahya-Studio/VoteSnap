<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $supported = ['ar', 'en'];

        if ($request->has('lang') && in_array($request->query('lang'), $supported)) {
            $locale = $request->query('lang');
            session(['locale' => $locale]);
        } else {
            $locale = session('locale', config('app.locale', 'ar'));
        }

        if (! in_array($locale, $supported)) {
            $locale = 'ar';
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
