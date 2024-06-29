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
            $table->id('product_option_id');
            $table->boolean('is_requested')->default(false);
            $table->jsonb('design_element');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('product_id')->on('table_product')->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('user_id')->on('table_users')->onDelete('cascade');
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
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('table_product_option');
    }
}
