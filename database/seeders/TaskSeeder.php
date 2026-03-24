<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = \App\Models\User::first();
        if (!$user) return;

        \App\Models\Task::create([
            'user_id' => $user->id,
            'title' => 'Client Strategy Session',
            'location' => 'Zoom Meeting',
            'category' => 'Meeting',
            'due_at' => now()->addHours(2),
        ]);

        \App\Models\Task::create([
            'user_id' => $user->id,
            'title' => 'Contract Review',
            'location' => 'Legal Team',
            'category' => 'Meeting',
            'due_at' => now()->addDays(1)->setHour(14)->setMinute(30),
        ]);
        
        \App\Models\Task::create([
            'user_id' => $user->id,
            'title' => 'Product Demo',
            'location' => 'Microsoft Teams',
            'category' => 'Meeting',
            'due_at' => now()->addDays(2)->setHour(11)->setMinute(0),
        ]);
    }
}
