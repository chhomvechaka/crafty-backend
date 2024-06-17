<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableProductOptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_product_option', function (Blueprint $table) {
            $table->jsonb('design_element')->unique();
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('product_id')->on('table_product')->onDelete('cascade');
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
        Schema::table('table_product_option', function (Blueprint $table) {
            // Drop the foreign key constraints first
            $table->dropForeign(['product_id']);
        });
        Schema::dropIfExists('table_product_option');    }
}
