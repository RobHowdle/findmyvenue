<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserModuleSetting extends Model
{
    use SoftDeletes;

    protected $table = 'user_module_settings';

    protected $fillable = [
        'user_id',
        'serviceable_id',
        'serviceable_type',
        'module_name',
        'is_enabled'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function serviceable()
    {
        return $this->morphTo();
    }
}