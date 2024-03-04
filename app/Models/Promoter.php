<?php

namespace App\Models;

use App\Models\Venue;
use App\Models\PromoterReview;
use App\Models\VenueExtraInfo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Promoter extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'promoters';

    protected $fillable = [
        'name',
        'location',
        'postal_town',
        'longitude',
        'latitude',
        'logo_url',
        'about_me',
        'my_venues',
        'contact_number',
        'contact_email',
        'contact_link',
    ];

    public function extraInfo()
    {
        return $this->hasOne(VenueExtraInfo::class, 'venues_id');
    }

    public function venues()
    {
    return $this->belongsToMany(Venue::class, 'promoter_venue_pivot', 'promoters_id', 'venues_id');
    }

    public function review()
    {
        return $this->hasMany(PromoterReview::class);
    }
}