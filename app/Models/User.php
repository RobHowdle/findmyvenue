<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Role;
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


    public function services()
    {
        // Get all associated promoters
        $promoters = $this->morphedByMany(Promoter::class, 'serviceable', 'service_user', 'user_id', 'serviceable_id')
            ->select('promoters.*', 'service_user.user_id as pivot_user_id', 'service_user.serviceable_id as pivot_serviceable_id', 'service_user.serviceable_type as pivot_serviceable_type')
            ->get();

        // Get all associated venues
        $venues = $this->morphedByMany(Venue::class, 'serviceable', 'service_user', 'user_id', 'serviceable_id')
            ->select('venues.*', 'service_user.user_id as pivot_user_id', 'service_user.serviceable_id as pivot_serviceable_id', 'service_user.serviceable_type as pivot_serviceable_type')
            ->get();

        // Combine both collections
        return $promoters->merge($venues);
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

    public function getRoleType()
    {
        $excludedRoles = ['promoter', 'venue', 'standard', 'administrator'];

        if ($this->hasRole('promoter')) {
            return Promoter::class;
        }

        if ($this->hasRole('venue')) {
            return Venue::class;
        }

        $userRoles = $this->getRoleNames()->toArray();
        $filteredRoles = array_diff($userRoles, $excludedRoles);

        if (!empty($filteredRoles)) {
            return OtherService::class;
        }

        return null;
    }
}
