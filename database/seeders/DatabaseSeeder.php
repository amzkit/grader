<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Language;
use App\Models\Course;


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
    }
}
class UserTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('users')->delete();
        User::create(
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
            ],
        );
    }
}

class LanguageTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('languages')->delete();
        Language::create(
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
        );
    }
}

class CourseTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('courses')->delete();
        Course::create(
            [
                'course_name' => "Guest",
            ],
        );
    }
}
