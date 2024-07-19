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
                'status' => 'Active',
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
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => 3,
                'first_name' => 'Aladdin',
                'last_name' => 'Buwanding',
                'gender' => 'Male',
                'date_of_birth' => '1920-01-15',
                'email' => 'aladdin@gmail.com',
                'user_name' => 'aladdin',
                'password' => Hash::make('aladdin'),
                'mobile_number' => '1234567890',
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => 3,
                'first_name' => 'Raphael',
                'last_name' => 'Alingig',
                'gender' => 'Male',
                'date_of_birth' => '1920-01-15',
                'email' => 'raphael@gmail.com',
                'user_name' => 'raphael',
                'password' => Hash::make('raphael'),
                'mobile_number' => '1234567890',
                'status' => 'Disabled',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => 3,
                'first_name' => 'Rider2',
                'last_name' => 'Yamaha',
                'gender' => 'Male',
                'date_of_birth' => '1920-01-15',
                'email' => 'rider2@gmail.com',
                'user_name' => 'rider2',
                'password' => Hash::make('rider2'),
                'mobile_number' => '1234567890',
                'status' => 'Disabled',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => 3,
                'first_name' => 'Rider3',
                'last_name' => 'Toyota',
                'gender' => 'Male',
                'date_of_birth' => '1920-01-15',
                'email' => 'rider3@gmail.com',
                'user_name' => 'rider3',
                'password' => Hash::make('rider3'),
                'mobile_number' => '1234567890',
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => 3,
                'first_name' => 'John',
                'last_name' => 'Ratunil',
                'gender' => 'Male',
                'date_of_birth' => '1920-01-15',
                'email' => 'john@gmail.com',
                'user_name' => 'john',
                'password' => Hash::make('john'),
                'mobile_number' => '1234567890',
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => 3,
                'first_name' => 'Ray',
                'last_name' => 'Ibarra',
                'gender' => 'Male',
                'date_of_birth' => '1920-01-15',
                'email' => 'ray@gmail.com',
                'user_name' => 'ray',
                'password' => Hash::make('ray'),
                'mobile_number' => '1234567890',
                'status' => 'Disabled',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => 4,
                'first_name' => 'Customer',
                'last_name' => 'Customer',
                'gender' => 'Male',
                'date_of_birth' => '1920-01-15',
                'email' => 'customer@gmail.com',
                'user_name' => 'customer',
                'password' => Hash::make('customer'),
                'mobile_number' => '1234567890',
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => 4,
                'first_name' => 'Thad',
                'last_name' => 'Huber',
                'gender' => 'Male',
                'date_of_birth' => '1920-01-15',
                'email' => 'thad@gmail.com',
                'user_name' => 'thadhuber',
                'password' => Hash::make('thad'),
                'mobile_number' => '1234567890',
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => 4,
                'first_name' => 'Bethany',
                'last_name' => 'Marquez',
                'gender' => 'Female',
                'date_of_birth' => '1920-01-15',
                'email' => 'bethany@gmail.com',
                'user_name' => 'bethanymarquez',
                'password' => Hash::make('bethany'),
                'mobile_number' => '1234567890',
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => 4,
                'first_name' => 'Erin',
                'last_name' => 'Flower',
                'gender' => 'Male',
                'date_of_birth' => '1920-01-15',
                'email' => 'Erin@gmail.com',
                'user_name' => 'erinflower',
                'password' => Hash::make('erin'),
                'mobile_number' => '1234567890',
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => 4,
                'first_name' => 'Gil',
                'last_name' => 'Vincent',
                'gender' => 'Male',
                'date_of_birth' => '1920-01-15',
                'email' => 'gil@gmail.com',
                'user_name' => 'gil',
                'password' => Hash::make('gil'),
                'mobile_number' => '1234567890',
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => 4,
                'first_name' => 'Jayne',
                'last_name' => 'Olvier',
                'gender' => 'Female',
                'date_of_birth' => '1920-01-15',
                'email' => 'jayne@gmail.com',
                'user_name' => 'jayne',
                'password' => Hash::make('jayne'),
                'mobile_number' => '1234567890',
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => 4,
                'first_name' => 'Sony',
                'last_name' => 'Ali',
                'gender' => 'Male',
                'date_of_birth' => '1920-01-15',
                'email' => 'sony@gmail.com',
                'user_name' => 'sony',
                'password' => Hash::make('sony'),
                'mobile_number' => '1234567890',
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => 4,
                'first_name' => 'Tracy',
                'last_name' => 'Moreno',
                'gender' => 'Female',
                'date_of_birth' => '1920-01-15',
                'email' => 'tracy@gmail.com',
                'user_name' => 'tracy',
                'password' => Hash::make('tracy'),
                'mobile_number' => '1234567890',
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => 4,
                'first_name' => 'Customer4',
                'last_name' => 'Customer4',
                'gender' => 'Male',
                'date_of_birth' => '1920-01-15',
                'email' => 'customer4@gmail.com',
                'user_name' => 'customer4',
                'password' => Hash::make('customer4'),
                'mobile_number' => '1234567890',
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ],


        ]);
    }
}
