<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('username')->nullable()->unique()->default(null);
            $table->string('password');
            $table->boolean('role_student')->default(false);
            $table->boolean('role_admin')->default(false);
            $table->boolean('role_teacher')->default(false);
            $table->boolean('role_ta')->default(false);
            $table->enum('role', ['admin', 'student', 'ta', 'teacher', 'guest'])->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
