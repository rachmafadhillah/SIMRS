<?php

namespace App\Livewire;

use App\Models\Doctor;
use App\Models\Poli;
use App\Models\Registration;
use Carbon\Carbon;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $today = Carbon::today()->toDateString();

        // 1. Total Kunjungan Hari Ini
        $totalToday = Registration::where('tgl_registrasi', $today)->count();

        // 2. Total Dokter Aktif
        $totalDoctors = Doctor::where('is_active', true)->count();

        // 3. Total Pasien Laki-laki Berobat Hari Ini
        $totalMale = Registration::where('tgl_registrasi', $today)
            ->whereHas('patient', function ($q) {
                $q->where('jenis_kelamin', 'L');
            })->count();

        // 4. Total Pasien Perempuan Berobat Hari Ini
        $totalFemale = Registration::where('tgl_registrasi', $today)
            ->whereHas('patient', function ($q) {
                $q->where('jenis_kelamin', 'P');
            })->count();

        // Data statistik kunjungan per poli hari ini untuk Grafik
        $kunjunganPerPoli = Poli::get()->map(function ($poli) use ($today) {
            return [
                'nama_poli' => $poli->nama_poli,
                'total' => Registration::where('poli_id', $poli->id)
                    ->where('tgl_registrasi', $today)
                    ->count(),
            ];
        });

        return view('livewire.dashboard', compact(
            'totalToday',
            'totalDoctors',
            'totalMale',
            'totalFemale',
            'kunjunganPerPoli',
            'today'
        ));
    }
}
