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
            $table->bigInteger('classroom_id')->unsigned();
            $table->string('language');
            $table->integer('score');
            $table->string('send_start_work');
            $table->string('send_end_work');
            $table->string('subject_file_path');
            $table->string('subject_name');
            $table->string('work_name');
            $table->timestamps();

            $table->foreign('classroom_id')->references('id')->on('classrooms')->onDelete('cascade');
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
