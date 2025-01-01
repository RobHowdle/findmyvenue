<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventVenue extends Model
{
    protected $table = 'event_promoter';

    protected $fillable = [
        'venue_id',
        'promoter_id'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }
}