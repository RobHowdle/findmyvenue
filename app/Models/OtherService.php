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
        'packages',
        'environment_type',
        'working_times',
        'band_members',
        'stream_urls',
        'contact_number',
        'contact_email',
        'contact_link',
        'portfolio_link',
        'services'
    ];

    public function users()
    {
        return $this->morphToMany(User::class, 'serviceable');
    }

    public static function getHighestRatedService($serviceType, $location)
    {
        return self::with('otherServiceList') // Eager load the related model
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

    public function otherServiceList()
    {
        return $this->belongsTo(OtherServiceList::class, 'other_service_id');
    }

    public function todos()
    {
        return $this->morphMany(Todo::class, 'serviceable');
    }
}
