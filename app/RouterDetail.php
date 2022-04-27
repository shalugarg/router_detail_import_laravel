<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


/**
 * class for Router detail db table
 */
class RouterDetail extends Model 
{
     /**
     * Defining the database table name
     */
    protected $table = 'router_details';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'sapid', 'hostname', 'loopback','macaddress',
    ];

}