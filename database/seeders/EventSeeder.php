<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('events')->insert ([
            [
                'title' => 'Konser The Weeknd',
                'category' => 'Musik',
                'ticket_price' => 700000,
                'description' => 'Konser musik The Weeknd yang akan diadakan di Jakarta pada bulan Desember 2026. Nikmati penampilan spektakuler dari penyanyi terkenal ini.',
                'image' => null,
            ],
            [
                'title' => 'Festival Coachella',
                'category' => 'Festival',
                'ticket_price' => 1000000,
                'description' => 'Festival musik terbesar di dunia yang akan diadakan di Los Angeles pada bulan April 2026. Nikmati penampilan spektakuler dari berbagai artis terkenal.',
                'image' => null,
            ],
            [
                'title' => 'Seminar Elon Musk SpaceX Future',
                'category' => 'Seminar',
                'ticket_price' => 200000,
                'description' => 'Seminar tentang masa depan eksplorasi luar angkasa yang akan diadakan di San Francisco pada bulan Juni 2026. Dapatkan wawasan langsung dari Elon Musk dan tim SpaceX.',
                'image' => null,
            ],
            [
                'title' => 'Konser Coldplay',
                'category' => 'Musik',
                'ticket_price' => 800000,
                'description' => 'Konser musik Coldplay yang akan diadakan di Jakarta pada bulan November 2026. Nikmati penampilan spektakuler dari band terkenal ini.',
                'image' => null,
            ],
            [
                'title' => 'Festival Glastonbury',
                'category' => 'Festival',
                'ticket_price' => 1200000,
                'description' => 'Festival musik terbesar di Inggris yang akan diadakan di Glastonbury pada bulan Juni 2026. Nikmati penampilan spektakuler dari berbagai artis terkenal.',
                'image' => null,
            ]
        ]);
    }
}
