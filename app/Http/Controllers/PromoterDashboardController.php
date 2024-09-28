<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Finance;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\PromoterReview;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class PromoterDashboardController extends Controller
{
    public function index()
    {
        $pendingReviews = PromoterReview::with('promoter')->where('review_approved', '0')->whereNull('deleted_at')->count();
        $promoter = Auth::user()->load('promoters');

        if (!$promoter) {
            return redirect()->back()->withErrors('No promoter company linked to this user.');
        }

        return view('admin.dashboards.promoter-dash', compact([
            'pendingReviews',
            'promoter',
        ]));
    }

    public function promoterFinances()
    {
        $promoter = Auth::user()->load('promoters');

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
                'view' => view(
                    'admin.dashboards.promoter.promoter-finances',
                    compact('promoter')
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

    public function getFinanceData(Request $request)
    {
        $serviceableId = $request->input('serviceable_id');
        $serviceableType = \App\Models\Promoter::class;;
        $filter = $request->input('filter');
        $date = $request->input('date');

        // Initialize response arrays
        $dates = [];
        $totalIncome = [];
        $totalOutgoing = [];
        $totalProfit = [];
        $financeRecords = [];

        // Based on the filter, adjust query for finances table
        $query = Finance::where('serviceable_id', $serviceableId)
            ->where('serviceable_type', $serviceableType);

        switch ($filter) {
            case 'day':
                $finances = Finance::where('serviceable_id', $serviceableId)
                    ->where('serviceable_type', $serviceableType)
                    ->whereDate('date_from', $date)
                    ->get();

                // Get totals for a single day
                $data = Finance::select(
                    DB::raw('SUM(total_incoming) as totalIncome'),
                    DB::raw('SUM(total_outgoing) as totalOutgoing'),
                    DB::raw('SUM(total_profit) as totalProfit')
                )
                    ->where('serviceable_id', $serviceableId)
                    ->where('serviceable_type', $serviceableType)
                    ->whereDate('date_from', $date)
                    ->first();

                $financeRecords = $finances->map(function ($finance) {
                    return [
                        'id' => $finance->id,
                        'name' => $finance->name,
                        'link' => route('promoter.dashboard.finances.show', $finance->id),
                    ];
                });

                // Format the data for response
                $response = [
                    'dates' => [$date], // Just the selected day
                    'totalIncome' => [$data->totalIncome ?: 0], // Wrap in array to match structure
                    'totalOutgoing' => [$data->totalOutgoing ?: 0],
                    'totalProfit' => [$data->totalProfit ?: 0],
                    'financeRecords' => $financeRecords,
                ];
                return response()->json($response);
                break;
            case 'week':
                $dates = explode(' to ', $date); // Split the date range

                if (count($dates) === 2) {
                    $startDate = Carbon::parse($dates[0])->startOfDay();
                    $endDate = Carbon::parse($dates[1])->endOfDay();

                    $query->where(function ($q) use ($startDate, $endDate) {
                        $q->whereBetween('date_from', [$startDate, $endDate])
                            ->orWhereBetween('date_to', [$startDate, $endDate]);
                    });
                }

                $finances = $query->get();
                $financeRecords = $finances->map(function ($finance) {
                    return [
                        'id' => $finance->id,
                        'name' => $finance->name,
                        'link' => route('promoter.dashboard.finances.show', $finance->id),
                    ];
                });

                // Prepare the response with the appropriate data for your charts
                return response()->json([
                    'dates' => $finances->pluck('date_from'),
                    'totalIncome' => $finances->pluck('total_incoming'),
                    'totalOutgoing' => $finances->pluck('total_outgoing'),
                    'totalProfit' => $finances->pluck('total_profit'),
                    'financeRecords' => $financeRecords,
                ]);
                break;
            case 'month':
                // Gather data for each day of the month
                $startOfMonth = Carbon::parse($date)->startOfMonth();
                $endOfMonth = Carbon::parse($date)->endOfMonth();

                // Fetch daily totals for the month
                $data = Finance::select(DB::raw('DATE(date_from) as date, 
                SUM(total_incoming) as totalIncome,
                SUM(total_outgoing) as totalOutgoing,
                SUM(total_profit) as totalProfit'))
                    ->where('serviceable_id', $serviceableId)
                    ->where('serviceable_type', $serviceableType)
                    ->whereBetween('date_from', [$startOfMonth, $endOfMonth])
                    ->groupBy(DB::raw('DATE(date_from)'))
                    ->get();

                $finances = Finance::where('serviceable_id', $serviceableId)
                    ->where('serviceable_type', $serviceableType)
                    ->whereBetween('date_from', [$startOfMonth, $endOfMonth])
                    ->get();

                $financeRecords = $finances->map(function ($finance) {
                    return [
                        'id' => $finance->id,
                        'name' => $finance->name,
                        'link' => route('promoter.dashboard.finances.show', $finance->id),
                    ];
                });

                foreach ($data as $entry) {
                    $dates[] = $entry->date;
                    $totalIncome[] = $entry->totalIncome ?: 0; // Use 0 if null
                    $totalOutgoing[] = $entry->totalOutgoing ?: 0; // Use 0 if null
                    $totalProfit[] = $entry->totalProfit ?: 0; // Use 0 if null
                }

                // Format the data for response
                $response = [
                    'dates' => $data->pluck('date'),
                    'totalIncome' => $data->pluck('totalIncome'),
                    'totalOutgoing' => $data->pluck('totalOutgoing'),
                    'totalProfit' => $data->pluck('totalProfit'),
                    'financeRecords' => $financeRecords,
                ];
                return response()->json($response);
                break;
            case 'year':
                // Get start and end of the year
                $startOfYear = Carbon::parse($date)->startOfYear();
                $endOfYear = Carbon::parse($date)->endOfYear();

                // Fetch monthly totals for the year
                $data = Finance::select(DB::raw('MONTH(date_from) as month, 
                        SUM(total_incoming) as totalIncome,
                        SUM(total_outgoing) as totalOutgoing,
                        SUM(total_profit) as totalProfit'))
                    ->where('serviceable_id', $serviceableId)
                    ->where('serviceable_type', $serviceableType)
                    ->whereBetween('date_from', [$startOfYear, $endOfYear])
                    ->groupBy(DB::raw('MONTH(date_from)'))
                    ->get();

                // Format the response
                $response = [
                    'dates' => $data->pluck('month'),
                    'totalIncome' => $data->pluck('totalIncome'),
                    'totalOutgoing' => $data->pluck('totalOutgoing'),
                    'totalProfit' => $data->pluck('totalProfit'),
                ];
                return response()->json($response);
                break;
        }
        $finances = $query->get();

        // Calculate totals
        $totalIncome = $finances->sum('total_incoming');
        $totalOutgoing = $finances->sum('total_outgoing');
        $totalProfit = $finances->sum('total_profit');

        // Getting list of finance data for link generation
        $financeRecords = $finances->map(function ($finance) {
            return [
                'id' => $finance->id,
                'name' => $finance->name,
                'link' => route('promoter.dashboard.finances.show', $finance->id),
            ];
        });

        return response()->json([
            'totalIncome' => $totalIncome,
            'totalOutgoing' => $totalOutgoing,
            'totalProfit' => $totalProfit,
            'financeRecords' => $financeRecords,
        ]);
    }

    public function exportFinances(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|string',
            'filter' => 'required|string',
            'totalIncome' => 'required|string',
            'totalOutgoing' => 'required|string',
            'totalProfit' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Retrieve the data from the request
        $dateRange = $request->input('date');
        $filterValue = $request->input('filter');
        $totalIncome = $request->input('totalIncome');
        $totalOutgoing = $request->input('totalOutgoing');
        $totalProfit = $request->input('totalProfit');

        // Convert inputs to arrays if necessary
        $totalIncome = is_array($totalIncome) ? $totalIncome : [$totalIncome];
        $totalOutgoing = is_array($totalOutgoing) ? $totalOutgoing : [$totalOutgoing];
        $totalProfit = is_array($totalProfit) ? $totalProfit : [$totalProfit];

        // Prepare the data for the PDF
        $data = [];

        if ($filterValue === 'day') {
            // Handle single day case
            $data[] = [
                'date' => $dateRange,
                'totalIncome' => $totalIncome[0],
                'totalOutgoing' => $totalOutgoing[0],
                'totalProfit' => $totalProfit[0],
            ];
        } elseif ($filterValue === 'week') {
            // Handle week case
            $dates = explode(' to ', $dateRange);

            if (count($dates) !== 2) {
                return response()->json(['errors' => ['Invalid date range format']], 422);
            }

            list($startDate, $endDate) = $dates;

            // Validate the dates
            if (!strtotime($startDate) || !strtotime($endDate)) {
                return response()->json(['errors' => ['Invalid date format']], 422);
            }

            $currentDate = strtotime($startDate);
            $endDateTimestamp = strtotime($endDate);

            while ($currentDate <= $endDateTimestamp) {
                $formattedDate = date('Y-m-d', $currentDate);
                $data[] = [
                    'date' => $formattedDate,
                    'totalIncome' => $totalIncome[0],
                    'totalOutgoing' => $totalOutgoing[0],
                    'totalProfit' => $totalProfit[0],
                ];
                $currentDate = strtotime('+1 day', $currentDate);
            }
        } elseif ($filterValue === 'month') {
            // Handle month case
            $month = $dateRange; // This should be in 'YYYY-MM' format
            $year = substr($month, 0, 4);
            $monthNumber = substr($month, 5, 2);

            // Get the total days in the month
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $monthNumber, $year);

            for ($day = 1; $day <= $daysInMonth; $day++) {
                $formattedDate = sprintf('%04d-%02d-%02d', $year, $monthNumber, $day);
                $data[] = [
                    'date' => $formattedDate,
                    'totalIncome' => $totalIncome[0],
                    'totalOutgoing' => $totalOutgoing[0],
                    'totalProfit' => $totalProfit[0],
                ];
            }
        } elseif ($filterValue === 'year') {
            // Handle year case
            $year = $dateRange; // This should be a year in 'YYYY' format

            // Validate the year
            if (!preg_match('/^\d{4}$/', $year)) {
                return response()->json(['errors' => ['Invalid year format']], 422);
            }

            // Loop through each month of the year
            for ($month = 1; $month <= 12; $month++) {
                $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                $totalIncomeForMonth = $totalIncome[0]; // Adjust if you want to calculate per month
                $totalOutgoingForMonth = $totalOutgoing[0]; // Adjust if you want to calculate per month
                $totalProfitForMonth = $totalProfit[0]; // Adjust if you want to calculate per month

                for ($day = 1; $day <= $daysInMonth; $day++) {
                    $formattedDate = sprintf('%04d-%02d-%02d', $year, $month, $day);
                    $data[] = [
                        'date' => $formattedDate,
                        'totalIncome' => $totalIncomeForMonth,
                        'totalOutgoing' => $totalOutgoingForMonth,
                        'totalProfit' => $totalProfitForMonth,
                    ];
                }
            }
        }

        // Generate the PDF
        $pdf = Pdf::loadView('pdf.finances', compact('data'));
        $pdfContent = $pdf->output();

        // Return the PDF to the browser
        return response()->stream(function () use ($pdfContent) {
            echo $pdfContent;
        }, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="finances_graph_data.pdf"',
        ]);
    }

    public function showSingleFinance($id)
    {
        $promoter = Auth::user()->load('promoters');

        $finance = Finance::findOrFail($id)->load('user', 'serviceable');
        return view('admin.dashboards.promoter.show-single-finance', compact('finance', 'promoter'));
    }

    public function editSingleFinance($id)
    {
        $promoter = Auth::user()->load('promoters');
        $finance = Finance::findOrFail($id)->load('user', 'serviceable');

        return view('admin.dashboards.promoter.edit-single-finance', compact('finance', 'promoter'));
    }

    public function updateSingleFinance(Request $request, $id)
    {
        try {
            $promoter = Auth::user()->load('promoters');
            $finance = Finance::findOrFail($id)->load('user', 'serviceable');

            // Validate the input
            $validator = Validator::make($request->all(), [
                'desired_profit' => 'nullable|numeric|regex:/^\d+(\.\d{1,2})?$/',
                'name' => 'required|string',
                'date_from' => 'required|date',
                'date_to' => 'required|date',
                'external_link' => 'nullable|url',
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

            // Update the record with validated data
            $finance->update($validator);

            // Redirect back to the view page or another location
            return redirect()
                ->route('finances.show', $finance->id)
                ->with('success', 'Finance record updated successfully!')
                ->with('promoter', $promoter);
        } catch (\Exception $e) {
            Log::error('Error updating budget:', ['message' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function exportSingleFinanceRecord($id)
    {
        $financeRecord = Finance::findOrFail($id);

        // Generate the PDF
        $pdf = PDF::loadView('pdf.finances-single', ['finance' => $financeRecord]);

        // Download the PDF file
        return $pdf->download('finance_record_' . $id . '.pdf');
    }
}
