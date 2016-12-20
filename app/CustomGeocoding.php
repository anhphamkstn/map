<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomGeocoding extends Model
{
    protected $fillable = ['tag_name', 'country', 'state', 'city', 'area', 'postal_code', 'building_name', 'lat', 'lon'];
}