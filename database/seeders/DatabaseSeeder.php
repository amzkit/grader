<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call('Database\Seeder\UserTableSeeder');

        $this->command->info('User table seeded!');
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
