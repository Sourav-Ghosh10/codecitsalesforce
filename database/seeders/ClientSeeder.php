<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = \App\Models\User::where('email', 'admin@crm.com')->first();

        if ($admin) {
            \App\Models\Client::create([
                'customer_number' => 'CUST-001',
                'full_name' => 'John Doe',
                'phone' => '+1 555 0101',
                'email' => 'john@techcorp.com',
                'company_name' => 'TechCorp Solutions',
                'status' => 'New',
                'agent_id' => $admin->id,
                'created_by' => $admin->id,
            ]);

            \App\Models\Client::create([
                'customer_number' => 'CUST-002',
                'full_name' => 'Alice Smith',
                'phone' => '+1 555 0202',
                'email' => 'alice@designhub.com',
                'company_name' => 'Design Hub',
                'status' => 'Follow-up',
                'agent_id' => $admin->id,
                'created_by' => $admin->id,
            ]);
        }
    }
}
