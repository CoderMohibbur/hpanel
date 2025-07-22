<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if admin already exists to prevent duplicate seeding
        if (!User::where('email', operator: 'admin@example.com')->exists()) {
            User::create([
                'name' => 'Super Admin',
                'email' => 'mohibbur1994@gmail.com',
                'password' => Hash::make('aq12wsAQ!@WS'), // ❗ Change this later
                'is_admin' => true,
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
        }
    }
}
