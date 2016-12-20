<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword;

class User extends Authenticatable implements CanResetPassword
{
    use \Illuminate\Auth\Passwords\CanResetPassword;

    protected $table = 'users';

    const ACTIVE = 1;
    const INACTIVE = 0;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */


    protected $fillable = [
	    'avatar_id', 'name', 'email', 'password', 'role_id', 'active', 'phone_1', 'phone_2'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    public function role()
    {
        return $this->belongsTo('App\Role');
    }

    public function scopeActive($query)
    {
        return $query->where('active', self::ACTIVE);
    }

    public function scopeInactive($query)
    {
        return $query->where('active', self::INACTIVE);
    }

    public function trackingLocations()
    {
        return $this->hasMany('App\TrackingLocation', 'user_id');
    }
}
