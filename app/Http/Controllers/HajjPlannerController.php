<?php

namespace App\Http\Controllers;

use App\Models\FinancialData;
use App\Models\HajjPlanLead;
use App\Services\FinancialPlannerEngine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class HajjPlannerController extends Controller
{
    public function index()
    {
        $initialProduct = request()->query('plan') === 'umrah' ? 'umrah' : 'hajj';

        return view('hajj-planner', [
            'plannerCities' => config('planner_cities'),
            'initialProduct' => $initialProduct,
        ]);
    }

    public function calculate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'city' => ['required', 'string', 'max:120', Rule::in(config('planner_cities'))],
            'age' => 'required|integer|min:18|max:55',
            'hajj_year' => 'required|integer|in:10,15,20,25',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $age = (int) $request->age;
        $term = (int) $request->hajj_year;

        if ($age === 55) {
            $term = 10;
        }

        $product = FinancialPlannerEngine::PRODUCT_HAJJ;

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
                'message' => __('Financial data not found for the selected age and plan term. Import the spreadsheet into the :table table (Admin → Financial Data, or run :command).', [
                    'table' => 'financial_data',
                    'command' => 'php artisan financial-data:import',
                ]),
            ], 422);
        }

        $lead = HajjPlanLead::create(array_merge(
            $request->only(['name', 'email', 'phone', 'age', 'hajj_year']),
            [
                'address' => $request->city,
                'message' => null,
                'plan_type' => 'hajj',
            ]
        ));

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
