<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin user
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@crm.com',
            'password' => bcrypt('password123'),
            'role' => User::ROLE_ADMIN,
        ]);

        // Create Manager user
        User::factory()->create([
            'name' => 'Manager User',
            'email' => 'manager@crm.com',
            'password' => bcrypt('password123'),
            'role' => User::ROLE_MANAGER,
        ]);

        // Create Agent user
        User::factory()->create([
            'name' => 'Agent User',
            'email' => 'agent@crm.com',
            'password' => bcrypt('password123'),
            'role' => User::ROLE_AGENT,
        ]);
    }
}
