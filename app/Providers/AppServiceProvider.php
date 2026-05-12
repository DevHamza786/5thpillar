<?php

namespace App\Providers;

use App\View\Composers\MainLayoutComposer;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->normalizeApplicationUrlsForSubdirectoryDeploy();

        $root = (string) config('app.url');
        if ($root !== '') {
            URL::forceRootUrl($root);
        }

        Authenticate::redirectUsing(fn ($request) => route('admin.login'));

        Paginator::defaultView('vendor.pagination.admin-wp');
        Paginator::defaultSimpleView('vendor.pagination.simple-admin-wp');

        View::composer('layouts.app', MainLayoutComposer::class);
    }

    /**
     * If ASSET_URL points at the same host as APP_URL but omits APP_URL's path
     * (common misconfiguration), asset() resolves to https://host/uploads/...
     * instead of https://host/.../public/uploads/... — clear the misleading value.
     */
    private function normalizeApplicationUrlsForSubdirectoryDeploy(): void
    {
        $appUrl = rtrim((string) config('app.url'), '/');
        config(['app.url' => $appUrl]);

        $rawAsset = config('app.asset_url');
        if (! is_string($rawAsset) || $rawAsset === '') {
            return;
        }

        $assetUrl = rtrim($rawAsset, '/');
        $appHost = parse_url($appUrl, PHP_URL_HOST);
        $assetHost = parse_url($assetUrl, PHP_URL_HOST);
        if (! is_string($appHost) || ! is_string($assetHost)
            || strcasecmp($appHost, $assetHost) !== 0) {
            return;
        }

        $appPath = rtrim((string) (parse_url($appUrl, PHP_URL_PATH) ?? ''), '/') ?: '';
        $assetPath = rtrim((string) (parse_url($assetUrl, PHP_URL_PATH) ?? ''), '/') ?: '';

        if ($appPath !== '' && $appPath !== '/' && ($assetPath === '' || $assetPath === '/' || strlen($assetPath) < strlen($appPath))) {
            config(['app.asset_url' => null]);
        }
    }
}
