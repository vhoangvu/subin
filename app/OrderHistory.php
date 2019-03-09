<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderHistory extends Model
{
    //
    protected $table = 'order_history';
    
    protected $fillable = [
    		'user_id',
    		'order_id',
    		'product_id',
    		'quantity',
    		'current_produce_price',
    		'current_sale_price',
    		'date',
    ];
}
