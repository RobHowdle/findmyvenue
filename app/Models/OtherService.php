<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OtherService extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'other_services';

    protected $fillable = [
        'name',
        'logo_url',
        'location',
        'postal_town',
        'longitude',
        'latitude',
        'other_service_id',
        'description',
        'packages',
        'environment_type',
        'working_times',
        'members',
        'stream_urls',
        'band_type',
        'genre',
        'contact_number',
        'contact_email',
        'contact_link',
        'portfolio_link',
        'services'
    ];

    /**
     * Polymorphic relation to the users.
     */
    public function users()
    {
        return $this->morphToMany(User::class, 'serviceable', 'service_user', 'serviceable_id', 'user_id');
    }

    /**
     * Retrieve all bands (other services with `other_service_id` as 4).
     */
    public static function bands()
    {
        return self::where('other_service_id', 4); // Assuming 4 refers to bands in your `other_services` table
    }

    /**
     * Belongs to OtherServiceList relation.
     */
    public function otherServiceList()
    {
        return $this->belongsTo(OtherServiceList::class, 'other_service_id');
    }

    /**
     * MorphMany relation to Todo items.
     */
    public function todos()
    {
        return $this->morphMany(Todo::class, 'serviceable');
    }

    /**
     * BelongsToMany relation to Events for band events.
     */
    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_band', 'band_id', 'event_id');
    }

    /**
     * Get the highest rated service of a specific type in a location.
     */
    public static function getHighestRatedService($serviceType, $location)
    {
        return self::with('otherServiceList') // Eager load related services
            ->whereHas('otherServiceList', function ($query) use ($serviceType) {
                $query->where('service_name', $serviceType);
            })
            ->where('postal_town', $location)
            ->get()
            ->map(function ($service) {
                $service->overall_score = OtherServicesReview::calculateOverallScore($service->id);
                return $service;
            })
            ->sortByDesc('overall_score')
            ->first();
    }

    /**
     * Retrieve all bands associated with this service.
     */
    public function getAllBands()
    {
        return self::bands()->get(); // Get all band services
    }
}
