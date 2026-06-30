<?php

namespace App\Http\Middleware;

use App\Services\UrduLocaleService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function __construct(
        private readonly UrduLocaleService $urdu
    ) {}

    /**
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $segments = $request->segments();
        $first = $segments[0] ?? null;

        if ($first !== null && $this->urdu->isEnabled() && $first === $this->urdu->prefix()) {
            app()->setLocale('ur');
            \Illuminate\Support\Facades\URL::defaults(['locale' => $this->urdu->prefix()]);
        } else {
            app()->setLocale('en');
            \Illuminate\Support\Facades\URL::defaults(['locale' => null]);
        }

        return $next($request);
    }
}
