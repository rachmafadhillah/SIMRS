<?php

namespace App\Livewire;

use App\Models\Doctor;
use App\Models\Poli;
use App\Models\Schedule;
use Livewire\Component;

class ScheduleManager extends Component
{
    public $schedule_id, $poli_id, $doctor_id, $hari, $jam_awal, $jam_akhir, $kuota;
    public $isEdit = false;

    public $listHari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

    protected $rules = [
        'poli_id' => 'required|exists:polis,id',
        'doctor_id' => 'required|exists:doctors,id',
        'hari' => 'required|string',
        'jam_awal' => 'required|date_format:H:i',
        'jam_akhir' => 'required|date_format:H:i|after:jam_awal',
        'kuota' => 'required|integer|min:1',
    ];

    public function render()
    {
        $schedules = Schedule::with(['poli', 'doctor'])->orderBy('id', 'desc')->get();
        $polis = Poli::where('is_active', true)->get();
        $doctors = Doctor::where('is_active', true)->get();

        return view('livewire.schedule-manager', compact('schedules', 'polis', 'doctors'));
    }

    public function resetFields()
    {
        $this->schedule_id = null;
        $this->poli_id = '';
        $this->doctor_id = '';
        $this->hari = '';
        $this->jam_awal = '';
        $this->jam_akhir = '';
        $this->kuota = '';
        $this->isEdit = false;
        $this->resetValidation();
    }

    // Checking Logika Jam Bentrok
    private function checkConflict()
    {
        return Schedule::where('doctor_id', $this->doctor_id)
            ->where('hari', $this->hari)
            ->when($this->schedule_id, function ($query) {
                $query->where('id', '!=', $this->schedule_id);
            })
            ->where(function ($query) {
                // Algoritma overlap jam
                $query->where(function ($q) {
                    $q->where('jam_awal', '<', $this->jam_akhir)
                      ->where('jam_akhir', '>', $this->jam_awal);
                });
            })
            ->exists();
    }

    public function store()
    {
        $this->validate();

        if ($this->checkConflict()) {
            $this->addError('doctor_id', 'Jadwal dokter bentrok dengan jadwal lain di hari dan jam yang sama!');
            return;
        }

        Schedule::create([
            'poli_id' => $this->poli_id,
            'doctor_id' => $this->doctor_id,
            'hari' => $this->hari,
            'jam_awal' => $this->jam_awal,
            'jam_akhir' => $this->jam_akhir,
            'kuota' => $this->kuota,
        ]);

        session()->flash('message', 'Jadwal praktek berhasil ditambahkan!');
        $this->resetFields();
    }

    public function edit($id)
    {
        $schedule = Schedule::findOrFail($id);
        $this->schedule_id = $schedule->id;
        $this->poli_id = $schedule->poli_id;
        $this->doctor_id = $schedule->doctor_id;
        $this->hari = $schedule->hari;
        $this->jam_awal = date('H:i', strtotime($schedule->jam_awal));
        $this->jam_akhir = date('H:i', strtotime($schedule->jam_akhir));
        $this->kuota = $schedule->kuota;
        $this->isEdit = true;
    }

    public function update()
    {
        $this->validate();

        if ($this->checkConflict()) {
            $this->addError('doctor_id', 'Jadwal dokter bentrok dengan jadwal lain di hari dan jam yang sama!');
            return;
        }

        $schedule = Schedule::findOrFail($this->schedule_id);
        $schedule->update([
            'poli_id' => $this->poli_id,
            'doctor_id' => $this->doctor_id,
            'hari' => $this->hari,
            'jam_awal' => $this->jam_awal,
            'jam_akhir' => $this->jam_akhir,
            'kuota' => $this->kuota,
        ]);

        session()->flash('message', 'Jadwal praktek berhasil diperbarui!');
        $this->resetFields();
    }

    public function delete($id)
    {
        Schedule::destroy($id);
        session()->flash('message', 'Jadwal praktek berhasil dihapus!');
    }
}