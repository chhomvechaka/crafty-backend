<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_product', function (Blueprint $table) {
            // Create the product_id column as the primary key
            $table->id('product_id');
            // Create other columns
            $table->string('product_name');
            $table->text('product_description');
            $table->integer('base_price');
            $table->integer('stock');
            $table->unsignedBigInteger('tag_id');
            $table->foreign('tag_id')->references('tag_id')->on('table_tag')->onDelete('cascade');
            $table->unsignedBigInteger('store_id');
            $table->foreign('store_id')->references('store_id')->on('table_stores')->onDelete('cascade');
            // Create the timestamps for created_at and updated_at
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
        Schema::table('table_product', function (Blueprint $table) {
            // Drop the foreign key constraints
            $table->dropForeign(['tag_id']);
            $table->dropForeign(['store_id']);
        });
        // Now drop the table
        Schema::dropIfExists('table_product');
    }
}
