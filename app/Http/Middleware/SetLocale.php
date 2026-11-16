<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($lang = $request->query('lang')) {
            session(['locale' => in_array($lang, ['en', 'bn'], true) ? $lang : 'en']);
        }

        app()->setLocale(session('locale', config('app.locale')));

        return $next($request);
    }
}
