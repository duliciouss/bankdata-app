<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FileExtractService
{
    /**
     * Kirim file ke API ekstraksi.
     *
     * @param string $filePath Full path ke file
     * @param string $filename Nama file (asli)
     * @return Response
     */
    public function sendToExtractor(string $filePath, string $filename): Response
    {
        try {
            return Http::attach(
                'file',
                file_get_contents($filePath),
                $filename
            )->withHeaders([
                'X-API-Key' => config('extract.api_key'),
            ])->post(config('extract.endpoint'));
        } catch (\Throwable $e) {
            Log::error('Gagal mengirim file ke ekstraktor: ' . $e->getMessage(), [
                'file' => $filePath,
                'exception' => $e,
            ]);
            throw $e;
        }
    }
}
