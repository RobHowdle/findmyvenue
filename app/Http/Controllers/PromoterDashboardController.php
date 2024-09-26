<?php

namespace App\Http\Controllers;

use App\Models\Finance;
use App\Models\Promoter;
use Illuminate\Http\Request;
use App\Models\PromoterReview;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class PromoterDashboardController extends Controller
{
    public function index()
    {
        $pendingReviews = PromoterReview::with('promoter')->where('review_approved', '0')->whereNull('deleted_at')->count();
        $promoter = Auth::user()->load('promoters');

        return view('admin.dashboards.promoter-dash', compact([
            'pendingReviews',
            'promoter',
        ]));
    }

    public function promoterFinances()
    {
        $promoter = Auth::user();

        return view('admin.dashboards.promoter.promoter-finances', compact('promoter'));
    }

    public function createNewPromoterBudget()
    {
        $promoter = Auth::user();

        return view('admin.dashboards.promoter.promoter-new-finance', compact('promoter'));
    }

    public function saveNewPromoterBudget(Request $request)
    {
        $promoter = Auth::user()->load('promoters');
        $promoterCompany = $promoter->promoters()->first();

        try {
            $validator = Validator::make($request->all(), [
                'desired_profit' => 'nullable|numeric|regex:/^\d+(\.\d{1,2})?$/',
                'budget_name' => 'required|string',
                'date_from' => 'required|date',
                'date_to' => 'required|date',
                'link_to_event' => 'nullable|url',
                'income_presale' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
                'income_otd' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
                'income_other' => 'array|nullable',
                'income_other.*' => 'nullable|numeric|regex:/^\d+(\.\d{1,2})?$/',
                'outgoing_venue' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
                'outgoing_band' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
                'outgoing_promotion' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
                'outgoing_rider' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
                'outgoing_other' => 'array|nullable',
                'outgoing_other.*' => 'nullable|numeric|regex:/^\d+(\.\d{1,2})?$/',
                'income_total' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
                'outgoing_total' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
                'profit_total' => 'required|numeric',
                'desired_profit_remaining' => 'nullable|numeric',
            ]);

            if ($validator->fails()) {
                Log::warning('Validation failed', $validator->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors(),
                ], 422);
            }

            $desiredProfit = $validator->validated()['desired_profit'];
            $budgetName = $validator->validated()['budget_name'];
            $dateFrom = $validator->validated()['date_from'];
            $dateTo = $validator->validated()['date_to'];
            $linkToEvent = $validator->validated()['link_to_event'];
            $incomePresale = $validator->validated()['income_presale'];
            $incomeOtd = $validator->validated()['income_otd'];
            $incomeOthers = $request->input('income_other', []);
            $outgoingVenue = $validator->validated()['outgoing_venue'];
            $outgoingBand = $validator->validated()['outgoing_band'];
            $outgoingPromotion = $validator->validated()['outgoing_promotion'];
            $outgoingRider = $validator->validated()['outgoing_rider'];
            $outgoingOthers = $request->input('outgoing_other', []);
            $incomeTotal = $validator->validated()['income_total'];
            $outgoingTotal = $validator->validated()['outgoing_total'];
            $profitTotal = $validator->validated()['profit_total'];
            $desiredProfitRemaining = $validator->validated()['desired_profit_remaining'];

            $incoming = json_encode([
                [
                    'field' => 'income_presale',
                    'value' => $incomePresale,
                ],
                [
                    'field' => 'income_otd',
                    'value' => $incomeOtd,
                ]
            ]);

            $outgoing = json_encode([
                [
                    'field' => 'outgoing_venue',
                    'value' => $outgoingVenue,
                ],
                [
                    'field' => 'outgoing_band',
                    'value' => $outgoingBand,
                ],
                [
                    'field' => 'outgoing_promotion',
                    'value' => $outgoingPromotion,
                ],
                [
                    'field' => 'outgoing_rider',
                    'value' => $outgoingRider,
                ],
            ]);

            $totalIncomeOther = array_sum($incomeOthers);
            $totalOutgoingOther = array_sum($outgoingOthers);
            $incomeOthersJson = json_encode($incomeOthers);
            $outgoingOthersJson = json_encode($outgoingOthers);

            $serviceableId = null;
            $serviceableType = null;

            if ($promoterCompany) {
                $serviceableId = $promoterCompany->id;
                $serviceableType = 'App\Models\Promoter';
            }

            $newPromoterBudget = Finance::create([
                'user_id' => $promoter->id,
                'serviceable_id' => $serviceableId,
                'serviceable_type' => $serviceableType,
                'finance_type' => 'Budget',
                'name' => $budgetName,
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
                'external_link' => $linkToEvent,
                'incoming' => $incoming,
                'other_incoming' => $incomeOthersJson,
                'outgoing' => $outgoing,
                'other_outgoing' => $outgoingOthersJson,
                'desired_profit' => $desiredProfit,
                'total_incoming' => $incomeTotal,
                'total_outgoing' => $outgoingTotal,
                'total_profit' => $profitTotal,
                'total_remaining_to_desired_profit' => $desiredProfitRemaining,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Your Budget Saved!',
                // 'view' => view(
                //     'admin.dashboards.promoter.promoter-new-finance',
                //     compact('promoter')
                // )
            ]);
        } catch (\Exception $e) {
            Log::error('Error saving budget:', ['message' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(), // Send the error message directly
            ]);
        }
    }
}
