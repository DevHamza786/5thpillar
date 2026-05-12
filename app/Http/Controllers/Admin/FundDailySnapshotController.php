<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FundDailySnapshot;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class FundDailySnapshotController extends Controller
{
    public function index(): View
    {
        $snapshots = FundDailySnapshot::query()
            ->orderByDesc('price_date')
            ->paginate(40);

        return view('admin.fund-snapshots.index', [
            'snapshots' => $snapshots,
            'fundManagersReportPage' => Page::query()
                ->where('slug', config('cms.fund_managers_report_slug'))
                ->first(),
        ]);
    }

    public function create(): View
    {
        return view('admin.fund-snapshots.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        FundDailySnapshot::create($data);

        return redirect()
            ->route('admin.fund-snapshots.index')
            ->with('status', __('Daily fund row saved.'));
    }

    public function edit(FundDailySnapshot $fundDailySnapshot): View
    {
        return view('admin.fund-snapshots.edit', ['snapshot' => $fundDailySnapshot]);
    }

    public function update(Request $request, FundDailySnapshot $fundDailySnapshot): RedirectResponse
    {
        $fundDailySnapshot->update($this->validated($request, $fundDailySnapshot->id));

        return redirect()
            ->route('admin.fund-snapshots.index')
            ->with('status', __('Daily fund row updated.'));
    }

    public function destroy(FundDailySnapshot $fundDailySnapshot): RedirectResponse
    {
        $fundDailySnapshot->delete();

        return redirect()
            ->route('admin.fund-snapshots.index')
            ->with('status', __('Row deleted.'));
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?int $ignoreId = null): array
    {
        $unique = Rule::unique('fund_daily_snapshots', 'price_date');
        if ($ignoreId !== null) {
            $unique = $unique->ignore($ignoreId);
        }

        return $request->validate([
            'price_date' => ['required', 'date', $unique],
            'agg_bid' => ['nullable', 'string', 'max:64'],
            'agg_offer' => ['nullable', 'string', 'max:64'],
            'bal_bid' => ['nullable', 'string', 'max:64'],
            'bal_offer' => ['nullable', 'string', 'max:64'],
            'con_bid' => ['nullable', 'string', 'max:64'],
            'con_offer' => ['nullable', 'string', 'max:64'],
        ]);
    }
}
