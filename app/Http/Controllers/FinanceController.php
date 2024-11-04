<?php

namespace App\Http\Controllers;

use App\Models\Finance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreUpdateFinanceRequest;

class FinanceController extends Controller
{
    protected function getUserId()
    {
        return Auth::id();
    }

    public function showFinances($dashboardType)
    {
        $modules = collect(session('modules', []));
        $user = Auth::user();
        $service = null;
        $serviceType = null;

        if ($dashboardType == 'promoter') {
            $service = $user->promoters()->first();
            $serviceType = 'App\Models\Promoter';
        } elseif ($dashboardType == 'band') {
            $service = $user->otherService("Band")->first();
            $serviceType = 'App\Models\OtherService';
        } elseif ($dashboardType == 'designer') {
            $service = $user->otherService("Designer")->first();
            $serviceType = 'App\Models\OtherService';
        } elseif ($dashboardType == 'photographer') {
            $service = $user->otherService("Photographer")->first();
            $serviceType = 'App\Models\OtherService';
        } elseif ($dashboardType == 'videographer') {
            $service = $user->otherService("Videographer")->first();
            $serviceType = 'App\Models\OtherService';
        } elseif ($dashboardType == 'venue') {
            $service = $user->venues()->first();
            $serviceType = 'App\Models\Venue';
        }

        $finances = Finance::where('serviceable_id', $service->id)
            ->where('serviceable_type', $serviceType)
            ->get();

        return view('admin.dashboards.show-finances', [
            'userId' => $this->getUserId(),
            'dashboardType' => $dashboardType,
            'modules' => $modules,
            'finances' => $finances,
            'service' => $service,
            'serviceType' => $serviceType,
        ]);
    }

    public function createFinance($dashboardType)
    {
        $modules = collect(session('modules', []));

        return view('admin.dashboards.new-finance', [
            'userId' => $this->getUserId(),
            'dashboardType' => $dashboardType,
            'modules' => $modules,
        ]);
    }

    public function storeFinance($dashboardType, StoreUpdateFinanceRequest $request)
    {
        $modules = collect(session('modules', []));
        $user = Auth::user();
        $service = null;
        $serviceType = null;

        if ($dashboardType == 'promoter') {
            $service = $user->promoters()->first();
            $serviceType = 'App\Models\Promoter';
        } elseif ($dashboardType == 'band') {
            $service = $user->otherService("Band")->first();
            $serviceType = 'App\Models\OtherService';
        } elseif ($dashboardType == 'designer') {
            $service = $user->otherService("Designer")->first();
            $serviceType = 'App\Models\OtherService';
        } elseif ($dashboardType == 'photographer') {
            $service = $user->otherService("Photographer")->first();
            $serviceType = 'App\Models\OtherService';
        } elseif ($dashboardType == 'videographer') {
            $service = $user->otherService("Videographer")->first();
            $serviceType = 'App\Models\OtherService';
        } elseif ($dashboardType == 'venue') {
            $service = $user->venues()->first();
            $serviceType = 'App\Models\Venue';
        }

        try {
            $desiredProfit = $request->validated()['desired_profit'];
            $budgetName = $request->validated()['budget_name'];
            $dateFrom = $request->validated()['date_from'];
            $dateTo = $request->validated()['date_to'];
            $linkToEvent = $request->validated()['link_to_event'];
            $incomePresale = $request->validated()['income_presale'];
            $incomeOtd = $request->validated()['income_otd'];
            $incomeOthers = $request->input('income_other', []);
            $outgoingVenue = $request->validated()['outgoing_venue'];
            $outgoingBand = $request->validated()['outgoing_band'];
            $outgoingPromotion = $request->validated()['outgoing_promotion'];
            $outgoingRider = $request->validated()['outgoing_rider'];
            $outgoingOthers = $request->input('outgoing_other', []);
            $incomeTotal = $request->validated()['income_total'];
            $outgoingTotal = $request->validated()['outgoing_total'];
            $profitTotal = $request->validated()['profit_total'];
            $desiredProfitRemaining = $request->validated()['desired_profit_remaining'];

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


            $newPromoterBudget = Finance::create([
                'user_id' => $user->id,
                'serviceable_id' => $service->id,
                'serviceable_type' => $serviceType,
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
                'redirect_url' => route(
                    'admin.dashboard.show-finance',
                    [
                        'userId' => $this->getUserId(),
                        'dashboardType' => $dashboardType,
                        'modules' => $modules,
                        'id' => $newPromoterBudget->id,
                    ]
                )
            ]);
        } catch (\Exception $e) {
            Log::error('Error saving budget:', ['message' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function showSingleFinance($dashboardType, $id)
    {
        $modules = collect(session('modules', []));

        $finance = Finance::findOrFail($id)->load('user', 'serviceable');

        return view('admin.dashboards.show-finance', [
            'userId' => $this->getUserId(),
            'dashboardType' => $dashboardType,
            'modules' => $modules,
            'finance' => $finance,
        ]);
    }
}