<?php

namespace App\Models;

use App\Models\VenueExtraInfo;
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
        'capacity',
        'in_house_gear',
        'band_type',
        'genre',
        'contact_name',
        'contact_number',
        'contact_email'
    ];

    public function extraInfo()
    {
        return $this->hasOne(VenueExtraInfo::class, 'venues_id');
    }
}