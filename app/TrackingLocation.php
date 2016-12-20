<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrackingLocation extends Model
{
    protected $fillable = ['user_id', 'lat', 'lon', 'note','source','bearing'];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
