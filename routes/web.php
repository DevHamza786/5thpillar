<?php

use Illuminate\Support\Facades\Route;

// Home
Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('/contact', [\App\Http\Controllers\ContactController::class, 'send'])->name('contact.send');

Route::post('/online-complaint-form', [\App\Http\Controllers\ContactController::class, 'sendOnlineComplaint'])
    ->name('online-complaint-form.send');

Route::post('/brochure-lead', [\App\Http\Controllers\BrochureLeadController::class, 'store'])
    ->name('brochure-lead.store');

/*
|--------------------------------------------------------------------------
| Legacy Static Page Routes
|--------------------------------------------------------------------------
|
| Disabled to prevent conflicts with the imported WordPress page slugs that
| should now resolve through the dynamic `/{slug}` route below.
|
| // Company
| Route::prefix('company')->name('company.')->group(function () {
|     Route::get('/about-us',              [\App\Http\Controllers\CompanyController::class, 'about'])->name('about');
|     Route::get('/pacra-rating',          [\App\Http\Controllers\CompanyController::class, 'pacra'])->name('pacra');
|     Route::get('/vision-mission',        [\App\Http\Controllers\CompanyController::class, 'vision'])->name('vision');
|     Route::get('/management-team',       [\App\Http\Controllers\CompanyController::class, 'team'])->name('team');
|     Route::get('/corporate-information', [\App\Http\Controllers\CompanyController::class, 'corporate'])->name('corporate');
|     Route::get('/code-of-conduct',       [\App\Http\Controllers\CompanyController::class, 'conduct'])->name('conduct');
|     Route::get('/waqf-deed',             [\App\Http\Controllers\CompanyController::class, 'waqf'])->name('waqf');
|     Route::get('/ptf-policies',          [\App\Http\Controllers\CompanyController::class, 'ptf'])->name('ptf');
|     Route::get('/privacy-policy',        [\App\Http\Controllers\CompanyController::class, 'privacy'])->name('privacy');
| });
|
| // Governance
| Route::prefix('governance')->name('governance.')->group(function () {
|     Route::get('/board-of-directors',      [\App\Http\Controllers\GovernanceController::class, 'board'])->name('board');
|     Route::get('/board-committees',        [\App\Http\Controllers\GovernanceController::class, 'boardCommittees'])->name('board-committees');
|     Route::get('/management-committees',   [\App\Http\Controllers\GovernanceController::class, 'mgmtCommittees'])->name('mgmt-committees');
|     Route::get('/shariah-advisor',         [\App\Http\Controllers\GovernanceController::class, 'shariah'])->name('shariah');
|     Route::get('/external-auditors',       [\App\Http\Controllers\GovernanceController::class, 'auditors'])->name('auditors');
|     Route::get('/legal-advisor',           [\App\Http\Controllers\GovernanceController::class, 'legal'])->name('legal');
|     Route::get('/pattern-of-shareholding', [\App\Http\Controllers\GovernanceController::class, 'shareholding'])->name('shareholding');
| });
|
| // Products
| Route::prefix('products')->name('products.')->group(function () {
|     Route::get('/hajj-savings-plan',    [\App\Http\Controllers\ProductController::class, 'hajj'])->name('hajj');
|     Route::get('/umrah-savings-plan',   [\App\Http\Controllers\ProductController::class, 'umrah'])->name('umrah');
|     Route::get('/regular-savings-plan', [\App\Http\Controllers\ProductController::class, 'regular'])->name('regular');
|     Route::get('/corporate-takaful',    [\App\Http\Controllers\ProductController::class, 'corporate'])->name('corporate');
| });
|
| // Investors Relations
| Route::prefix('investors')->name('investors.')->group(function () {
|     Route::get('/financial-statements',  [\App\Http\Controllers\InvestorController::class, 'financial'])->name('financial');
|     Route::get('/online-complaint-form', [\App\Http\Controllers\InvestorController::class, 'complaint'])->name('complaint');
|     Route::post('/online-complaint-form',[\App\Http\Controllers\InvestorController::class, 'complaintSend'])->name('complaint.send');
|     Route::get('/contact-details',       [\App\Http\Controllers\InvestorController::class, 'contact'])->name('contact');
|     Route::get('/complaints',            [\App\Http\Controllers\InvestorController::class, 'complaints'])->name('complaints');
|     Route::get('/secp-sdms',             [\App\Http\Controllers\InvestorController::class, 'secp'])->name('secp');
|
|     Route::prefix('unit-linked-funds')->name('funds.')->group(function () {
|         Route::get('/',                     [\App\Http\Controllers\FundController::class, 'index'])->name('index');
|         Route::get('/target-asset-mix',     [\App\Http\Controllers\FundController::class, 'targetAsset'])->name('target-asset');
|         Route::get('/daily-fund-prices',    [\App\Http\Controllers\FundController::class, 'dailyPrices'])->name('daily-prices');
|         Route::get('/fund-managers-report', [\App\Http\Controllers\FundController::class, 'report'])->name('report');
|         Route::get('/accounts',             [\App\Http\Controllers\FundController::class, 'accounts'])->name('accounts');
|     });
| });
|
| // Media
| Route::prefix('media')->name('media.')->group(function () {
|     Route::get('/news-events', [\App\Http\Controllers\MediaController::class, 'news'])->name('news');
|     Route::get('/memberships', [\App\Http\Controllers\MediaController::class, 'memberships'])->name('memberships');
| });
|
| // Downloads
| Route::prefix('downloads')->name('downloads.')->group(function () {
|     Route::get('/unclaimed-benefits', [\App\Http\Controllers\DownloadController::class, 'unclaimed'])->name('unclaimed');
|     Route::get('/forms',              [\App\Http\Controllers\DownloadController::class, 'forms'])->name('forms');
| });
|
| // Contact
| Route::get('/contact', [\App\Http\Controllers\ContactController::class, 'index'])->name('contact');
| Route::post('/contact', [\App\Http\Controllers\ContactController::class, 'send'])->name('contact.send');
|
| // Sitemap
| Route::get('/sitemap', [\App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');
|
| // Language switcher
| Route::get('/language/{locale}', function (string $locale) {
|     if (! in_array($locale, ['en', 'ur'])) {
|         $locale = 'en';
|     }
|
|     session(['locale' => $locale]);
|
|     return redirect()->back();
| })->name('language.switch');
|
*/

// News & Events (archive + pagination — must be before catch-all slug route)
Route::get('/news-and-events/page/{page}', [\App\Http\Controllers\NewsEventsController::class, 'paginated'])
    ->where('page', '[1-9][0-9]*')
    ->name('news-events.paginated');
Route::get('/news-and-events', [\App\Http\Controllers\NewsEventsController::class, 'index'])
    ->name('news-events.index');

// Imported WordPress pages
Route::get('/{slug}', [\App\Http\Controllers\PageController::class, 'show'])
    ->name('pages.show');
