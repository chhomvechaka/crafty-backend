<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableProductImageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_product_image', function (Blueprint $table) {
            // Create the id column as the primary key
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('product_id')->on('table_product')->onDelete('cascade');

            $table->string('image_path');
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
        Schema::table('table_product_image', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['product_id']);
        });
        // Now drop the table
        Schema::dropIfExists('table_product_image');    }
}
