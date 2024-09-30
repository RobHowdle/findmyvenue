<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_logged_in' => 'datetime'
    ];

    protected $dates = [
        'last_logged_in',
    ];

    public function services(): MorphToMany
    {
        return $this->morphedByMany(Serviceable::class, 'serviceable', 'service_user', 'user_id', 'serviceable_id')->whereNull('service_user.deleted_at');
    }

    public function promoters(): MorphToMany
    {
        return $this->morphedByMany(Promoter::class, 'serviceable', 'service_user', 'user_id', 'serviceable_id')->whereNull('service_user.deleted_at');
    }

    public function venues(): MorphToMany
    {
        return $this->morphedByMany(Venue::class, 'serviceable', 'service_user', 'user_id', 'serviceable_id')->whereNull('service_user.deleted_at');
    }

    public function otherService(): MorphToMany
    {
        return $this->morphedByMany(OtherService::class, 'serviceable', 'service_user', 'user_id', 'serviceable_id')->whereNull('service_user.deleted_at');
    }

    public function todos()
    {
        return $this->morphMany(Todo::class, 'serviceable');
    }
}
