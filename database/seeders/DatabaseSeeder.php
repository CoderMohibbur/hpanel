<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * This will:
     * - Seed Super Admin user via AdminSeeder
     * - Optionally create a demo user
     */
    public function run(): void
    {
        // ✅ Seed default admin user
        $this->call([
            AdminSeeder::class,
        ]);

    }
}
