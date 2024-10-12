<?php

namespace App\Models;

use App\Models\Promoter;
use App\Models\VenueReview;
use App\Models\VenueExtraInfo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Venue extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'venues';

    protected $fillable = [
        'name',
        'logo_url',
        'location',
        'postal_town',
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
        'description',
        'additional_info'
    ];

    public function users(): MorphToMany
    {
        return $this->morphToMany(User::class, 'serviceable', 'service_user', 'serviceable_id', 'user_id');
    }

    public function extraInfo()
    {
        return $this->hasOne(VenueExtraInfo::class, 'venues_id');
    }

    public function promoters()
    {
        return $this->belongsToMany(Promoter::class, 'promoter_venue_pivot', 'promoters_id', 'venues_id');
    }

    public function review()
    {
        return $this->hasMany(VenueReview::class);
    }

    public function todos()
    {
        return $this->morphMany(Todo::class, 'serviceable');
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_venue');
    }
}
