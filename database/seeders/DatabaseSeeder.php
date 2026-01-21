<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Create a default user
        $user = User::factory()->create([
            'name' => 'Developher',
            'email' => 'dev@email.com',
            'password' => bcrypt('password'),
        ]);

        // Create an API token for the user
        $token = $user->createToken('admin-api-token')->plainTextToken;
        $this->command->info("API Token: $token");
    }
}
