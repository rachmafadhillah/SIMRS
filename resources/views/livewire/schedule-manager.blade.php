<div class="p-6">
    <h2 class="text-2xl font-bold mb-4">Jadwal Praktek Dokter</h2>

    @if (session()->has('message'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Form Input Jadwal -->
        <div class="bg-white p-4 rounded shadow h-fit">
            <h3 class="font-bold text-lg mb-3">{{ $isEdit ? 'Edit Jadwal' : 'Tambah Jadwal Baru' }}</h3>
            <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">
                <div class="mb-3">
                    <label class="block font-medium mb-1">Pilih Dokter</label>
                    <select wire:model="doctor_id" class="w-full border rounded p-2">
                        <option value="">-- Pilih Dokter --</option>
                        @foreach($doctors as $doc)
                            <option value="{{ $doc->id }}">{{ $doc->nama_dokter }} ({{ $doc->spesialisasi }})</option>
                        @endforeach
                    </select>
                    @error('doctor_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label class="block font-medium mb-1">Pilih Poli</label>
                    <select wire:model="poli_id" class="w-full border rounded p-2">
                        <option value="">-- Pilih Poli --</option>
                        @foreach($polis as $p)
                            <option value="{{ $p->id }}">{{ $p->nama_poli }}</option>
                        @endforeach
                    </select>
                    @error('poli_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label class="block font-medium mb-1">Hari</label>
                    <select wire:model="hari" class="w-full border rounded p-2">
                        <option value="">-- Pilih Hari --</option>
                        @foreach($listHari as $h)
                            <option value="{{ $h }}">{{ $h }}</option>
                        @endforeach
                    </select>
                    @error('hari') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-2 gap-2 mb-3">
                    <div>
                        <label class="block font-medium mb-1">Jam Awal</label>
                        <input type="time" wire:model="jam_awal" class="w-full border rounded p-2">
                        @error('jam_awal') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block font-medium mb-1">Jam Akhir</label>
                        <input type="time" wire:model="jam_akhir" class="w-full border rounded p-2">
                        @error('jam_akhir') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block font-medium mb-1">Kuota Pasien</label>
                    <input type="number" wire:model="kuota" min="1" class="w-full border rounded p-2">
                    @error('kuota') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
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

        <!-- Tabel Jadwal Praktek -->
        <div class="md:col-span-2 bg-white p-4 rounded shadow overflow-x-auto">
            <table class="w-full text-left border-collapse border">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border p-2">Dokter</th>
                        <th class="border p-2">Poli</th>
                        <th class="border p-2">Hari & Jam</th>
                        <th class="border p-2 text-center">Kuota</th>
                        <th class="border p-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($schedules as $sch)
                        <tr>
                            <td class="border p-2 font-bold">{{ $sch->doctor->nama_dokter ?? '-' }}</td>
                            <td class="border p-2">{{ $sch->poli->nama_poli ?? '-' }}</td>
                            <td class="border p-2">
                                <span class="font-semibold text-blue-600">{{ $sch->hari }}</span>
                                <div class="text-sm text-gray-600">{{ date('H:i', strtotime($sch->jam_awal)) }} - {{ date('H:i', strtotime($sch->jam_akhir)) }}</div>
                            </td>
                            <td class="border p-2 text-center font-bold">{{ $sch->kuota }}</td>
                            <td class="border p-2 text-center">
                                <button wire:click="edit({{ $sch->id }})" class="bg-yellow-500 text-white px-2 py-1 rounded text-sm mb-1">Edit</button>
                                <button wire:click="delete({{ $sch->id }})" wire:confirm="Hapus jadwal ini?" class="bg-red-600 text-white px-2 py-1 rounded text-sm">Hapus</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="border p-4 text-center text-gray-500">Belum ada jadwal praktek.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>