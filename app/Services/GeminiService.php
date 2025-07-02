<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class GeminiService
{
    protected string $apiKey;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('gemini.key');
        $this->baseUrl = config('gemini.url');

        if (empty($this->apiKey)) {
            throw new Exception('GEMINI_API_KEY is not configured.');
        }
    }

    /**
     * Ekstrak teks yang bersih untuk FTS dari teks dokumen mentah.
     */
    public function extractTextForFTS(string $inputText): string
    {
        $prompt = $this->buildPrompt($inputText);

        try {
            $response = Http::post("{$this->baseUrl}?key={$this->apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ]
            ]);

            if ($response->failed()) {
                throw new Exception("Gemini API failed with status {$response->status()}: " . $response->body());
            }

            $data = $response->json();

            return $data['candidates'][0]['content']['parts'][0]['text']
                ?? throw new Exception('Unexpected Gemini response format.');
        } catch (Exception $e) {
            Log::error('[GeminiService] Gagal ekstraksi dengan Gemini: ' . $e->getMessage(), [
                'input_length' => strlen($inputText),
            ]);
            throw new Exception('Gagal memproses teks dengan AI.');
        }
    }

    protected function buildPrompt(string $text): string
    {
        return <<<PROMPT
Tugas kamu adalah mengekstrak konten inti dari teks dokumen berikut agar dapat digunakan untuk pencarian teks lengkap (Full-Text Search / FTS).

📌 Instruksi:
- Hilangkan bagian yang tidak relevan seperti header, footer, nomor halaman, atau watermark.
- Ekstrak hanya konten yang bermakna seperti:
    - Judul dokumen
    - Sub-judul atau bab (jika ada)
    - Tanggal (jika disebutkan)
    - Penulis, instansi, atau penerbit (jika ada)
    - Semua isi atau deskripsi dokumen
- Jangan sertakan format atau markup apapun.
- Output hanya teks murni, rapi, dan mudah dicari.
- Gunakan gaya naratif apa adanya dari dokumen (tidak perlu ringkasan).

🔽 Berikut teks dokumennya:

$text
PROMPT;
    }

    public function listAvailableModels(): array
    {
        try {
            // Perhatikan URL endpoint ini berbeda dari generateContent
            $response = Http::get("https://generativelanguage.googleapis.com/v1beta/models?key={$this->apiKey}");

            if ($response->failed()) {
                throw new Exception("Failed to list models: {$response->status()} - " . $response->body());
            }

            return $response->json();
        } catch (Exception $e) {
            // Catat error untuk debugging
            Log::error("Error listing Gemini models: " . $e->getMessage());
            throw new Exception("Could not list Gemini models: " . $e->getMessage());
        }
    }
}
