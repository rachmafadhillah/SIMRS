<?php

namespace App\Livewire;

use App\Models\Patient;
use App\Models\Poli;
use App\Models\Registration;
use App\Models\Schedule;
use Carbon\Carbon;
use Livewire\Component;

class RegistrationManager extends Component
{
    public $tgl_registrasi;
    public $patient_id, $poli_id, $schedule_id;
    public $availableSchedules = [];

    protected $rules = [
        'tgl_registrasi' => 'required|date',
        'patient_id' => 'required|exists:patients,id',
        'poli_id' => 'required|exists:polis,id',
        'schedule_id' => 'required|exists:schedules,id',
    ];

    public function mount()
    {
        $this->tgl_registrasi = date('Y-m-d');
    }

    // Trigger saat memilih Poli atau Tanggal
    public function updatedPoliId()
    {
        $this->loadSchedules();
    }

    public function updatedTglRegistrasi()
    {
        $this->loadSchedules();
    }

    // Filter jadwal berdasarkan Poli & Hari dari tanggal yang dipilih
    public function loadSchedules()
    {
        $this->schedule_id = null;
        if ($this->poli_id && $this->tgl_registrasi) {
            // Dapatkan nama hari dalam Bahasa Indonesia dari tanggal pilihan
            $dayName = Carbon::parse($this->tgl_registrasi)->locale('id')->isoFormat('dddd');
            
            $this->availableSchedules = Schedule::with('doctor')
                ->where('poli_id', $this->poli_id)
                ->where('hari', ucfirst($dayName))
                ->get();
        } else {
            $this->availableSchedules = [];
        }
    }

    // Generate No Kunjungan Format: YYMMDD0001
    private function generateNoKunjungan($date)
    {
        $dateFormatted = Carbon::parse($date)->format('ymd'); // Contoh: 260801
        
        $lastRegistration = Registration::where('no_kunjungan', 'like', $dateFormatted . '%')
            ->orderBy('id', 'desc')
            ->first();

        if (!$lastRegistration) {
            return $dateFormatted . '0001';
        }

        $lastSequence = (int) substr($lastRegistration->no_kunjungan, -4);
        return $dateFormatted . str_pad($lastSequence + 1, 4, '0', STR_PAD_LEFT);
    }

    // Generate No Antrian Auto-increment per Poli per Hari
    private function generateNoAntrian($poliId, $date)
    {
        $lastAntrian = Registration::where('poli_id', $poliId)
            ->where('tgl_registrasi', $date)
            ->max('no_antrian');

        return $lastAntrian ? $lastAntrian + 1 : 1;
    }

    public function store()
    {
        $this->validate();

        $schedule = Schedule::findOrFail($this->schedule_id);

        // Validasi Kuota Penuh
        $totalRegistered = Registration::where('schedule_id', $this->schedule_id)
            ->where('tgl_registrasi', $this->tgl_registrasi)
            ->count();

        if ($totalRegistered >= $schedule->kuota) {
            $this->addError('schedule_id', 'Mohon maaf, kuota pendaftaran untuk jadwal dokter ini sudah penuh!');
            return;
        }

        // Simpan Registrasi
        Registration::create([
            'no_kunjungan' => $this->generateNoKunjungan($this->tgl_registrasi),
            'tgl_registrasi' => $this->tgl_registrasi,
            'patient_id' => $this->patient_id,
            'schedule_id' => $this->schedule_id,
            'poli_id' => $this->poli_id,
            'no_antrian' => $this->generateNoAntrian($this->poli_id, $this->tgl_registrasi),
        ]);

        session()->flash('message', 'Pendaftaran berobat berhasil disimpan!');
        $this->reset(['patient_id', 'poli_id', 'schedule_id', 'availableSchedules']);
    }

    public function render()
    {
        $patients = Patient::orderBy('nama', 'asc')->get();
        $polis = Poli::where('is_active', true)->get();
        
        $registrations = Registration::with(['patient', 'poli', 'schedule.doctor'])
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.registration-manager', compact('patients', 'polis', 'registrations'));
    }
}
