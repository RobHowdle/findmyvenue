<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserService extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'users_service';

    protected $fillable = [
        'user_id',
        'venues_id',
        'promoters_id',
        'other_service_id',
    ];
}
