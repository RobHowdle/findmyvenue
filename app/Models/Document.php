<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'user_id',
        'serviceable_id',
        'serviceable_type',
        'service',
        'title',
        'file_path',
        'category',
        'description',
    ];

    public function serviceable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
