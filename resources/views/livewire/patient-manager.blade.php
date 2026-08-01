<div class="p-6">
    <h2 class="text-2xl font-bold mb-4">Master Data Pasien</h2>

    @if (session()->has('message'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    <!-- Form Input / Edit -->
    <div class="bg-white p-4 rounded shadow mb-6">
        <h3 class="font-bold text-lg mb-2">{{ $isEdit ? 'Edit Pasien' : 'Tambah Pasien Baru' }}</h3>
        <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-medium">NIK</label>
                    <input type="text" wire:model="nik" class="w-full border rounded p-2">
                    @error('nik')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label class="block font-medium">Nama Pasien</label>
                    <input type="text" wire:model="nama" class="w-full border rounded p-2">
                    @error('nama')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label class="block font-medium">Jenis Kelamin</label>
                    <select wire:model="jenis_kelamin" class="w-full border rounded p-2">
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                    @error('jenis_kelamin')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label class="block font-medium">Tanggal Lahir</label>
                    <input type="date" wire:model="tgl_lahir" class="w-full border rounded p-2">
                    @error('tgl_lahir')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label class="block font-medium">No. HP</label>
                    <input type="text" wire:model="no_hp" class="w-full border rounded p-2">
                    @error('no_hp')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-span-2">
                    <label class="block font-medium">Alamat</label>
                    <textarea wire:model="alamat" class="w-full border rounded p-2"></textarea>
                    @error('alamat')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="mt-4 flex gap-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                    {{ $isEdit ? 'Update' : 'Simpan' }}
                </button>
                @if ($isEdit)
                    <button type="button" wire:click="resetFields"
                        class="bg-gray-500 text-white px-4 py-2 rounded">Batal</button>
                @endif
            </div>
        </form>
    </div>

    <!-- Table & Search -->
    <div class="bg-white p-4 rounded shadow">
        <div class="mb-4">
            <input type="text" wire:model.live="search" placeholder="Cari Nama / No. RM / NIK..."
                class="w-full border rounded p-2">
        </div>

        <table class="w-full text-left border-collapse border">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border p-2">No. RM</th>
                    <th class="border p-2">NIK</th>
                    <th class="border p-2">Nama</th>
                    <th class="border p-2">Tgl Lahir</th>
                    <th class="border p-2">No HP</th>
                    <th class="border p-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($patients as $patient)
                    <tr>
                        <td class="border p-2 font-mono font-bold text-blue-600">{{ $patient->no_rm }}</td>
                        <td class="border p-2">{{ $patient->nik }}</td>
                        <td class="border p-2">{{ $patient->nama }}</td>
                        <td class="border p-2">{{ $patient->tgl_lahir }}</td>
                        <td class="border p-2">{{ $patient->no_hp }}</td>
                        <td class="border p-2 flex gap-2">
                            <button wire:click="edit({{ $patient->id }})"
                                class="bg-yellow-500 text-white px-2 py-1 rounded text-sm">Edit</button>
                            <button wire:click="delete({{ $patient->id }})"
                                wire:confirm="Apakah Anda yakin ingin menghapus data pasien ini?"
                                class="bg-red-600 text-white px-2 py-1 rounded text-sm">
                                Hapus
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="border p-4 text-center text-gray-500">Data pasien tidak ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $patients->links() }}
        </div>
    </div>
</div>
