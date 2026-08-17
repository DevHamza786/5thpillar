<?php

use App\Http\Controllers\Admin\Auth\ChangePasswordController;
use App\Http\Controllers\Admin\Auth\ForgotPasswordController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\ResetPasswordController;
use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\Admin\CmsMediaController;
use App\Http\Controllers\Admin\CmsPageController;
use App\Http\Controllers\Admin\CmsTableController;
use App\Http\Controllers\Admin\MediaLibraryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FinancialDataController;
use App\Http\Controllers\Admin\FundDailySnapshotController;
use App\Http\Controllers\Admin\NavMenuController;
use App\Http\Controllers\Admin\NavMenuMediaController;
use App\Http\Controllers\Admin\PageSectionController;
use App\Http\Controllers\Admin\SiteSettingsController;
use App\Http\Controllers\AccountDeletionController;
use App\Http\Controllers\BrochureLeadController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsEventsController;
use App\Http\Controllers\HajjPlannerController;
use App\Http\Controllers\UmrahPlannerController;
use App\Http\Controllers\PdfEmbedController;
use App\Http\Controllers\PdfViewerController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::get('/favicon.ico', static function () {
    return redirect(asset('assets/images/favicons/cropped-5th-Pilar-Logo-32x32.png'), 301);
});

/*
|--------------------------------------------------------------------------
| Admin CMS
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store'])->name('login.store');

    Route::get('forgot-password', [ForgotPasswordController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [ForgotPasswordController::class, 'store'])->name('password.email');
    Route::get('reset-password/{token}', [ResetPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [ResetPasswordController::class, 'store'])->name('password.update');

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('logout', [LoginController::class, 'destroy'])->name('logout');
        Route::get('profile/password', [ChangePasswordController::class, 'edit'])->name('password.change');
        Route::put('profile/password', [ChangePasswordController::class, 'update'])->name('password.change.update');
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('pages', [CmsPageController::class, 'index'])->name('pages.index');
        Route::get('pages/create', [CmsPageController::class, 'create'])->name('pages.create');
        Route::post('pages', [CmsPageController::class, 'store'])->name('pages.store');
        Route::get('pages/{page}/edit', [CmsPageController::class, 'edit'])->name('pages.edit');
        Route::get('pages/{page}/preview', [CmsPageController::class, 'preview'])->name('pages.preview');
        Route::put('pages/{page}', [CmsPageController::class, 'update'])->name('pages.update');
        Route::post('pages/purge-legacy', [CmsPageController::class, 'purgeLegacy'])->name('pages.purge-legacy');

        Route::get('media/picker', [MediaLibraryController::class, 'picker'])->name('media.picker');
        Route::get('media', [MediaLibraryController::class, 'index'])->name('media.index');
        Route::post('media', [MediaLibraryController::class, 'store'])->name('media.store');
        Route::put('media/{cmsMedia}', [MediaLibraryController::class, 'update'])->name('media.update');
        Route::delete('media/{cmsMedia}', [MediaLibraryController::class, 'destroy'])->name('media.destroy');

        Route::get('data-tables', [CmsTableController::class, 'index'])->name('cms-tables.index');
        Route::get('data-tables/{tableKey}', [CmsTableController::class, 'edit'])->name('cms-tables.edit');
        Route::put('data-tables/{tableKey}', [CmsTableController::class, 'update'])->name('cms-tables.update');

        Route::post('pages/{page}/sections', [PageSectionController::class, 'store'])->name('pages.sections.store');
        Route::put('pages/{page}/sections/reorder', [PageSectionController::class, 'reorder'])->name('pages.sections.reorder');
        Route::post('pages/{page}/sections/{pageSection}/duplicate', [PageSectionController::class, 'duplicate'])->name('pages.sections.duplicate');
        Route::put('pages/{page}/sections/{pageSection}', [PageSectionController::class, 'update'])->name('pages.sections.update');
        Route::delete('pages/{page}/sections/{pageSection}', [PageSectionController::class, 'destroy'])->name('pages.sections.destroy');

        Route::post('pages/{page}/media', [CmsMediaController::class, 'store'])->name('pages.media.store');
        Route::delete('pages/{page}/media/{cmsMedia}', [CmsMediaController::class, 'destroy'])->name('pages.media.destroy');

        Route::get('navigation/media', [NavMenuMediaController::class, 'index'])->name('navigation.media.index');
        Route::post('navigation/media', [NavMenuMediaController::class, 'store'])->name('navigation.media.store');
        Route::delete('navigation/media/{cmsMedia}', [NavMenuMediaController::class, 'destroy'])->name('navigation.media.destroy');

        Route::get('navigation', [NavMenuController::class, 'index'])->name('navigation.index');
        Route::get('navigation/create', [NavMenuController::class, 'create'])->name('navigation.create');
        Route::post('navigation', [NavMenuController::class, 'store'])->name('navigation.store');
        Route::get('navigation/{navMenuItem}/edit', [NavMenuController::class, 'edit'])->name('navigation.edit');
        Route::put('navigation/{navMenuItem}', [NavMenuController::class, 'update'])->name('navigation.update');
        Route::delete('navigation/{navMenuItem}', [NavMenuController::class, 'destroy'])->name('navigation.destroy');

        Route::get('fund-daily-snapshots', [FundDailySnapshotController::class, 'index'])->name('fund-snapshots.index');
        Route::get('fund-daily-snapshots/create', [FundDailySnapshotController::class, 'create'])->name('fund-snapshots.create');
        Route::post('fund-daily-snapshots', [FundDailySnapshotController::class, 'store'])->name('fund-snapshots.store');
        Route::get('fund-daily-snapshots/{fundDailySnapshot}/edit', [FundDailySnapshotController::class, 'edit'])->name('fund-snapshots.edit');
        Route::put('fund-daily-snapshots/{fundDailySnapshot}', [FundDailySnapshotController::class, 'update'])->name('fund-snapshots.update');
        Route::delete('fund-daily-snapshots/{fundDailySnapshot}', [FundDailySnapshotController::class, 'destroy'])->name('fund-snapshots.destroy');

        // Financial Data Import
        Route::get('financial-data', [FinancialDataController::class, 'index'])->name('financial-data.index');
        Route::post('financial-data/upload', [FinancialDataController::class, 'upload'])->name('financial-data.upload');
        Route::get('financial-data/export', [FinancialDataController::class, 'export'])->name('financial-data.export');

        Route::get('backups', [BackupController::class, 'index'])->name('backups.index');
        Route::post('backups', [BackupController::class, 'store'])->name('backups.store');
        Route::get('backups/{filename}/download', [BackupController::class, 'download'])->name('backups.download');
        Route::delete('backups/{filename}', [BackupController::class, 'destroy'])->name('backups.destroy');

        Route::get('settings/email', [SiteSettingsController::class, 'email'])->name('settings.email');
        Route::put('settings/email', [SiteSettingsController::class, 'updateEmail'])->name('settings.email.update');
        Route::post('settings/email/test', [SiteSettingsController::class, 'testEmail'])->name('settings.email.test');

        Route::get('settings/urdu', [SiteSettingsController::class, 'urdu'])->name('settings.urdu');
        Route::put('settings/urdu', [SiteSettingsController::class, 'updateUrdu'])->name('settings.urdu.update');
    });
});

/*
|--------------------------------------------------------------------------
| Public Routes (Bilingual Support)
|--------------------------------------------------------------------------
*/
$urduPrefix = trim((string) config('site.locale.prefix', 'urdu'), '/');
$urduEnabled = (bool) config('site.locale.enabled', true);
$urduRouteSlugs = array_merge(
    config('site.locale.urdu_route_slugs', []),
    (array) (config('site.locale.route_slugs') ?? [])
);

