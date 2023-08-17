<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Roles;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        
        User::create([
            'email' => 'admin@gmail.com',
            'name' => 'Admin',
            'password' => bcrypt('password123'),
            'role_id' => 1
        ]);

        Roles::create([
            'display_name' => 'Administrator',
            'description' => 'Super Admin',
        ]);
    }
}
