<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'events';

    protected $fillable = [
        'name',
        'location',
        'event_date',
        'event_start_time',
        'event_end_time',
        'facebook_event_url',
        'poster_url',
        'band_ids',
        'ticket_url',
        'on_the_door_ticket_price',
        'attendance',
        'ticket_sales',
        'ratings',
    ];

    protected $casts = [
        'event_date' => 'datetime',
    ];

    public function services()
    {
        return $this->belongsToMany(OtherService::class, 'event_band', 'event_id', 'band_id');
    }

    public function venues()
    {
        return $this->belongsToMany(Venue::class, 'event_venue');
    }

    public function promoters()
    {
        return $this->belongsToMany(Promoter::class, 'event_promoter');
    }

    public function bands()
    {
        return $this->services()
            ->where('other_service_id', 4);
    }

    public function getBandsAttribute($value)
    {
        return json_decode($value, true);
    }
}
