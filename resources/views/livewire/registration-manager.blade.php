<div class="p-6">
    <h2 class="text-2xl font-bold mb-4">Registrasi Pendaftaran Pasien</h2>

    @if (session()->has('message'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Form Pendaftaran -->
        <div class="bg-white p-4 rounded shadow h-fit">
            <h3 class="font-bold text-lg mb-3">Form Pendaftaran Baru</h3>
            <form wire:submit.prevent="store">
                
                <div class="mb-3">
                    <label class="block font-medium mb-1">Tanggal Berobat</label>
                    <input type="date" wire:model.live="tgl_registrasi" class="w-full border rounded p-2">
                    @error('tgl_registrasi') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label class="block font-medium mb-1">Pilih Pasien</label>
                    <select wire:model="patient_id" class="w-full border rounded p-2">
                        <option value="">-- Pilih Pasien --</option>
                        @foreach($patients as $pasien)
                            <option value="{{ $pasien->id }}">{{ $pasien->no_rm }} - {{ $pasien->nama }}</option>
                        @endforeach
                    </select>
                    @error('patient_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label class="block font-medium mb-1">Pilih Poli Tujuan</label>
                    <select wire:model.live="poli_id" class="w-full border rounded p-2">
                        <option value="">-- Pilih Poli --</option>
                        @foreach($polis as $p)
                            <option value="{{ $p->id }}">{{ $p->nama_poli }}</option>
                        @endforeach
                    </select>
                    @error('poli_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="block font-medium mb-1">Pilih Dokter & Jadwal Praktek</label>
                    <select wire:model="schedule_id" class="w-full border rounded p-2" {{ empty($availableSchedules) ? 'disabled' : '' }}>
                        <option value="">-- {{ empty($availableSchedules) ? 'Jadwal Tidak Tersedia di Hari Ini' : 'Pilih Jadwal Dokter' }} --</option>
                        @foreach($availableSchedules as $sch)
                            <option value="{{ $sch->id }}">
                                {{ $sch->doctor->nama_dokter }} ({{ date('H:i', strtotime($sch->jam_awal)) }}-{{ date('H:i', strtotime($sch->jam_akhir)) }}) - Kuota: {{ $sch->kuota }}
                            </option>
                        @endforeach
                    </select>
                    @error('schedule_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded w-full font-bold">
                    Daftarkan Pasien
                </button>
            </form>
        </div>

        <!-- Tabel Riwayat Registrasi -->
        <div class="md:col-span-2 bg-white p-4 rounded shadow overflow-x-auto">
            <h3 class="font-bold text-lg mb-3">Daftar Kunjungan Pasien</h3>
            <table class="w-full text-left border-collapse border">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border p-2">No. Kunjungan</th>
                        <th class="border p-2">Tgl Berobat</th>
                        <th class="border p-2">Pasien</th>
                        <th class="border p-2">Poli & Dokter</th>
                        <th class="border p-2 text-center">No. Antrian</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($registrations as $reg)
                        <tr>
                            <td class="border p-2 font-mono font-bold text-blue-600">{{ $reg->no_kunjungan }}</td>
                            <td class="border p-2 text-sm">{{ $reg->tgl_registrasi }}</td>
                            <td class="border p-2">
                                <div class="font-bold">{{ $reg->patient->nama ?? '-' }}</div>
                                <div class="text-xs text-gray-500">RM: {{ $reg->patient->no_rm ?? '-' }}</div>
                            </td>
                            <td class="border p-2">
                                <div class="font-bold">{{ $reg->poli->nama_poli ?? '-' }}</div>
                                <div class="text-xs text-gray-600">{{ $reg->schedule->doctor->nama_dokter ?? '-' }}</div>
                            </td>
                            <td class="border p-2 text-center">
                                <span class="bg-blue-100 text-blue-800 font-extrabold px-3 py-1 rounded-full text-lg">
                                    {{ $reg->no_antrian }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="border p-4 text-center text-gray-500">Belum ada data pendaftaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-4">
                {{ $registrations->links() }}
            </div>
        </div>
    </div>
</div>