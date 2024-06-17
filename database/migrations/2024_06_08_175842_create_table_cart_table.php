<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCartTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_cart', function (Blueprint $table) {
            $table->id('cart_id');
            $table->unsignedBigInteger('request_id');  // You need to add this column
            $table->timestamps(); // Adds 'created_at' and 'updated_at' columns

            $table->foreign('request_id')
                ->references('request_id')
                ->on('table_quotation_request')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('table_cart', function (Blueprint $table) {
            // Drop the foreign key constraints first
            $table->dropForeign(['request_id']);
        });
        Schema::dropIfExists('table_cart');
    }
}
