<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * Akun admin TIDAK dibuat di sini (hindari password default).
     * Buat dengan: php artisan app:create-admin
     */
    public function run(): void
    {
        $this->call(PortfolioSeeder::class);
    }
}
