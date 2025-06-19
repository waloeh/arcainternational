<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            RolesTableSeeder::class,
            UsersTableSeeder::class,
            CategoriesTableSeeder::class,
        ]);
    }
    
}

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('roles')->insert([
            ['role_id' => 1, 'name' => 'manager', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['role_id' => 2, 'name' => 'admin', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['role_id' => 3, 'name' => 'employee', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
    }
}

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            [
                'user_id' => 1,
                'username' => 'manager',
                'email' => 'mulyana14101990@gmail.com',
                'password' => Hash::make('password'),
                'role_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'user_id' => 2,
                'username' => 'admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role_id' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'user_id' => 3,
                'username' => 'employee',
                'email' => 'employee@example.com',
                'password' => Hash::make('password'),
                'role_id' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'user_id' => 4,
                'username' => 'employee2',
                'email' => 'employee2@example.com',
                'password' => Hash::make('password'),
                'role_id' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);
    }
}

class CategoriesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('categories')->insert([
            [
                'category_id' => 1,
                'name' => 'transportasi',
                'limit_per_month' => 1000000,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'category_id' => 2,
                'name' => 'kesehatan',
                'limit_per_month' => 1000000,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'category_id' => 3,
                'name' => 'makan',
                'limit_per_month' => 1000000,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ]);
    }
}


