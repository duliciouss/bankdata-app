<div>
    <flux:heading size="xl" level="1">Unggah Dokumen</flux:heading>
    <flux:subheading size="lg" class="mb-6">Silakan unggah file PDF untuk ekstraksi otomatis.</flux:subheading>
    <flux:separator variant="subtle" class="mb-4" />

    <form wire:submit.prevent="upload" class="space-y-6" enctype="multipart/form-data">
        {{-- File Upload --}}
        <flux:field label="File PDF">
            <flux:input type="file" wire:model="file" accept=".pdf" />
            @error('file')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </flux:field>
    </form>

    {{-- Flash Messages --}}
    @if (session()->has('success'))
        <p class="text-green-600 text-sm mt-4">{{ session('success') }}</p>
    @endif

    @if (session()->has('error'))
        <p class="text-red-600 text-sm mt-4">{{ session('error') }}</p>
    @endif
</div>
