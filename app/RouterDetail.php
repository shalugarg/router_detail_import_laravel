<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RouterDetail extends Model 
{

    protected $table = 'router_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'Sapid', 'hostname', 'loopback','macaddress',
    ];


}
