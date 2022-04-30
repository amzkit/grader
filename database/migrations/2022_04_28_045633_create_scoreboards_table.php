<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateScoreboardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement(
            "
            CREATE VIEW scoreboards 
            AS 
            SELECT 
                submissions.created_at as date_send,
                submissions.score,
                submissions.schedule_id,
                submissions.problem_id,
                submissions.user_id,
                schedules.course_id
            FROM 
                submissions
                JOIN problems ON submissions.problem_id = problems.id
                JOIN schedules ON submissions.schedule_id = schedules.id
                JOIN users ON submissions.user_id = users.id
            ORDER BY submissions.created_at DESC
            "
        );
        //  users.name

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('scoreboards');
    }
}
