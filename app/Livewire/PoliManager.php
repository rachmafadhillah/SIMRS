<?php

namespace App\Livewire;

use Livewire\Component;

namespace App\Livewire;

use App\Models\Poli;
use Livewire\Component;

class PoliManager extends Component
{
    public $poli_id, $kode_poli, $nama_poli, $is_active = true;
    public $isEdit = false;

    protected function rules()
    {
        $poliIdRule = $this->poli_id ? ',' . $this->poli_id : '';

        return [
            'kode_poli' => 'required|string|unique:polis,kode_poli' . $poliIdRule,
            'nama_poli' => 'required|string|max:255',
            'is_active' => 'boolean',
        ];
    }

    public function render()
    {
        $polis = Poli::orderBy('id', 'desc')->get();
        return view('livewire.poli-manager', compact('polis'));
    }

    public function resetFields()
    {
        $this->poli_id = null;
        $this->kode_poli = '';
        $this->nama_poli = '';
        $this->is_active = true;
        $this->isEdit = false;
        $this->resetValidation();
    }

    public function store()
    {
        $this->validate();

        Poli::create([
            'kode_poli' => strtoupper($this->kode_poli),
            'nama_poli' => $this->nama_poli,
            'is_active' => $this->is_active,
        ]);

        session()->flash('message', 'Poli berhasil ditambahkan!');
        $this->resetFields();
    }

    public function edit($id)
    {
        $poli = Poli::findOrFail($id);
        $this->poli_id = $poli->id;
        $this->kode_poli = $poli->kode_poli;
        $this->nama_poli = $poli->nama_poli;
        $this->is_active = (bool) $poli->is_active;
        $this->isEdit = true;
    }

    public function update()
    {
        $this->validate();

        $poli = Poli::findOrFail($this->poli_id);
        $poli->update([
            'kode_poli' => strtoupper($this->kode_poli),
            'nama_poli' => $this->nama_poli,
            'is_active' => $this->is_active,
        ]);

        session()->flash('message', 'Poli berhasil diperbarui!');
        $this->resetFields();
    }

    public function toggleStatus($id)
    {
        $poli = Poli::findOrFail($id);
        $poli->update(['is_active' => !$poli->is_active]);
    }
}
