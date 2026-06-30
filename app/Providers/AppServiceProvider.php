<?php

namespace App\Providers;

use App\Models\SiteSetting;
use App\View\Composers\MainLayoutComposer;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
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
        $this->bootstrapSiteLocaleFromDatabase();
        $this->normalizeApplicationUrlsForSubdirectoryDeploy();

        $root = (string) config('app.url');
        if ($root !== '') {
            URL::forceRootUrl($root);
        }

        Authenticate::redirectUsing(fn ($request) => route('admin.login'));

        ResetPassword::createUrlUsing(static function (object $notifiable, string $token): string {
            return route('admin.password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ]);
        });

        Paginator::defaultView('vendor.pagination.admin-wp');
        Paginator::defaultSimpleView('vendor.pagination.simple-admin-wp');

        View::composer('layouts.app', MainLayoutComposer::class);
    }

    private function bootstrapSiteLocaleFromDatabase(): void
    {
        try {
            if (! Schema::hasTable('site_settings')) {
                return;
            }

            $stored = SiteSetting::query()->where('key', 'locale')->value('value');
            if (! is_array($stored)) {
                return;
            }

            config(['site.locale' => array_merge(config('site.locale', []), $stored)]);
        } catch (\Throwable) {
            // Fresh install or DB unavailable during deploy.
        }
    }

    /**
     * Normalize app.url / app.asset_url so asset() and route URLs work when the
     * app is not at the domain root (e.g. /5thpillar/public).
     *
     * Laravel uses ASSET_URL for asset(); if it is only the bare domain while
     * APP_URL includes a path, browsers request https://host/uploads/... (404).
     * For any subdirectory APP_URL we force the asset base to match APP_URL exactly.
     */
    private function normalizeApplicationUrlsForSubdirectoryDeploy(): void
    {
        $appUrl = rtrim((string) config('app.url'), '/');
        config(['app.url' => $appUrl]);

        $appPathRaw = parse_url($appUrl, PHP_URL_PATH);
        $appPath = is_string($appPathRaw) ? rtrim($appPathRaw, '/') : '';
        $isSubdirectory = ($appPath !== '' && $appPath !== '/');

        if ($isSubdirectory) {
            config(['app.asset_url' => $appUrl]);

            return;
        }

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

        $assetPathRaw = parse_url($assetUrl, PHP_URL_PATH);
        $assetPath = is_string($assetPathRaw) ? rtrim($assetPathRaw, '/') : '';

        if ($assetPath === '' || $assetPath === '/' || strlen($assetPath) < strlen($appPath)) {
            config(['app.asset_url' => null]);
        }
    }
}
