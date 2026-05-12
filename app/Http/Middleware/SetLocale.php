<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $segments = $request->segments();
        
        if (isset($segments[0]) && $segments[0] === 'urdu') {
            app()->setLocale('ur');
            \Illuminate\Support\Facades\URL::defaults(['locale' => 'urdu']);
        } else {
            app()->setLocale('en');
            \Illuminate\Support\Facades\URL::defaults(['locale' => null]);
        }

        return $next($request);
    }

}
