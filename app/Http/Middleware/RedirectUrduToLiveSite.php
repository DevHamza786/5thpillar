<?php

namespace App\Http\Middleware;

use App\Services\UrduLocaleService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectUrduToLiveSite
{
    public function __construct(
        private readonly UrduLocaleService $urdu
    ) {}

    /**
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $this->urdu->redirectsToLive()) {
            return $next($request);
        }

        $path = trim($request->path(), '/');
        $prefix = $this->urdu->prefix();

        if ($path !== $prefix && ! str_starts_with($path, $prefix.'/')) {
            return $next($request);
        }

        $suffix = $path === $prefix
            ? ''
            : ltrim((string) substr($path, strlen($prefix)), '/');

        $target = $this->urdu->liveUrduUrl($suffix);

        $query = $request->getQueryString();
        if (filled($query)) {
            $target .= '?'.$query;
        }

        return redirect()->away($target, 302);
    }
}
