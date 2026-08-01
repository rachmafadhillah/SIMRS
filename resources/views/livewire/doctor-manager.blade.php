<div class="p-6">
    <h2 class="text-2xl font-bold mb-4">Master Data Dokter</h2>

    @if (session()->has('message'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Form Input Dokter -->
        <div class="bg-white p-4 rounded shadow h-fit">
            <h3 class="font-bold text-lg mb-3">{{ $isEdit ? 'Edit Dokter' : 'Tambah Dokter Baru' }}</h3>
            <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">
                <div class="mb-3">
                    <label class="block font-medium mb-1">ID Dokter</label>
                    <input type="text" wire:model="iddokter" placeholder="Contoh: DOC001" class="w-full border rounded p-2">
                    @error('iddokter') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label class="block font-medium mb-1">Nama Dokter</label>
                    <input type="text" wire:model="nama_dokter" placeholder="dr. John Doe, Sp.A" class="w-full border rounded p-2">
                    @error('nama_dokter') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label class="block font-medium mb-1">No. SIP</label>
                    <input type="text" wire:model="no_sip" class="w-full border rounded p-2">
                    @error('no_sip') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label class="block font-medium mb-1">Tgl Berakhir SIP</label>
                    <input type="date" wire:model="tgl_berakhir_sip" class="w-full border rounded p-2">
                    @error('tgl_berakhir_sip') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label class="block font-medium mb-1">Spesialisasi</label>
                    <input type="text" wire:model="spesialisasi" placeholder="Anak / Umum / Kandungan" class="w-full border rounded p-2">
                    @error('spesialisasi') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4 flex items-center gap-2">
                    <input type="checkbox" wire:model="is_active" id="is_active_doc" class="w-4 h-4">
                    <label for="is_active_doc" class="font-medium">Status Aktif</label>
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

        <!-- Tabel Dokter -->
        <div class="md:col-span-2 bg-white p-4 rounded shadow overflow-x-auto">
            <table class="w-full text-left border-collapse border">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border p-2">ID</th>
                        <th class="border p-2">Nama Dokter</th>
                        <th class="border p-2">Spesialisasi</th>
                        <th class="border p-2">No. SIP & Exp</th>
                        <th class="border p-2 text-center">Status</th>
                        <th class="border p-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($doctors as $doc)
                        <tr>
                            <td class="border p-2 font-mono font-bold">{{ $doc->iddokter }}</td>
                            <td class="border p-2 font-bold">{{ $doc->nama_dokter }}</td>
                            <td class="border p-2">{{ $doc->spesialisasi }}</td>
                            <td class="border p-2 text-sm">
                                <div>{{ $doc->no_sip }}</div>
                                <div class="text-gray-500 text-xs">Exp: {{ $doc->tgl_berakhir_sip }}</div>
                            </td>
                            <td class="border p-2 text-center">
                                <button wire:click="toggleStatus({{ $doc->id }})" 
                                        class="px-2 py-1 rounded text-xs text-white font-bold {{ $doc->is_active ? 'bg-green-600' : 'bg-red-600' }}">
                                    {{ $doc->is_active ? 'Aktif' : 'Non-aktif' }}
                                </button>
                            </td>
                            <td class="border p-2 text-center">
                                <button wire:click="edit({{ $doc->id }})" class="bg-yellow-500 text-white px-2 py-1 rounded text-sm">Edit</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="border p-4 text-center text-gray-500">Belum ada data dokter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>