<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OtherServiceList extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "other_services_list";

    protected $fillable = [
        'service_name',
    ];

    public function otherServices()
    {
        return $this->hasMany(OtherService::class);
    }
}
