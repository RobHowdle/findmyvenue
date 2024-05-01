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
        'name',
    ];
}
