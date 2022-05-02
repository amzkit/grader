<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Language;
use App\Models\Course;
use App\Models\Problem;
use App\Models\Schedule;
use App\Models\Classroom;
use App\Models\Testcase;

use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call('Database\Seeders\UserTableSeeder');
        $this->command->info('User table seeded!');

        $this->call('Database\Seeders\LanguageTableSeeder');
        $this->command->info('Language table seeded!');

        $this->call('Database\Seeders\CourseTableSeeder');
        $this->command->info('Course Guest table seeded!');

        $this->call('Database\Seeders\ClassroomTableSeeder');
        $this->command->info('Classroom table seeded!');

        $this->call('Database\Seeders\ProblemTableSeeder');
        $this->command->info('Problem table seeded!');

        $this->call('Database\Seeders\ScheduleTableSeeder');
        $this->command->info('Schedule table seeded!');

        $this->call('Database\Seeders\TestCaseTableSeeder');
        $this->command->info('TestCase table seeded!');
    }
}
class UserTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('users')->delete();

        $rows = [
            [
                'name' => "Admin",
                'email' => "admin@gmail.com",
                'username' => "admin",
                'password' => Hash::make('12345678'),
                'role_admin' => 1,
                'role' => 'admin',
            ],
            [
                'name' => "Teacher",
                'email' => "teacher@gmail.com",
                'username' => "teacher",
                'password' => Hash::make('12345678'),
                'role_teacher' => 1,
                'role' => 'teacher',
            ]
        ];

        foreach ($rows as $row) {
            User::create($row);
        }
    }
}

class LanguageTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('languages')->delete();

        $rows = [
            [
                'lang' => "PYTHON",
                'type' => ".py",
            ],
            [
                'lang' => "C#",
                'type' => ".cs",
            ],
            [
                'lang' => "C++",
                'type' => ".cpp",
            ],
        ];

        foreach ($rows as $row) {
            Language::create($row);
        }
    }
}

class CourseTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('courses')->delete();

        $rows = [
            [
                'course_name' => "Guest",
            ],
            [
                'course_name' => "TEST",

            ],
        ];

        foreach ($rows as $row) {
            Course::create($row);
        }
    }
}

class ClassroomTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('classrooms')->delete();

        $user = DB::table('users')->orderBy('id', 'desc')->get();

        $course = DB::table('courses')->orderBy('id', 'desc')->get();

        $rows = [
            [
                'user_id' => $user->where('username', '=', 'teacher')->first()->id,
                'course_id' => $course->first()->id,
                'section' => 1,
                "year" => 1,
                "teacher" => 1,
                "ta" => 0,
                "student" => 0,
                "guest" => 0,
                "semester" => 2564,
                "start_datetime" => null,
                "end_datetime" => null,
            ],

        ];

        foreach ($rows as $row) {
            Classroom::create($row);
        }
    }
}

class ProblemTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('problems')->delete();

        $rows = [
            [
                'title' => "TITLE",
                'question' => "QUESTION",
                'level' => 2.5,
                "file" => null,
                "tolerant" => "$",
                "IsActive" => 1,
            ],

        ];

        foreach ($rows as $row) {
            Problem::create($row);
        }
    }
}

class ScheduleTableSeeder extends Seeder
{

    public function run()
    {
        $now = Carbon::now();

        $course = DB::table('courses')->orderBy('id', 'desc')->get();

        $problem = DB::table('problems')->orderBy('id', 'desc')->get();

        $language = DB::table('languages')->get();

        DB::table('schedules')->delete();

        $rows = [
            [
                'course_id' => $course->first()->id,
                'start_date' => $now->toDateTimeString(),
                'end_date' => $now->addDays(30)->toDateTimeString(),
                "problem_id" => $problem->first()->id,
                "language_id" => $language->where('lang', '=', 'PYTHON')->first()->id,
                "IsActive" => 1,
                "IsAnalysis" => 1,
                "late" => 1,
                'score' => 20
            ],

        ];

        foreach ($rows as $row) {
            Schedule::create($row);
        }
    }
}

class TestCaseTableSeeder extends Seeder
{

    public function run()
    {

        $problem = DB::table('problems')->orderBy('id', 'desc')->get();

        DB::table('testcases')->delete();

        $rows = [
            [
                'input' => "5300",
                'output' => "5",
                "problem_id" => $problem->first()->id,
            ],
            [
                'input' => "15200",
                'output' => "22",
                "problem_id" => $problem->first()->id,
            ],
            [
                'input' => "61000",
                'output' => "244",
                "problem_id" => $problem->first()->id,
            ],
            [
                'input' => "80400",
                'output' => "400",
                "problem_id" => $problem->first()->id,
            ],
            [
                'input' => "93000",
                'output' => "511",
                "problem_id" => $problem->first()->id,
            ],
            [
                'input' => "160300",
                'output' => "1440",
                "problem_id" => $problem->first()->id,
            ],
            [
                'input' => "23145",
                'output' => "46",
                "problem_id" => $problem->first()->id,
            ],
            [
                'input' => "126789",
                'output' => "882",
                "problem_id" => $problem->first()->id,
            ],
            [
                'input' => "6503321",
                'output' => "2119978",
                "problem_id" => $problem->first()->id,
            ],
            [
                'input' => "564329",
                'output' => "16356",
                "problem_id" => $problem->first()->id,
            ],
            [
                'input' => "871125",
                'output' => "38759",
                "problem_id" => $problem->first()->id,
            ],
            [
                'input' => "91150",
                'output' => "500",
                "problem_id" => $problem->first()->id,
            ],
            [
                'input' => "784490",
                'output' => "31360",
                "problem_id" => $problem->first()->id,
            ],
            [
                'input' => "4508",
                'output' => "4",
                "problem_id" => $problem->first()->id,
            ],
            [
                'input' => "98665",
                'output' => "539",
                "problem_id" => $problem->first()->id,
            ],
            [
                'input' => "670013",
                'output' => "23115",
                "problem_id" => $problem->first()->id,
            ],
            [
                'input' => "88240",
                'output' => "440",
                "problem_id" => $problem->first()->id,
            ],
            [
                'input' => "61233",
                'output' => "244",
                "problem_id" => $problem->first()->id,
            ],
            [
                'input' => "446787",
                'output' => "10258",
                "problem_id" => $problem->first()->id,
            ],
            [
                'input' => "782457",
                'output' => "31280",
                "problem_id" => $problem->first()->id,
            ],
            [
                'input' => "7886",
                'output' => "7",
                "problem_id" => $problem->first()->id,
            ],
            [
                'input' => "68968",
                'output' => "272",
                "problem_id" => $problem->first()->id,
            ],
            [
                'input' => "788760",
                'output' => "31520",
                "problem_id" => $problem->first()->id,
            ],
            [
                'input' => "689689",
                'output' => "24115",
                "problem_id" => $problem->first()->id,
            ],
            [
                'input' => "67560",
                'output' => "268",
                "problem_id" => $problem->first()->id,
            ],
            [
                'input' => "5735",
                'output' => "5",
                "problem_id" => $problem->first()->id,
            ],
            [
                'input' => "57390",
                'output' => "199",
                "problem_id" => $problem->first()->id,
            ],
            [
                'input' => "33456",
                'output' => "82",
                "problem_id" => $problem->first()->id,
            ],
            [
                'input' => "5789235",
                'output' => "1678810",
                "problem_id" => $problem->first()->id,
            ],
            [
                'input' => "3459735",
                'output' => "600136",
                "problem_id" => $problem->first()->id,
            ],

        ];

        foreach ($rows as $row) {
            Testcase::create($row);
        }
    }
}