$registerPublicRoutes = static function (string $prefix, string $namePrefix, bool $isUrduLocale) use ($urduRouteSlugs): void {
    $segment = static function (string $key, string $default) use ($isUrduLocale, $urduRouteSlugs): string {
        if (! $isUrduLocale) {
            return $default;
        }

        $slug = trim((string) ($urduRouteSlugs[$key] ?? ''));

        return $slug !== '' ? $slug : $default;
    };

    $middleware = [\App\Http\Middleware\SetLocale::class];
    if ($isUrduLocale) {
        $middleware[] = \App\Http\Middleware\RedirectUrduToLiveSite::class;
    }

    Route::prefix($prefix)
        ->name($namePrefix)
        ->middleware($middleware)
        ->group(function () use ($segment): void {
            Route::get('/', [HomeController::class, 'index'])->name('home');

            Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

            Route::post('/'.$segment('online-complaint-form', 'online-complaint-form'), [ContactController::class, 'sendOnlineComplaint'])
                ->name('online-complaint-form.send');

            Route::post('/brochure-lead', [BrochureLeadController::class, 'store'])
                ->name('brochure-lead.store');

            Route::post('/delete-my-account', [AccountDeletionController::class, 'send'])
                ->name('delete-account.send');

            Route::get('/'.$segment('news-and-events', 'news-and-events').'/page/{page}', [NewsEventsController::class, 'paginated'])
                ->where('page', '[1-9][0-9]*')
                ->name('news-events.paginated');
            Route::get('/'.$segment('news-and-events', 'news-and-events'), [NewsEventsController::class, 'index'])
                ->name('news-events.index');

            Route::get('/'.$segment('hajj-planner', 'hajj-planner'), [HajjPlannerController::class, 'index'])
                ->name('hajj-planner.index');
            Route::post('/api/hajj-planner/calculate', [HajjPlannerController::class, 'calculate'])
                ->name('hajj-planner.calculate');

            Route::get('/'.$segment('umrah-planner', 'umrah-planner'), [UmrahPlannerController::class, 'index'])
                ->name('umrah-planner.index');
            Route::post('/api/umrah-planner/calculate', [UmrahPlannerController::class, 'calculate'])
                ->name('umrah-planner.calculate');

            Route::get('/pdf-viewer/{file}', [PdfViewerController::class, 'show'])
                ->where('file', '.*')
                ->name('pdf-viewer.show');

            Route::get('/pdf-embed/{file}', [PdfEmbedController::class, 'show'])
                ->where('file', '.*')
                ->name('pdf-embed.show');

            Route::get('/favicon.ico', static function () {
                return redirect(asset('assets/images/favicons/cropped-5th-Pilar-Logo-32x32.png'), 301);
            })->name('favicon');

            Route::get('/search', [SearchController::class, 'index'])->name('search');

            Route::get('/{slug}', [PageController::class, 'show'])
                ->name('pages.show');
        });
};

// Register Urdu routes before the English `/{slug}` catch-all.
if ($urduEnabled && $urduPrefix !== '') {
    $registerPublicRoutes($urduPrefix, 'urdu.', true);
}
$registerPublicRoutes('', '', false);
