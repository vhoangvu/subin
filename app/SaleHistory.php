<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaleHistory extends Model
{
    //
    protected $table = 'sale_history';
    
    protected $fillable = [
    		'user_id',
    		'product_id',
    		'quantity',
    		'current_produce_price',
    		'current_sale_price',
    		'date',
    ];
    
    protected $dates = ['date'];
}
