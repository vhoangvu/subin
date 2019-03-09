<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_history', function (Blueprint $table) {
            $table->increments('id');
            
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            $table->unsignedInteger('order_id');
            $table->foreign('order_id')->references('id')->on('order')->onDelete('cascade');
            
            $table->unsignedInteger('product_id');
            $table->foreign('product_id')->references('id')->on('product')->onDelete('cascade');
            
            $table->integer('quantity');
            $table->integer('current_produce_price');
            $table->integer('current_sale_price');
            
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
        Schema::dropIfExists('order_history');
    }
}
