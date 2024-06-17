<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_users', function (Blueprint $table) {
            $table->id('user_id'); // Auto-incrementing primary key
            $table->string('firebase_uid', 36)->unique(); // Firebase UID, unique and up to 36 characters long
            $table->unsignedBigInteger('role_id'); // Foreign key
            $table->string('firstname', 255); // First name
            $table->string('lastname', 255); // Last name
            $table->string('email', 255)->unique(); // Email, unique
            $table->string('password', 60)->nullable(); // Add this line to your migration
            $table->string('phone_number', 20)->nullable(); // Phone number, optional
            $table->text('address')->nullable(); // Address, optional
            $table->timestamps(); // Adds 'created_at' and 'updated_at' columns
            $table->foreign('role_id')->references('role_id')->on('table_role')->onDelete('cascade'); // Foreign key constraint
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('table_users', function (Blueprint $table) {
            $table->dropForeign(['role_id']); // Drops the foreign key constraint
        });
        Schema::dropIfExists('table_users'); // Drops the table
    }
}
