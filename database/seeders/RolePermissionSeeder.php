<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::create([
            'name' => 'admin',
        ]);

        $stafRole = Role::create([
            'name' => 'staff',
        ]);

        $userRole = Role::create([
            'name' => 'user',
        ]);

        $userOwner = User::create([
            'name' => 'Rusli Zainal',
            'email' => 'zainal@gmail.com',
            'phone' => '082260549968',
            'otp' => '735133',
            'birth' => '1957-12-03',
            'access' => 'yes',
            'password' => bcrypt('00000000')
        ]);
        $userOwner->assignRole($adminRole);

        $userStaff = User::create([
            'name' => 'Setya Novanto',
            'email' => 'Setya@gmail.com',
            'phone' => '085183200745',
            'otp' => '123456',
            'access' => 'yes',
            'birth' => '1955-10-12',
            'password' => bcrypt('12345678')
        ]);
        $userStaff->assignRole($stafRole);

        $userUser = User::create([
            'name' => 'Irman Gusman',
            'email' => 'Irman@gmail.com',
            'phone' => '08987964511',
            'otp' => '654321',
            'access' => 'yes',
            'birth' => '1962-02-11',
            'password' => bcrypt('87654321')
        ]);
        $userUser->assignRole($userRole);
    }
}
