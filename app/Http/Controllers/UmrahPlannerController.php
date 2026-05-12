<?php

namespace App\Http\Controllers;

use App\Models\FinancialData;
use App\Models\HajjPlanLead;
use App\Services\FinancialPlannerEngine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UmrahPlannerController extends Controller
{
    public function index()
    {
        $route = app()->getLocale() === 'ur' ? 'urdu.hajj-planner.index' : 'hajj-planner.index';

        return redirect()->route($route, ['plan' => 'umrah']);
    }

    public function calculate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'city' => ['required', 'string', 'max:120', Rule::in(config('planner_cities'))],
            'age' => 'required|integer|min:18|max:55',
            'plan_term' => 'required|integer|in:5,7,10,15',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $age = (int) $request->age;
        $term = (int) $request->plan_term;

        if ($age === 55) {
            $term = 5;
        }

        $product = FinancialPlannerEngine::PRODUCT_UMRAH;

        $data9 = FinancialData::query()
            ->where('product', $product)
            ->where('age', $age)
            ->where('term', $term)
            ->where('growth_rate', 0.09)
            ->first();

        $data13 = FinancialData::query()
            ->where('product', $product)
            ->where('age', $age)
            ->where('term', $term)
            ->where('growth_rate', 0.13)
            ->first();

        if (! $data9 || ! $data13) {
            return response()->json([
                'success' => false,
                'message' => __('Financial data not found for the selected age and plan term. Import the Umrah marketing workbook into :table (Admin → Financial Data, or run :command).', [
                    'table' => 'financial_data',
                    'command' => 'php artisan financial-data:import --product=umrah',
                ]),
            ], 422);
        }

        $lead = HajjPlanLead::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->city,
            'message' => null,
            'age' => $age,
            'hajj_year' => $term,
            'plan_type' => 'umrah',
        ]);

        $built = FinancialPlannerEngine::buildResponseCharts($data9, $data13, $term, $product);

        $response = [
            'success' => true,
            'summary' => [
                'age' => $age,
                'term' => $term,
                'annual_contribution' => (float) $data9->annual_contribution,
                'takaful_benefit' => (float) $data9->takaful_benefit,
            ],
            'totals' => [
                'contribution' => $built['totals']['contribution'],
                'return_9' => $built['totals']['return_9'],
                'return_13' => $built['totals']['return_13'],
            ],
            'charts' => $built['charts'],
        ];

        try {
            $adminEmail = config('mail.from.address', 'admin@5thpillartakaful.com');
            Mail::to($adminEmail)->send(new \App\Mail\HajjPlanLeadNotification($lead, $response));
        } catch (\Exception $e) {
        }

        return response()->json($response);
    }
}
