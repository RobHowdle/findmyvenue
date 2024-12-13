<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class StandardUser extends Model
{
    protected $table = 'standard_users';

    protected $fillable = [
        'name',
        'location',
        'postal_town',
        'longitude',
        'latitude',
        'genre',
        'band_type',
    ];

    protected $casts = [
        'genre' => 'array',
    ];

    public function linkedUsers(): MorphToMany
    {
        return $this->morphToMany(User::class, 'serviceable', 'service_user', 'serviceable_id', 'user_id')
            ->withPivot('created_at', 'updated_at', 'role')
            ->whereNull('service_user.deleted_at');
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_promoter');
    }

    public function venues()
    {
        return $this->belongsToMany(Venue::class, 'promoter_venue_pivot', 'promoters_id', 'venues_id');
    }
}
