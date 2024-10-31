<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceUser extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'service_user';

    protected $fillable = [
        'user_id',
        'serviceable_id',
        'servieable_type'
    ];

    public $timestamps = true;
}