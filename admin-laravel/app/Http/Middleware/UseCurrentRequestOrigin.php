<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class UseCurrentRequestOrigin
{
    /**
     * Keep local signed Livewire upload/preview URLs on the same host that
     * served the Admin page. This prevents a localhost <-> 127.0.0.1 switch
     * from dropping the session and CSRF cookie during temporary file upload.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (app()->environment('local')) {
            URL::forceRootUrl($request->getSchemeAndHttpHost());
        }

        return $next($request);
    }
}

