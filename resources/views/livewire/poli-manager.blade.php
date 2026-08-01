<div class="p-6">
    <h2 class="text-2xl font-bold mb-4">Master Data Poli</h2>

    @if (session()->has('message'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Form Input / Edit Poli -->
        <div class="bg-white p-4 rounded shadow h-fit">
            <h3 class="font-bold text-lg mb-3">{{ $isEdit ? 'Edit Poli' : 'Tambah Poli Baru' }}</h3>
            <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">
                <div class="mb-3">
                    <label class="block font-medium mb-1">Kode Poli</label>
                    <input type="text" wire:model="kode_poli" placeholder="Contoh: POLI-UMUM" class="w-full border rounded p-2 uppercase">
                    @error('kode_poli') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label class="block font-medium mb-1">Nama Poli</label>
                    <input type="text" wire:model="nama_poli" placeholder="Contoh: Poliklinik Umum" class="w-full border rounded p-2">
                    @error('nama_poli') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4 flex items-center gap-2">
                    <input type="checkbox" wire:model="is_active" id="is_active" class="w-4 h-4">
                    <label for="is_active" class="font-medium">Status Aktif</label>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded w-full">
                        {{ $isEdit ? 'Update' : 'Simpan' }}
                    </button>
                    @if($isEdit)
                        <button type="button" wire:click="resetFields" class="bg-gray-500 text-white px-4 py-2 rounded">Batal</button>
                    @endif
                </div>
            </form>
        </div>

        <!-- Tabel Data Poli -->
        <div class="md:col-span-2 bg-white p-4 rounded shadow">
            <table class="w-full text-left border-collapse border">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border p-2">Kode Poli</th>
                        <th class="border p-2">Nama Poli</th>
                        <th class="border p-2 text-center">Status</th>
                        <th class="border p-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($polis as $poli)
                        <tr>
                            <td class="border p-2 font-mono font-bold">{{ $poli->kode_poli }}</td>
                            <td class="border p-2">{{ $poli->nama_poli }}</td>
                            <td class="border p-2 text-center">
                                <button wire:click="toggleStatus({{ $poli->id }})" 
                                        class="px-2 py-1 rounded text-xs text-white font-bold {{ $poli->is_active ? 'bg-green-600' : 'bg-red-600' }}">
                                    {{ $poli->is_active ? 'Aktif' : 'Non-aktif' }}
                                </button>
                            </td>
                            <td class="border p-2 text-center">
                                <button wire:click="edit({{ $poli->id }})" class="bg-yellow-500 text-white px-2 py-1 rounded text-sm">Edit</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="border p-4 text-center text-gray-500">Belum ada data poli.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>