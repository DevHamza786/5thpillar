<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BrochureLead;
use App\Models\FormSubmission;
use App\Models\FundDailySnapshot;
use App\Models\HajjPlanLead;
use App\Models\Page;
use App\Services\WebsiteBackupService;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $publishedPageCount = Page::query()->where('status', Page::STATUS_PUBLISHED)->count();
        $publishedUrduPageCount = Page::query()->where('status_ur', Page::STATUS_PUBLISHED)->count();
        $totalPageCount = Page::query()->count();

        $brochureLeadCount = BrochureLead::query()->count();
        $brochureLeadsByProduct = BrochureLead::query()
            ->select('brochure_key', DB::raw('count(*) as total'))
            ->groupBy('brochure_key')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        $hajjPlanLeadCount = HajjPlanLead::query()
            ->where(function ($query) {
                $query->where('plan_type', 'hajj')->orWhereNull('plan_type');
            })
            ->count();
        $umrahPlanLeadCount = HajjPlanLead::query()->where('plan_type', 'umrah')->count();
        $plannerLeadCount = $hajjPlanLeadCount + $umrahPlanLeadCount;

        $formSubmissionCounts = [
            FormSubmission::TYPE_INQUIRY => FormSubmission::query()->where('form_type', FormSubmission::TYPE_INQUIRY)->count(),
            FormSubmission::TYPE_COMPLAINT => FormSubmission::query()->where('form_type', FormSubmission::TYPE_COMPLAINT)->count(),
            FormSubmission::TYPE_ONLINE_COMPLAINT => FormSubmission::query()->where('form_type', FormSubmission::TYPE_ONLINE_COMPLAINT)->count(),
        ];
        $formSubmissionTotal = array_sum($formSubmissionCounts);

        $latestSnapshot = FundDailySnapshot::query()->orderByDesc('price_date')->first();
        $snapshotCount = FundDailySnapshot::query()->count();

        $legacySlugs = config('cms.legacy_demo_slugs', []);
        $legacyPageCount = $legacySlugs === []
            ? 0
            : Page::query()
                ->where(function ($query) use ($legacySlugs) {
                    $query->whereIn('slug', $legacySlugs)
                        ->orWhereIn('view_key', $legacySlugs);
                })
                ->where('status', Page::STATUS_PUBLISHED)
                ->count();

        $homePage = Page::query()->where('slug', 'home')->first();

        $backupCount = count(app(WebsiteBackupService::class)->listBackups());

        $recentBrochureLeads = BrochureLead::query()->latest()->limit(6)->get();
        $recentPlannerLeads = HajjPlanLead::query()->latest()->limit(6)->get();
        $recentFormSubmissions = FormSubmission::query()->latest()->limit(6)->get();

        return view('admin.dashboard', [
            'publishedPageCount' => $publishedPageCount,
            'publishedUrduPageCount' => $publishedUrduPageCount,
            'totalPageCount' => $totalPageCount,
            'brochureLeadCount' => $brochureLeadCount,
            'brochureLeadsByProduct' => $brochureLeadsByProduct,
            'hajjPlanLeadCount' => $hajjPlanLeadCount,
            'umrahPlanLeadCount' => $umrahPlanLeadCount,
            'plannerLeadCount' => $plannerLeadCount,
            'formSubmissionCounts' => $formSubmissionCounts,
            'formSubmissionTotal' => $formSubmissionTotal,
            'latestSnapshot' => $latestSnapshot,
            'snapshotCount' => $snapshotCount,
            'recentBrochureLeads' => $recentBrochureLeads,
            'recentPlannerLeads' => $recentPlannerLeads,
            'recentFormSubmissions' => $recentFormSubmissions,
            'fundManagersReportPage' => Page::query()
                ->where('slug', config('cms.fund_managers_report_slug'))
                ->first(),
            'legacyPageCount' => $legacyPageCount,
            'homePage' => $homePage,
            'backupCount' => $backupCount,
        ]);
    }
}
