<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    protected $table = 'product';
    
    protected $fillable = [
    		'product_group_code',
    		'name',
    		'produce_price',
    		'sale_price'
    ];
    
    protected $appends = array('clicked');
    
    public function getClickedAttribute(){
    	return 0;
    }
}
