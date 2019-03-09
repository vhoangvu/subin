<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MaterialInOutHistory extends Model
{
    //
    protected $table = "material_in_out_history";
    
    protected $fillable = [
    		'material_id',
    		'unit_code',
    		'quantity',
    		'in_out_code',
    		'price',
    		'date',
    ];
    
    protected $dates = ['date'];
}
