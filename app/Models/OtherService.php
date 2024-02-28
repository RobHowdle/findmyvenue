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
        'location',
        'contact_number',
        'contact_email',
        'contact_link',
        'services'
    ];
}