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
        'contact_number',
        'contact_email',
        'contact_link',
        'services'
    ];

    public function otherServiceList()
    {
        return $this->belongsTo(OtherServiceList::class, 'other_service_id');
    }
}
