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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
