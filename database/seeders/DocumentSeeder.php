<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Document;
use App\Models\User;
use Faker\Factory as Faker;

class DocumentSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil user pertama (atau sesuaikan)
        $user = User::first();

        // Cek apakah ada user yang tersedia
        if (!$user) {
            $this->command->error('Tidak ada user ditemukan. Jalankan UserSeeder terlebih dahulu.');
            return;
        }


        // Buat dan langsung simpan dokumen
        for ($i = 1; $i <= 20; $i++) {
            $faker = Faker::create();
            $name = $faker->randomElement([
                'Panduan',
                'Laporan',
                'Permohonan',
                'Ringkasan',
                'Evaluasi',
                'Instruksi',
                'Berita Acara'
            ]) . ' ' . $faker->randomElement([
                'Sistem Keuangan',
                'Kepegawaian',
                'Aplikasi Internal',
                'Monitoring Kinerja',
                'Manajemen Proyek',
                'Keamanan Data',
                'Pengadaan Barang',
                'Operasional Harian'
            ]) . ' ' . $faker->year;

            Document::create([
                'name' => $name,
                'category' => fake()->randomElement(['Panduan', 'Laporan', 'Permohonan']),
                'content' => fake()->paragraphs(3, true),
                'file' => "dokumen-$i.pdf",
                'thumbnail' => "thumb-$i.jpg",
                'user_id' => $user->id,
            ]);
        }

        $this->command->info('Seeder dokumen selesai. Data langsung diindeks ke Typesense.');
    }
}
