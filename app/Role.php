<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{

    protected $fillable = ['name', 'display_name', 'description'];

    const ROLE_SYS_ADMIN = 0;
    const ROLE_ADMIN = 1;
    const ROLE_SALES_MANAGER = 2;
    const ROLE_SALESPERSON = 3;

    public function users()
    {
        return $this->hasMany('App\User');
    }
}
