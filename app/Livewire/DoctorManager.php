<?php

namespace App\Livewire;

use App\Models\Doctor;
use Livewire\Component;

class DoctorManager extends Component
{
    public $doctor_id, $iddokter, $nama_dokter, $no_sip, $tgl_berakhir_sip, $spesialisasi, $is_active = true;
    public $isEdit = false;

    protected function rules()
    {
        $doctorIdRule = $this->doctor_id ? ',' . $this->doctor_id : '';

        return [
            'iddokter' => 'required|string|unique:doctors,iddokter' . $doctorIdRule,
            'nama_dokter' => 'required|string|max:255',
            'no_sip' => 'required|string|max:255',
            'tgl_berakhir_sip' => 'required|date',
            'spesialisasi' => 'required|string|max:255',
            'is_active' => 'boolean',
        ];
    }

    public function render()
    {
        $doctors = Doctor::orderBy('id', 'desc')->get();
        return view('livewire.doctor-manager', compact('doctors'));
    }

    public function resetFields()
    {
        $this->doctor_id = null;
        $this->iddokter = '';
        $this->nama_dokter = '';
        $this->no_sip = '';
        $this->tgl_berakhir_sip = '';
        $this->spesialisasi = '';
        $this->is_active = true;
        $this->isEdit = false;
        $this->resetValidation();
    }

    public function store()
    {
        $this->validate();

        Doctor::create([
            'iddokter' => $this->iddokter,
            'nama_dokter' => $this->nama_dokter,
            'no_sip' => $this->no_sip,
            'tgl_berakhir_sip' => $this->tgl_berakhir_sip,
            'spesialisasi' => $this->spesialisasi,
            'is_active' => $this->is_active,
        ]);

        session()->flash('message', 'Data dokter berhasil ditambahkan!');
        $this->resetFields();
    }

    public function edit($id)
    {
        $doctor = Doctor::findOrFail($id);
        $this->doctor_id = $doctor->id;
        $this->iddokter = $doctor->iddokter;
        $this->nama_dokter = $doctor->nama_dokter;
        $this->no_sip = $doctor->no_sip;
        $this->tgl_berakhir_sip = $doctor->tgl_berakhir_sip;
        $this->spesialisasi = $doctor->spesialisasi;
        $this->is_active = (bool) $doctor->is_active;
        $this->isEdit = true;
    }

    public function update()
    {
        $this->validate();

        $doctor = Doctor::findOrFail($this->doctor_id);
        $doctor->update([
            'iddokter' => $this->iddokter,
            'nama_dokter' => $this->nama_dokter,
            'no_sip' => $this->no_sip,
            'tgl_berakhir_sip' => $this->tgl_berakhir_sip,
            'spesialisasi' => $this->spesialisasi,
            'is_active' => $this->is_active,
        ]);

        session()->flash('message', 'Data dokter berhasil diperbarui!');
        $this->resetFields();
    }

    public function toggleStatus($id)
    {
        $doctor = Doctor::findOrFail($id);
        $doctor->update(['is_active' => !$doctor->is_active]);
    }
}
