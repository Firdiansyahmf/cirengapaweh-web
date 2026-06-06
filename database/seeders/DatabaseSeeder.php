<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserSeeder::class);
        $this->call(ChatSeeder::class);
        // User::factory(10)->create();

        $this->call([
            ProductSeeder::class,
            PartnerLocationSeeder::class,
            OrderSeeder::class,
        ]);
    }
}
