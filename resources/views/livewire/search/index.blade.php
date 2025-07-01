<div>
    <flux:heading size="xl" level="1">{{ __('Pencarian Data') }}</flux:heading>
    <flux:subheading size="lg" class="mb-6">
        {{ __('Silakan ketikan kata atau kalimat yang akan dicari pada form dibawah.') }}</flux:subheading>
    <flux:separator variant="subtle" />
    <div class="space-y-6">
        <flux:field label="Cari Pengguna">
            <flux:input wire:model.live="query" placeholder="..." />
            <div wire:loading.flex class="text-sm text-blue-500">Mencari...</div>
        </flux:field>

        @if (strlen($query) > 2)
            <div class="space-y-2">
                @forelse($results as $user)
                    <div class="border rounded-lg p-4 shadow-sm bg-white dark:bg-neutral-900">
                        <p class="font-semibold text-lg">{{ $user->name }}</p>
                        <p class="text-sm text-neutral-600">{{ $user->email }}</p>
                    </div>
                @empty
                    <p class="text-neutral-500">Tidak ada hasil ditemukan.</p>
                @endforelse
            </div>
        @endif
    </div>
</div>
