<?php

namespace Database\Seeders;

use App\Models\ChatSession;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Database\Seeder;

class ChatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get admin user
        $admin = User::where('role', 'superadmin')->first();

        // Create sample chat sessions
        for ($i = 1; $i <= 5; $i++) {
            $session = ChatSession::create([
                'customer_name' => "Pelanggan $i",
                'customer_phone' => '08' . str_pad($i, 9, '0', STR_PAD_LEFT),
                'status' => $i % 2 === 0 ? 'open' : 'closed',
            ]);

            // Add sample messages for first 3 sessions
            if ($i <= 3) {
                ChatMessage::create([
                    'session_id' => $session->id,
                    'user_id' => null,
                    'sender_type' => 'customer',
                    'message' => 'Halo, saya mau tanya tentang pesanan #ORD-992'
                ]);

                ChatMessage::create([
                    'session_id' => $session->id,
                    'user_id' => $admin->id,
                    'sender_type' => 'admin',
                    'message' => 'Halo, selamat pagi! Apa yang bisa kami bantu?'
                ]);

                ChatMessage::create([
                    'session_id' => $session->id,
                    'user_id' => null,
                    'sender_type' => 'customer',
                    'message' => 'Saya mau refund pesanan yang kemarin'
                ]);

                ChatMessage::create([
                    'session_id' => $session->id,
                    'user_id' => $admin->id,
                    'sender_type' => 'admin',
                    'message' => 'Baik Pak Andi, kami sedang melakukan pengecekan di pihak kami. Mohon tunggu!'
                ]);
            }
        }
    }
}
