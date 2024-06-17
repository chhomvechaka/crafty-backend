<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCartItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_cart_item', function (Blueprint $table) {
            $table->id('cart_item_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('cart_id');
            $table->timestamps();
            //set foreign key
            $table->foreign('cart_id')->references('cart_id')->on('table_cart')->onDelete('cascade');
            $table->foreign('product_id')->references('product_id')->on('table_product')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('table_cart_item', function (Blueprint $table) {
            // Drop the foreign key constraints first
            $table->dropForeign(['cart_id']);
            $table->dropForeign(['product_id']);
        });
        Schema::dropIfExists('table_cart_item');
    }
}
