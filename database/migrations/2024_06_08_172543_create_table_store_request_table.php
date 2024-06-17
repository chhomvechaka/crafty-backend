<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableStoreRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_store_request', function (Blueprint $table) {
            $table->boolean('isVerified')->default(false);
            $table->integer('store_id');
            $table->foreign('store_id')->references('store_id')->on('table_stores')->onDelete('cascade');
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
        Schema::table('table_store_request', function (Blueprint $table) {
            $table->dropForeign(['store_id']); // Drop the foreign key constraint first
        });
        Schema::dropIfExists('table_store_request'); // Then drop the table
            }
}
