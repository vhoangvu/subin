<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderObject extends Model
{
    //
    protected $table = 'order_object';
    
    protected $fillable = [
    		'order_object_group_code',
    		'name',
    ];
}
