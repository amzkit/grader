<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManageStdClassroomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manage_std_classrooms', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('std_id')->unique()->nullable();
            $table->string('firstName');
            $table->string('lastName');
            $table->bigInteger('status_id')->unsigned()->nullable();
            $table->bigInteger('classroom_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('status_id')->references('id')->on('status')->onDelete('cascade');
            $table->foreign('classroom_id')->references('id')->on('manage_classrooms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manage_std_classrooms');
    }
}
