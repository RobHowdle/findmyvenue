<?php

namespace App\Models;

use App\Models\Promoter;
use App\Models\VenueExtraInfo;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Venue extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'venues';

    protected $fillable = [
        'name',
        'location',
        'longitude',
        'latitude',
        'capacity',
        'in_house_gear',
        'band_type',
        'genre',
        'contact_name',
        'contact_number',
        'contact_email',
        'contact_link',
    ];

    public function extraInfo()
    {
        return $this->hasOne(VenueExtraInfo::class, 'venues_id');
    }

    public function promoters()
    {
        return $this->belongsToMany(Promoter::class, 'promoter_venue_pivot', 'venues_id', 'promoters_id');
    }
}