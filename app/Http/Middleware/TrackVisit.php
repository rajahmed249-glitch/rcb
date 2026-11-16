<?php

namespace App\Http\Middleware;

use App\Models\VisitorLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackVisit
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('get') && ! $request->is('admin') && ! $request->is('admin/*')) {
            VisitorLog::record(md5((string) $request->ip()));
        }

        return $next($request);
    }
}
