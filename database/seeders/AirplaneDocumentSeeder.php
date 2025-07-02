<?php

namespace Database\Seeders;

use App\Models\Document;
use App\Models\User;
use Illuminate\Database\Seeder;

class AirplaneDocumentSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        if (!$user) {
            $this->command->error('User tidak ditemukan. Pastikan setidaknya ada satu user.');
            return;
        }

        $airplaneDocuments = [
            [
                'name' => 'Su-30MK2 Manual: Book 1 - General Information',
                'category' => 'Panduan Teknis',
                'content' => 'PDF_CONTENT_BOOK_1_GOES_HERE',
                'file' => 'Book 1.pdf',
                'thumbnail' => 'thumb-su30mk2-book1.jpg',
            ],
            [
                'name' => 'Su-30MK2 Manual: Book 2-1 - Airframe and Systems',
                'category' => 'Panduan Teknis',
                'content' => 'PDF_CONTENT_BOOK_2_1_GOES_HERE',
                'file' => 'Book 2-1.pdf',
                'thumbnail' => 'thumb-su30mk2-book2-1.jpg',
            ],
            [
                'name' => 'Su-30MK2 Manual: Book 2-2 - Powerplant',
                'category' => 'Panduan Teknis',
                'content' => 'PDF_CONTENT_BOOK_2_2_GOES_HERE',
                'file' => 'Book 2-2.pdf',
                'thumbnail' => 'thumb-su30mk2-book2-2.jpg',
            ],
            [
                'name' => 'Su-30MK2 Manual: Book 2-3 - Avionics',
                'category' => 'Panduan Teknis',
                'content' => 'PDF_CONTENT_BOOK_2_3_GOES_HERE',
                'file' => 'Book 2-3_unlock.pdf',
                'thumbnail' => 'thumb-su30mk2-book2-3.jpg',
            ],
            [
                'name' => 'Su-30MK2 Manual: Book 3-1 - Armament',
                'category' => 'Panduan Teknis',
                'content' => 'PDF_CONTENT_BOOK_3_1_GOES_HERE',
                'file' => 'Book 3-1_unlock.pdf',
                'thumbnail' => 'thumb-su30mk2-book3-1.jpg',
            ],
        ];

        foreach ($airplaneDocuments as $item) {
            Document::create([
                'name' => $item['name'],
                'category' => $item['category'],
                'content' => $item['content'],
                'file' => $item['file'],
                'thumbnail' => $item['thumbnail'],
                'user_id' => $user->id,
            ]);
        }

        $this->command->info('Seeder dokumen pesawat Su-30MK2 berhasil dijalankan.');
    }
}
