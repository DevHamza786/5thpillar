<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FundDailySnapshot;
use App\Models\Page;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'pageCount' => Page::query()->count(),
            'snapshotCount' => FundDailySnapshot::query()->count(),
            'fundManagersReportPage' => Page::query()
                ->where('slug', config('cms.fund_managers_report_slug'))
                ->first(),
        ]);
    }
}
