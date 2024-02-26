<?php

namespace App\Models;

use App\Models\Venue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VenueExtraInfo extends Model
{
    use HasFactory;
        use SoftDeletes;

    protected $table = 'venues_extra_info';

    protected $fillable = [
        'venues_id',
        'text',
    ];

    public function venue()
    {
        return $this->belongTo(Venue::class);
    }
}