<?php

namespace App\Livewire\Upload;

use App\Models\Document;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Services\FileExtractService;
use App\Services\PdfThumbnailService;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    use WithFileUploads;

    public $file;

    protected $rules = [
        'file' => 'required|mimes:pdf|max:12048',
    ];

    public function updatedFile()
    {
        $this->upload();
    }

    public function upload()
    {
        $this->validate();

        Log::info('Memulai proses unggah file.');

        try {
            Log::info('Validasi file berhasil.');

            // Pastikan $this->file tidak null sebelum mencoba mengaksesnya
            if (!$this->file) {
                Log::error('Properti $this->file kosong setelah validasi.');
                session()->flash('error', 'File tidak ditemukan untuk diunggah.');
                return;
            }

            Log::debug('Detail file yang diterima: ' . json_encode([
                'filename' => $this->file->getClientOriginalName(),
                'size' => $this->file->getSize(),
                'mimeType' => $this->file->getMimeType(),
                'extension' => $this->file->getClientOriginalExtension(),
                'realPath' => $this->file->getRealPath(),
            ]));

            // Simpan sementara
            $docsPath = $this->file->store('docs', 'local');
            $fullPath = Storage::disk('local')->path($docsPath);
            Log::info('File disimpan sementara di: ' . $fullPath);

            $thumbnailPath = 'thiings-folder.png';

            // Periksa apakah file benar-benar ada di temp path
            if (!file_exists($fullPath)) {
                Log::error('Gagal menyimpan file sementara ke: ' . $fullPath);
                session()->flash('error', 'Gagal menyimpan file untuk pemrosesan.');
                return;
            }

            // Kirim ke API ekstraksi
            Log::info('Mencoba mengirim file ke API ekstraksi...');
            $response = app(FileExtractService::class)->sendToExtractor(
                $fullPath,
                $this->file->getClientOriginalName()
            );

            // Hasil
            if ($response->successful()) {
                $data = $response->json();

                Document::create([
                    'name' => pathinfo($this->file->getClientOriginalName(), PATHINFO_FILENAME),
                    'category' => $data['category'] ?? 'Lainnya',
                    // 'raw_content' => $data['raw'] ?? '',
                    'content' => $data['text'] ?? '',
                    'file' => $docsPath,
                    'thumbnail' => $thumbnailPath,
                    'user_id' => Auth::user()->id,
                ]);

                $this->reset('file');
                session()->flash('success', 'Ekstraksi & penyimpanan berhasil.');
            } else {
                session()->flash('error', 'Gagal ekstraksi: ' . $response->status());
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validasi file gagal: ' . $e->getMessage(), ['errors' => $e->errors()]);
            throw $e; // Lempar kembali agar Livewire menampilkan error validasi di Blade
        } catch (\Throwable $e) {
            Log::critical('Terjadi kesalahan tak terduga selama proses unggah: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
        }
    }

    public function render()
    {
        return view('livewire.upload.index');
    }
}
