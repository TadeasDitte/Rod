<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * Detects the locale from the first URL segment (e.g. /sk/dashboard),
     * sets the application locale, and strips the segment so the router
     * can match the remaining path (e.g. /dashboard).
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locales = config('app.available_locales', []);
        $segment = $request->segment(1);

        if ($segment !== null && in_array($segment, $locales, true)) {
            App::setLocale($segment);

            $remaining = substr($request->getPathInfo(), strlen('/'.$segment));

            $request->server->set('REQUEST_URI', $remaining === '' ? '/' : $remaining);
        } else {
            App::setLocale(config('app.locale', 'en'));
        }

        return $next($request);
    }
}
