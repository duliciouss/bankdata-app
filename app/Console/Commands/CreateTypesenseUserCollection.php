<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Typesense\Client;

class CreateTypesenseUserCollection extends Command
{
    protected $signature = 'typesense:create-users';
    protected $description = 'Membuat collection "users" di Typesense untuk Laravel Scout';

    public function handle()
    {
        $client = new Client([
            'api_key' => env('TYPESENSE_API_KEY'),
            'nodes' => [[
                'host' => env('TYPESENSE_HOST', 'localhost'),
                'port' => env('TYPESENSE_PORT', '8108'),
                'protocol' => env('TYPESENSE_PROTOCOL', 'http'),
            ]],
            'connection_timeout_seconds' => 2,
        ]);

        $schema = [
            'name' => 'users',
            'fields' => [
                ['name' => 'id', 'type' => 'int32'],
                ['name' => 'name', 'type' => 'string'],
                ['name' => 'email', 'type' => 'string'],
                ['name' => 'created_at', 'type' => 'int64'], // <- pastikan field ada
            ],
            'default_sorting_field' => 'created_at',
        ];

        try {
            // Cek jika sudah ada, hapus dulu (opsional)
            if ($client->collections['users']->retrieve()) {
                $this->warn('Collection "users" sudah ada. Menghapus & membuat ulang...');
                $client->collections['users']->delete();
            }
        } catch (\Exception $e) {
            $this->info('Collection belum ada. Membuat baru...');
        }

        try {
            $client->collections->create($schema);
            $this->info('✅ Collection "users" berhasil dibuat!');
        } catch (\Exception $e) {
            $this->error('❌ Gagal membuat collection: ' . $e->getMessage());
        }
    }
}
