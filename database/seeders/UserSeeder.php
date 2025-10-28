<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Guardian;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run()
    {

        // Create Admin User
        $admin = Admin::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'phone_number' => '0927396406',
            'password' => 'password123',
        ]);
    

        // Create Guardian User
        $guardian = Guardian::create([
            'name' => 'Guardian User',
            'email' => 'guardian@example.com',
            'phone_number' => '0910000000',
        ]);

    }
}

