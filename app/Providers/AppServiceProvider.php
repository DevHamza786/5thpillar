<?php

namespace App\Providers;

use App\View\Composers\MainLayoutComposer;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Pagination\Paginator;
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
        Authenticate::redirectUsing(fn ($request) => route('admin.login'));

        Paginator::defaultView('vendor.pagination.admin-wp');
        Paginator::defaultSimpleView('vendor.pagination.simple-admin-wp');

        View::composer('layouts.app', MainLayoutComposer::class);
    }
}
