<?php

namespace Database\Seeders;

use App\Models\Authorization;
use App\Models\Event;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Authorization::factory()
            ->count(3)
            ->sequence(
                [
                    'name' => 'Amazon',
                    'email' => 'amazon@example.com',
                    'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                ],
                [
                    'name' => 'Sony',
                    'email' => 'sony@example.com',
                    'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                ],
                [
                    'name' => 'Netflix',
                    'email' => 'netflix@example.com',
                    'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                ],
            )
            ->has(Event::factory()->count(3))
            ->create();
    }
}
