<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('problem_id')->unsigned();
            $table->bigInteger('schedule_id')->unsigned();
            $table->bigInteger('user_id');
            $table->longText('code');
            $table->longText('output');
            $table->boolean('graded')->default(false);
            $table->float('score');
            $table->string('message', 100);
            $table->string('IP', 50);
            $table->string('Lang', 50);
            $table->string('fname', 240); // File Name
            $table->longText('compiler_message');

            $table->foreign('schedule_id')->references('id')->on('schedules')->onDelete('cascade');
            $table->foreign('problem_id')->references('id')->on('problems')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('submissions');
    }
}
