<?php

namespace App\Livewire\Admin\Pages;

use App\Models\Type;
use Livewire\Attributes\Title;
use Livewire\Component;

class AdminType extends Component
{
    #[Title('Type Status Gizi | Gizi Balita Lombok Utara')]

    public $typeId = null;
    public $name= '';
    public $search = '';

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
        ]);

        $slug = str()->slug($this->name);

        Type::updateOrCreate(
            ['id' => $this->typeId],
            ['name' => $this->name, 'slug' => $slug]
        );

        $this->resetForm();
        session()->flash('message', 'Data berhasil disimpan.');
    }

    public function edit($id)
    {
        $desa = Type::findOrFail($id);
        $this->typeId = $desa->id;
        $this->name = $desa->name;

    }

    public function delete($id)
    {
        Type::findOrFail($id)->delete();
        session()->flash('message', 'Data berhasil dihapus.');
    }

    public function resetForm()
    {
        $this->typeId = null;
        $this->name = '';
        $this->dispatch('clear-marker');
    }

    protected $paginationTheme = 'bootstrap';
    public function render()
    {
        $query = Type::query();
        
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            });
        }

        $typeGizis = $query->paginate(5);

        return view('livewire.admin.pages.admin-type', [
            'typeGizis' => $typeGizis
        ]);
    }
}
