<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'id' => 4,
                'name' => 'Cireng Original',
                'description' => 'Hahaha',
                'image' => 'assets/img/produk/jNaczpFQ1NC19BTETEw33FuKJJPcaN8j4u4pp7ej.png',
                'category' => 'fast_food',
                'price' => 12000,
                'is_active' => true,
                'created_at' => '2026-05-19 21:44:07',
                'updated_at' => '2026-05-25 05:06:51',
            ],
            [
                'id' => 5,
                'name' => 'Cireng Kuah sedang',
                'description' => 'aaaa',
                'image' => 'assets/img/produk/EF52lfFc7lzJpBpRWpupVUmnIMP5MwcTt9G4OF52.png',
                'category' => 'frozen_food',
                'price' => 12000,
                'is_active' => true,
                'created_at' => '2026-05-19 21:44:51',
                'updated_at' => '2026-05-25 05:06:58',
            ],
            [
                'id' => 13,
                'name' => 'Cireng kuah salju',
                'description' => 'hehehehe',
                'image' => 'assets/img/produk/lKk83NDDKxFzENNLh0Wp0hYpVaqypqAQCC43FEPh.png',
                'category' => 'fast_food',
                'price' => 12000,
                'is_active' => true,
                'created_at' => '2026-05-20 00:46:06',
                'updated_at' => '2026-05-25 05:07:05',
            ],
            [
                'id' => 14,
                'name' => 'Cireng Kuah pedas',
                'description' => 'hshs',
                'image' => 'assets/img/produk/RYBxH979X37X1LcBLQiSBwwq94OV31Rdj7GYHSAk.png',
                'category' => 'frozen_food',
                'price' => 10000,
                'is_active' => true,
                'created_at' => '2026-05-22 06:37:10',
                'updated_at' => '2026-05-22 06:37:10',
            ],
            [
                'id' => 31,
                'name' => 'Cireng ilyass',
                'description' => 'Enak poll',
                'image' => 'assets/img/produk/5d94YeKiBPD7zI7OPqMbYN7YwIpRJ7pM4FRuSMJg.png',
                'category' => 'fast_food',
                'price' => 12000,
                'is_active' => true,
                'created_at' => '2026-05-28 16:00:10',
                'updated_at' => '2026-05-28 16:00:10',
            ],
        ]);
    }
}
