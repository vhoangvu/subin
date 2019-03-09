<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaterialInventoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material_inventory', function (Blueprint $table) {
            $table->increments('id');
            
            $table->unsignedInteger('material_id');
            $table->foreign('material_id')->references('id')->on('material')->onDelete('cascade');
            
            $table->char('unit_code', 20);
            $table->float('quantity');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('material_inventory');
    }
}
