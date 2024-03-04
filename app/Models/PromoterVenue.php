<?php

namespace App\Models;

use App\Models\Venue;
use App\Models\Promoter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PromoterVenue extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'promoter_venue_pivot';

    protected $fillable = [
        'promoters_id',
        'venues_id'
    ];
}