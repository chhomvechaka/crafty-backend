<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_stores', function (Blueprint $table) {
            $table->id('store_id');
            $table->string('store_name');
            $table->string('store_description');
            $table->string('store_contact');
            $table->string('store_address');
            $table->string('store_logo_path');
            $table->integer('user_id');
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
        Schema::table('table_stores', function (Blueprint $table) {
            $table->dropForeign(['user_id']); // Drops the foreign key constraint on user_id
        });
        Schema::dropIfExists('table_stores'); // Then drops the table_stores table
    }
}
