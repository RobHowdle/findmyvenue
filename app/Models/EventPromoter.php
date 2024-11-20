<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventPromoter extends Model
{
    protected $table = 'event_promoter';

    protected $fillable = [
        'event_id',
        'promoter_id'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function promoter()
    {
        return $this->belongsTo(Promoter::class);
    }
}