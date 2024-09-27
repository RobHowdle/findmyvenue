<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Finance extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'finances';

    protected $fillable = [
        'user_id',
        'serviceable_id',
        'serviceable_type',
        'finance_type',
        'name',
        'date_from',
        'date_to',
        'external_link',
        'incoming',
        'other_incoming',
        'outgoing',
        'other_outgoing',
        'desired_profit',
        'total_incoming',
        'total_outgoing',
        'total_profit',
        'total_remaining_to_desired_profit',
    ];

    public function serviceable()
    {
        return $this->morphTo();
    }

    public static function getIncomingData($date)
    {
        return self::whereDate('date_from', $date)->sum('total_incoming');
    }

    public static function getOutgoingData($date)
    {
        return self::whereDate('date_from', $date)->sum('total_outgoing');
    }

    public static function getProfitData($date)
    {
        return self::whereDate('date_from', $date)->sum('total_profit');
    }

    public static function getIncomingDataForWeek($date)
    {
        return self::whereBetween('date_from', [Carbon::parse($date)->startOfWeek(), Carbon::parse($date)->endOfWeek()])->sum('total_incoming');
    }

    public static function getOutgoingDataForWeek($date)
    {
        return self::whereBetween('date_from', [Carbon::parse($date)->startOfWeek(), Carbon::parse($date)->endOfWeek()])->sum('total_outgoing');
    }

    public static function getProfitDateForWeek($date)
    {
        return self::whereBetween('date_from', [Carbon::parse($date)->startOfWeek(), Carbon::parse($date)->endOfWeek()])->sum('total_profit');
    }

    public static function getIncomingDataForMonth($date)
    {
        return self::whereMonth('date_from', Carbon::parse($date)->month)
            ->whereYear('date_from', Carbon::parse($date)->year)
            ->sum('total_incoming');
    }

    public static function getOutgoingDataForMonth($date)
    {
        return self::whereMonth('date_from', Carbon::parse($date)->month)
            ->whereYear('date_from', Carbon::parse($date)->year)
            ->sum('total_outgoing');
    }

    public static function getProftDataForMonth($date)
    {
        return self::whereMonth('date_from', Carbon::parse($date)->month)
            ->whereYear('date_from', Carbon::parse($date)->year)
            ->sum('total_profit');
    }

    public static function getIncomingDataForYear($date)
    {
        return self::whereYear('date_from', Carbon::parse($date)->year)->sum('total_incoming');
    }

    public static function getOutgoingDataForYear($date)
    {
        return self::whereYear('date_from', Carbon::parse($date)->year)->sum('total_outgoing');
    }

    public static function getProfitDataForYear($date)
    {
        return self::whereYear('date_from', Carbon::parse($date)->year)->sum('total_profit');
    }
}
