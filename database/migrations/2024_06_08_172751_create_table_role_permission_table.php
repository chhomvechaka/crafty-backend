<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableRolePermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_role_permission', function (Blueprint $table) {
            $table->integer('role_id');
            $table->foreign('role_id')->references('role_id')->on('table_role')->onDelete('cascade');
            $table->integer('permission_id');
            $table->foreign('permission_id')->references('permission_id')->on('table_permission')->onDelete('cascade');
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
        Schema::table('table_role_permission', function (Blueprint $table) {
            // It's important to drop foreign keys before dropping the table
            $table->dropForeign(['role_id']);
            $table->dropForeign(['permission_id']);
        });
        Schema::dropIfExists('table_role_permission');
    }
}
