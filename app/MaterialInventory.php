<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MaterialInventory extends Model
{
    //
    protected $table = "material_inventory";
    
    protected $fillable = [
    		'material_id',
    		'unit_code',
    		'quantity'
    ];
}
