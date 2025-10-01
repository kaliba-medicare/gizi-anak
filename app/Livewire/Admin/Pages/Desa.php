<?php

namespace App\Livewire\Admin\Pages;

use App\Models\Desa as ModelsDesa;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class Desa extends Component
{
    use WithPagination;

    #[Title('Data Desa | Gizi Balita Lombok Utara')]
    
    public $desaId = null;
    public $nama_desa = '';
    public $kecamatan = '';
    public $search = '';

    protected $paginationTheme = 'bootstrap';

    public function save()
    {
        $this->validate([
            'nama_desa' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
        ]);

        ModelsDesa::updateOrCreate(
            ['id' => $this->desaId],
            ['nama_desa' => $this->nama_desa, 'kecamatan' => $this->kecamatan]
        );

        $this->resetForm();
        session()->flash('message', 'Data berhasil disimpan.');
    }

    public function edit($id)
    {
        $desa = ModelsDesa::findOrFail($id);
        $this->desaId = $desa->id;
        $this->nama_desa = $desa->nama_desa;
        $this->kecamatan = $desa->kecamatan;

        $this->dispatch('set-marker', $this->kecamatan);
    }

    public function delete($id)
    {
        ModelsDesa::findOrFail($id)->delete();
        session()->flash('message', 'Data berhasil dihapus.');
    }

    public function resetForm()
    {
        $this->desaId = null;
        $this->nama_desa = '';
        $this->kecamatan = '';
        $this->dispatch('clear-marker');
    }

    public function render()
    {
        $query = ModelsDesa::query();
        
        if ($this->search) {
            $query->where(function($q) {
                $q->where('nama_desa', 'like', '%' . $this->search . '%')
                  ->orWhere('kecamatan', 'like', '%' . $this->search . '%');
            });
        }

        $data = $query->orderby('desas.kecamatan', 'asc')->orderBy('desas.nama_desa', 'asc')->paginate(5);
        return view('livewire.admin.pages.desa', [
            'desas' => $data
        ]);
    }
}
