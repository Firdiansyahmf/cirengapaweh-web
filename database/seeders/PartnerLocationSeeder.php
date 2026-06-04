<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PartnerLocationSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('partner_locations')->insert([
            [
                'id' => 8,
                'name' => 'Cibiru',
                'image' => 'assets/img/lokasi/AuQ59X54117dFWiBKqRZRgLXcVbsAKLOEU5WhLnL.png',
                'address' => 'UPI, 229, Gang Gegersuni 1, Gegerkalong, Sukajadi, Kota Bandung, Jawa Barat, Jawa, 40154, Indonesia',
                'operating_hours' => '09:00-10:00',
                'is_active' => false,
                'created_at' => '2026-05-19 22:00:35',
                'updated_at' => '2026-05-22 20:01:04',
            ],
            [
                'id' => 9,
                'name' => 'yuyuyu',
                'image' => 'assets/img/lokasi/ikp74ZsOHqDMd9JGOoA8NgyAPLr4LCMphuCuTopv.png',
                'address' => 'Parakansalak, Sukabumi, Jawa Barat, Jawa, 43358, Indonesia',
                'operating_hours' => '09:00-10:00',
                'is_active' => true,
                'created_at' => '2026-05-20 00:49:10',
                'updated_at' => '2026-05-23 17:48:43',
            ],
            [
                'id' => 12,
                'name' => 'Telkom',
                'image' => 'assets/img/lokasi/3CtqNgpPY2IVTda8OPrL68KO1AQPt0uAM3IRSwSD.png',
                'address' => 'Jalan Sunter Indah I, RW 12, Sunter Jaya, Tanjung Priok, Jakarta Utara, Daerah Khusus Ibukota Jakarta, Jawa, 14350, Indonesia',
                'operating_hours' => '09:00-10:00',
                'is_active' => true,
                'created_at' => '2026-05-23 18:02:43',
                'updated_at' => '2026-05-23 18:17:12',
            ],
            [
                'id' => 13,
                'name' => 'TELKOMM',
                'image' => 'assets/img/lokasi/GloFMCSfjB6ATeOdzmkhqQWCObEroW840HRhYeQA.png',
                'address' => 'Kota Bandung, Jawa Barat, Jawa, Indonesia',
                'operating_hours' => '09:00-10:00',
                'is_active' => true,
                'created_at' => '2026-05-23 18:17:57',
                'updated_at' => '2026-05-23 19:33:20',
            ],
            [
                'id' => 14,
                'name' => 'TELKOMM',
                'image' => 'assets/img/lokasi/mUBolhcpnSv2pT8T3iFRwLYLvvWhPguIa36UYVU7.png',
                'address' => 'UPI, 229, Jalan Dr. Setiabudi, Isola, Sukajadi, Kota Bandung, Jawa Barat, Jawa, 40154, Indonesia',
                'operating_hours' => '10:00-12:00',
                'is_active' => true,
                'created_at' => '2026-05-23 19:34:32',
                'updated_at' => '2026-05-23 20:51:33',
            ],
            [
                'id' => 16,
                'name' => 'TELKOM',
                'image' => 'assets/img/lokasi/WYUU2xin8ujXIlqZAgHIXBb6Ux0wvA9XoNDGnoOR.png',
                'address' => 'UPI, 229, Jalan Dr. Setiabudi, Isola, Sukajadi, Kota Bandung, Jawa Barat, Jawa, 40154, Indonesia',
                'operating_hours' => '10:00-11:00',
                'is_active' => true,
                'created_at' => '2026-05-23 21:29:20',
                'updated_at' => '2026-05-23 21:29:20',
            ],
            [
                'id' => 17,
                'name' => 'TELKOM',
                'image' => 'assets/img/lokasi/4subfSIbSsCyIxWZzbio8oMlqTxnu3yFOdp2enqZ.png',
                'address' => 'Pancor, Lombok Timur, Nusa Tenggara Barat, Nusa Tenggara, 83611, Indonesia',
                'operating_hours' => '10:00-11:00',
                'is_active' => true,
                'created_at' => '2026-05-23 21:30:06',
                'updated_at' => '2026-05-23 21:30:06',
            ],
            [
                'id' => 19,
                'name' => 'Parakansalak',
                'image' => 'assets/img/lokasi/p1o0AWowFq9QeKVkQ87wtBkeKljW4fxFAYDQ6jzG.png',
                'address' => 'Parakansalak, Sukabumi, Jawa Barat, Jawa, 43358, Indonesia',
                'operating_hours' => '09:00-10:00',
                'is_active' => true,
                'created_at' => '2026-05-28 16:01:15',
                'updated_at' => '2026-05-28 16:01:15',
            ],
        ]);
    }
}
