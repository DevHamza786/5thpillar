<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\CmsMediaController;
use App\Http\Controllers\Admin\CmsPageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FinancialDataController;
use App\Http\Controllers\Admin\FundDailySnapshotController;
use App\Http\Controllers\Admin\NavMenuController;
use App\Http\Controllers\Admin\NavMenuMediaController;
use App\Http\Controllers\Admin\PageSectionController;
use App\Http\Controllers\BrochureLeadController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsEventsController;
use App\Http\Controllers\HajjPlannerController;
use App\Http\Controllers\UmrahPlannerController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin CMS
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store'])->name('login.store');

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('logout', [LoginController::class, 'destroy'])->name('logout');
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('pages', [CmsPageController::class, 'index'])->name('pages.index');
        Route::get('pages/create', [CmsPageController::class, 'create'])->name('pages.create');
        Route::post('pages', [CmsPageController::class, 'store'])->name('pages.store');
        Route::get('pages/{page}/edit', [CmsPageController::class, 'edit'])->name('pages.edit');
        Route::put('pages/{page}', [CmsPageController::class, 'update'])->name('pages.update');

        Route::post('pages/{page}/sections', [PageSectionController::class, 'store'])->name('pages.sections.store');
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
    });
});

/*
|--------------------------------------------------------------------------
| Public Routes (Bilingual Support)
|--------------------------------------------------------------------------
*/
// Register `urdu` routes before the English `/{slug}` catch-all, otherwise `/urdu` is handled as slug "urdu" and 404s.
foreach (['urdu', ''] as $prefix) {
    Route::prefix($prefix)
        ->name($prefix ? "$prefix." : '')
        ->middleware([\App\Http\Middleware\SetLocale::class])
        ->group(function () {

            // Home
            Route::get('/', [HomeController::class, 'index'])->name('home');

            Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

            Route::post('/online-complaint-form', [ContactController::class, 'sendOnlineComplaint'])
                ->name('online-complaint-form.send');

            Route::post('/brochure-lead', [BrochureLeadController::class, 'store'])
                ->name('brochure-lead.store');

            // News & Events
            Route::get('/news-and-events/page/{page}', [NewsEventsController::class, 'paginated'])
                ->where('page', '[1-9][0-9]*')
                ->name('news-events.paginated');
            Route::get('/news-and-events', [NewsEventsController::class, 'index'])
                ->name('news-events.index');

            // Hajj Planner
            Route::get('/hajj-planner', [HajjPlannerController::class, 'index'])
                ->name('hajj-planner.index');
            Route::post('/api/hajj-planner/calculate', [HajjPlannerController::class, 'calculate'])
                ->name('hajj-planner.calculate');

            Route::get('/umrah-planner', [UmrahPlannerController::class, 'index'])
                ->name('umrah-planner.index');
            Route::post('/api/umrah-planner/calculate', [UmrahPlannerController::class, 'calculate'])
                ->name('umrah-planner.calculate');

            // Imported WordPress pages (Catch-all slug)
            Route::get('/{slug}', [PageController::class, 'show'])
                ->name('pages.show');
        });
}
