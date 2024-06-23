<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableQuotationRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_quotation_request', function (Blueprint $table) {
            $table->id('request_id');
            $table->text('notes');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('user_id')->on('table_users')->onDelete('cascade');
            // $table->unsignedBigInteger('product_id');
            // $table->foreign('product_id')->references('product_id')->on('table_product')->onDelete('cascade');
            $table->jsonb('design_element');
            $table->foreign('design_element')->references('design_element')->on('table_product_option')->onDelete('cascade');
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
        Schema::table('table_quotation_request', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign(['user_id']);
            $table->dropForeign(['design_element']);
            // $table->dropForeign(['product_id']);
            // If there were other proper foreign keys, they would be dropped here
        });
        Schema::dropIfExists('table_quotation_request');
    }
}
