<?php

namespace App\Livewire\Admin\Pages;

use App\Imports\PosyanduImport;
use App\Models\Desa;
use App\Models\Posyandu;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class AdminPosyanduImport extends Component
{
    use WithFileUploads, WithPagination;

    #[Title('Data Posyandu | Gizi Balita Lombok Utara')]


    public $file;
    public $selectedData = null;
    public $search = '';

    public $editId, $nama_dusun, $nama_posyandu, $nama_desa, $latlong;
    public $listDesa = [];

    public $confirmingDeleteId = null;


    protected $paginationTheme = 'bootstrap';

    public function import()
    {
        $this->validate([
            'file' => 'required|file|mimes:xls,xlsx,csv',
        ]);

        Excel::import(new PosyanduImport, $this->file->getRealPath());

        session()->flash('message', 'Data berhasil diimpor!');
        $this->reset('file');
        $this->resetPage();
    }
    public function edit($id)
    {
        $posyandu = Posyandu::join('desas', 'desas.id', '=', 'posyandus.desa_id')->selectRaw('posyandus.*, desas.nama_desa')->findOrFail($id);
        $this->editId = $posyandu->id;
        $this->nama_dusun = $posyandu->nama_dusun;
        $this->nama_posyandu = $posyandu->nama_posyandu;
        $this->nama_desa = $posyandu->desa_id;
        $this->latlong = $posyandu->latlong;
    }

    public function update()
    {
        $this->validate([
            'nama_dusun' => 'required',
            'nama_posyandu' => 'required',
            'nama_desa' => 'required',
            'latlong' => 'required',
        ]);
        $posyandu = Posyandu::findOrFail($this->editId);
        $posyandu->update([
            'nama_dusun' => $this->nama_dusun,
            'nama_posyandu' => $this->nama_posyandu,
            'desa_id' => $this->nama_desa,
            'latlong' => $this->latlong,
        ]);

        session()->flash('message', 'Data berhasil diperbarui.');
        $this->reset(['editId', 'nama_dusun', 'nama_posyandu', 'nama_desa', 'latlong']);
        $this->dispatch('close-edit-modal');
    }

    public function confirmDelete($id)
    {
        $this->confirmingDeleteId = $id;
    }

    public function delete()
    {
        Posyandu::findOrFail($this->confirmingDeleteId)->delete();
        $this->confirmingDeleteId = null;
        session()->flash('message', 'Data berhasil dihapus.');
    }

    public function mount (){
        $this->listDesa = Desa::get();
    }

    public function render()
    {
        $query = Posyandu::query()
        ->join('desas', 'desas.id', '=', 'posyandus.desa_id')
        ->selectRaw('posyandus.*, desas.nama_desa');
        
        if ($this->search) {
            $query->where(function($q) {
                $q->where('nama_dusun', 'like', '%' . $this->search . '%')
                  ->orWhere('nama_posyandu', 'like', '%' . $this->search . '%')
                  ->orWhere('nama_desa', 'like', '%' . $this->search . '%');
            });
        }

        $dataPosyandu = $query->orderBy('desas.nama_desa', 'asc')->paginate(10);
        return view('livewire.admin.pages.admin-posyandu-import',[
            'dataPosyandu'=>$dataPosyandu,
            'listDesa' => $this->listDesa,
        ]);
    }
}
