<?php

namespace App\Livewire;

use App\Models\Patient;
use Livewire\Component;
use Livewire\WithPagination;

class PatientManager extends Component
{
    use WithPagination;

    public $search = '';
    public $patient_id, $nik, $nama, $jenis_kelamin = 'L', $tgl_lahir, $alamat, $no_hp;
    public $isEdit = false;

    protected function rules()
    {
        $patientIdRule = $this->patient_id ? ',' . $this->patient_id : '';

        return [
            'nik' => 'required|numeric|digits:16|unique:patients,nik' . $patientIdRule,
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tgl_lahir' => 'required|date',
            'alamat' => 'required|string',
            'no_hp' => 'required|string|max:15',
        ];
    }

    // Reset pagination ketika melakukan pencarian
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $patients = Patient::where('nama', 'like', '%' . $this->search . '%')
            ->orWhere('no_rm', 'like', '%' . $this->search . '%')
            ->orWhere('nik', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.patient-manager', compact('patients'));
    }

    public function resetFields()
    {
        $this->patient_id = null;
        $this->nik = '';
        $this->nama = '';
        $this->jenis_kelamin = 'L'; // Reset ke default 'L'
        $this->tgl_lahir = '';
        $this->alamat = '';
        $this->no_hp = '';
        $this->isEdit = false;
        $this->resetValidation();
    }

    // Logika Auto-Generate No RM 8 Digit (contoh: 00000001)
    private function generateNoRm()
    {
        $lastPatient = Patient::orderBy('id', 'desc')->first();
        if (!$lastPatient) {
            return '00000001';
        }
        $lastNumber = (int) $lastPatient->no_rm;
        return str_pad($lastNumber + 1, 8, '0', STR_PAD_LEFT);
    }

    public function store()
    {
        $this->validate();

        Patient::create([
            'no_rm' => $this->generateNoRm(),
            'nik' => $this->nik,
            'nama' => $this->nama,
            'jenis_kelamin' => $this->jenis_kelamin,
            'tgl_lahir' => $this->tgl_lahir,
            'alamat' => $this->alamat,
            'no_hp' => $this->no_hp,
        ]);

        session()->flash('message', 'Pasien berhasil ditambahkan!');
        $this->resetFields();
    }

    public function edit($id)
    {
        $patient = Patient::findOrFail($id);
        $this->patient_id = $patient->id;
        $this->nik = $patient->nik;
        $this->nama = $patient->nama;
        $this->jenis_kelamin = $patient->jenis_kelamin; // <-- WAJIB: Mengambil jenis kelamin eksisting pasien
        $this->tgl_lahir = $patient->tgl_lahir;
        $this->alamat = $patient->alamat;
        $this->no_hp = $patient->no_hp;
        $this->isEdit = true;
    }

    public function update()
    {
        $this->validate();

        $patient = Patient::findOrFail($this->patient_id);
        $patient->update([
            'nik' => $this->nik,
            'nama' => $this->nama,
            'jenis_kelamin' => $this->jenis_kelamin, // <-- WAJIB: Menyimpan perubahan jenis kelamin
            'tgl_lahir' => $this->tgl_lahir,
            'alamat' => $this->alamat,
            'no_hp' => $this->no_hp,
        ]);

        session()->flash('message', 'Data pasien berhasil diperbarui!');
        $this->resetFields();
    }

    public function delete($id)
    {
        Patient::destroy($id);
        session()->flash('message', 'Pasien berhasil dihapus!');
    }
}
