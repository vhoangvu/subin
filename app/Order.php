<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    protected $table = 'order';
    
    protected $fillable = [
    		'user_id',
    		'order_object_id',
    		'date',
    		'status',
    ];
    
    public function order_object() {
    	return $this->belongsTo('App\OrderObject', 'order_object_id', 'id');
    }
    
    public function order_histories() {
    	return $this->hasMany('App\OrderHistory', 'order_id', 'id');
    }
}
