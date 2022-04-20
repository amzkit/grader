<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Language;

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
    }
}
class UserTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('users')->delete();
        User::create([
            'name' => "Admin",
            'email' => "admin_grader@mju.ac.th",
            'username' => 1,
            'password' => Hash::make('12345678'),
            'role_admin' => 1,
            'role' => 'admin',
        ]);
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
        );
    }
}
