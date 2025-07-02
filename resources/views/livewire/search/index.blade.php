<div>
    <flux:heading size="xl" level="1">{{ __('Pencarian Dokumen') }}</flux:heading>
    <flux:subheading size="lg" class="mb-6">
        {{ __('Silakan ketikan kata atau kalimat yang akan dicari pada form dibawah.') }}</flux:subheading>
    <flux:separator variant="subtle" class="mb-4" />
    <div class="space-y-6">
        <flux:field label="Cari dokumen">
            <flux:input wire:model.live.debounce.250ms="query" placeholder="Cari dokumen..." />
            <div wire:loading.flex class="text-sm">Mencari...</div>
        </flux:field>

        @if (strlen($query) > 2)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
                @forelse($results as $doc)
                    <div class="bg-white dark:bg-neutral-900 rounded-2xl shadow border overflow-hidden flex flex-col">
                        @php
                            $thumbnailPath = 'storage/' . $doc->thumbnail;
                            $defaultThumbnail = asset('images/thiings-folder.png'); // fallback
                        @endphp

                        <img src="{{ file_exists(public_path($thumbnailPath)) ? asset($thumbnailPath) : $defaultThumbnail }}"
                            alt="Thumbnail {{ $doc->name }}" class="w-full h-40 object-cover">

                        <div class="p-4 flex flex-col flex-1 space-y-2">
                            {{-- Badge Kategori --}}
                            <span
                                class="inline-block px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-100 w-fit">
                                {{ $doc->category }}
                            </span>

                            {{-- Judul --}}
                            <h2 class="text-lg font-semibold text-neutral-900 dark:text-white">
                                {{ $doc->name }}
                            </h2>

                            {{-- Ringkasan Konten --}}
                            <p class="text-sm text-neutral-600 dark:text-neutral-300">
                                {{ \Illuminate\Support\Str::limit(strip_tags($doc->content), 100) }}
                            </p>
                        </div>
                    </div>
                @empty
                    <p class="text-neutral-500 dark:text-neutral-400 col-span-full">Tidak ada hasil ditemukan.</p>
                @endforelse
            </div>
        @endif
    </div>
</div>
