<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quizs', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->bigInteger('classroom_id')->unsigned()->nullable();
            $table->string('work_name');
            $table->string('subject_name');
            $table->string('subject_file_path');
            $table->integer('score');
            $table->bigInteger('language_id')->unsigned()->nullable();
            $table->string('send_start_work');
            $table->string('send_end_work');
            $table->timestamps();

            $table->foreign('classroom_id')->references('id')->on('manage_classrooms')->onDelete('cascade');
            $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quizs');
    }
}
