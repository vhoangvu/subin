<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaterialInOutHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material_in_out_history', function (Blueprint $table) {
            $table->increments('id');
            
            $table->unsignedInteger('material_id');
            $table->foreign('material_id')->references('id')->on('material')->onDelete('cascade');
            
            $table->char('unit_code', 20);
            $table->float('quantity');
            
            $table->char('in_out_code', 3);
            
            $table->float('price')->nullable();
            
            $table->date('date');
            
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
        Schema::dropIfExists('material_in_out_history');
    }
}
