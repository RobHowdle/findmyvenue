<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventBand extends Model
{
    protected $table = 'event_band';

    protected $fillable = [
        'band_id',
        'promoter_id'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function band()
    {
        return $this->belongsTo(OtherService::class);
    }
}