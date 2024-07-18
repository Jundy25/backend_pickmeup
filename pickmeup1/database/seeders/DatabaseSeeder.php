<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            [
                'role_name' => 'SuperAdmin',
                'role_description' => 'Super Administrator role',
                'permission_type' => 'full_in_app_access',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_name' => 'Admin',
                'role_description' => 'Administrator role',
                'permission_type' => 'full_in_app_access',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_name' => 'Rider',
                'role_description' => 'Rider role',
                'permission_type' => 'limited_rider_access',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_name' => 'Customer',
                'role_description' => 'Customer role',
                'permission_type' => 'limited_access',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        

        // Added separate file for users
        DB::table('users')->insert([
            [
                'role_id' => 1,
                'first_name' => 'Superadmin',
                'last_name' => 'Superadmin',
                'gender' => 'Male',
                'date_of_birth' => '2001-01-01',
                'email' => 'superadmin@gmail.com',
                'user_name' => 'superadmin',
                'password' => Hash::make('superadmin'),
                'mobile_number' => '09123456789',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => 2,
                'first_name' => 'Admin',
                'last_name' => 'Admin',
                'gender' => 'Male',
                'date_of_birth' => '2003-03-03', 
                'email' => 'admin@gmail.com',
                'user_name' => 'admin',
                'password' => Hash::make('admin'),
                'mobile_number' => '09123456789',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => 3,
                'first_name' => 'Rider',
                'last_name' => 'Rider',
                'gender' => 'Male',
                'date_of_birth' => '1920-01-15',
                'email' => 'rider@gmail.com',
                'user_name' => 'rider',
                'password' => Hash::make('rider'),
                'mobile_number' => '1234567890',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
