<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Finance;
use Carbon\Carbon;

class FinanceTestDataSeeder extends Seeder
{
    public function run()
    {
        // User-specific data
        $userId = 4;
        $serviceableId = 1;
        $serviceableType = 'App\Models\Promoter';
        $financeType = 'Budget';

        // Fields for income and outgoing (JSON format)
        $incomeFields = [
            'income_presale',
            'income_otd',
        ];

        $outgoingFields = [
            'outgoing_venue',
            'outgoing_band',
            'outgoing_promotion',
            'outgoing_rider',
        ];

        // Create records for each month
        for ($month = 1; $month <= 12; $month++) {
            // Randomly choose between 1 to 4 records for this month
            $recordsPerMonth = rand(1, 4);

            for ($i = 0; $i < $recordsPerMonth; $i++) {
                // Random day for the month
                $randomDay = rand(1, Carbon::now()->startOfMonth()->daysInMonth);
                $dateFrom = Carbon::create(null, $month, $randomDay);
                $dateTo = $dateFrom->copy()->addDay(); // Just one day after for date_to

                // Generate random values for incoming and outgoing
                $totalIncoming = rand(1000, 5000); // Random total incoming between 1000 and 5000
                $totalOutgoing = rand(500, 4000);  // Random total outgoing between 500 and 4000
                $desiredProfit = rand(500, 2000);  // Random desired profit between 500 and 2000

                // Calculate total profit and total remaining to desired profit
                $totalProfit = $totalIncoming - $totalOutgoing;
                $totalRemainingToDesiredProfit = max(0, $desiredProfit - $totalProfit);

                // JSON values for incoming and outgoing
                $incoming = json_encode(array_map(function ($field) {
                    return ['field' => $field, 'value' => rand(10, 500)];
                }, $incomeFields));

                $outgoing = json_encode(array_map(function ($field) {
                    return ['field' => $field, 'value' => rand(1, 500)];
                }, $outgoingFields));

                // Optional fields: other_incoming and other_outgoing (can be null or have values)
                $otherIncoming = rand(0, 1) ? json_encode([rand(100, 1000)]) : null;
                $otherOutgoing = rand(0, 1) ? json_encode([rand(100, 1000)]) : null;

                // Create the finance record
                Finance::create([
                    'user_id' => $userId,
                    'serviceable_id' => $serviceableId,
                    'serviceable_type' => $serviceableType,
                    'finance_type' => $financeType,
                    'name' => 'Finance Record for ' . $dateFrom->format('F Y'),
                    'date_from' => $dateFrom,
                    'date_to' => $dateTo,
                    'external_link' => 'https://example.com',
                    'incoming' => $incoming,
                    'other_incoming' => $otherIncoming,
                    'outgoing' => $outgoing,
                    'other_outgoing' => $otherOutgoing,
                    'desired_profit' => $desiredProfit,
                    'total_incoming' => $totalIncoming,
                    'total_outgoing' => $totalOutgoing,
                    'total_profit' => $totalProfit,
                    'total_remaining_to_desired_profit' => $totalRemainingToDesiredProfit,
                ]);
            }
        }
    }
}
