<?php

namespace App\Livewire\Admin\Pages;

use App\Helpers\BeratBadan;
use App\Helpers\DateHelper;
use App\Imports\ImportsStatusGizi;
use App\Models\StatusGizi;
use App\Models\Type;
use Carbon\Carbon;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class StatusGiziImport extends Component
{
    use WithFileUploads, WithPagination;

    #[Title('Import Data Status Gizi | Gizi Balita Lombok Utara')]
    //data
    public $file, $type_id, $month, $year;

    //filter
    public $selectedData = null;
    public $search = '';
    public $filterType_id, $filterMonth, $filterYear;

    //mount data
    public $typeGizis =[];
    public $listMonths = [];
    public $listYears = []; 
    public $nowMonth = '';
    public $nowYear ='';


    protected $paginationTheme = 'bootstrap';

    public function mount(){
        Carbon::setLocale('id'); 
        $this->typeGizis = Type::get();
        $this->listMonths = collect(range(1, 12))->mapWithKeys(function ($month) {
            return [$month => Carbon::create()->month($month)->translatedFormat('F')];
        })->toArray();
        $currentYear = now()->year;
        $this->listYears = collect(range($currentYear - 5, $currentYear))->toArray();
        $this->nowMonth = now()->month;
        $this->nowYear = now()->year;
    }

    public function import()
    {
        $this->validate([
            'file' => 'required|file|mimes:xls,xlsx,csv',
            'type_id' => 'required',
            'month' => 'required',
            'year' => 'required',
        ]);

        Excel::import(
            new ImportsStatusGizi($this->type_id, $this->month, $this->year),
            $this->file->getRealPath()
        );

        session()->flash('message', 'Data berhasil diimpor!');
        $this->reset('file');
        $this->resetPage();
    }


    public function showDetail($id)
    {
        $this->selectedData = StatusGizi::find($id);

        if ($this->selectedData) {
            $this->selectedData->usia_dalam_hari = DateHelper::convertToDays($this->selectedData->usia_saat_ukur);
        }
    }

    public function closeDetail()
    {
        $this->selectedData = null;
    }

    public function filter()
    {
        // Just reset page to page 1 when filter form submitted
        $this->resetPage();
    }

    public function confirmDeleteFiltered()
    {
         $this->dispatch('confirm-delete');
    }

    public function deleteFiltered()
    {
        $query = StatusGizi::query();

        if ($this->filterType_id) {
            $query->where('type_id', $this->filterType_id);
        }
        if ($this->filterMonth) {
            $query->where('month', $this->filterMonth);
        }
        if ($this->filterYear) {
            $query->where('year', $this->filterYear);
        }

        $deletedCount = $query->delete();

        if ($deletedCount > 0) {
            session()->flash('message', "Berhasil menghapus {$deletedCount} data.");
        } else {
            session()->flash('message', 'Tidak ada data yang dihapus.');
        }
        $this->resetPage();
    }


    public function render()
    {
        $query = StatusGizi::query();
        
        if ($this->search) {
            $query->where(function($q) {
                $q->where('nama', 'like', '%' . $this->search . '%')
                  ->orWhere('nik', 'like', '%' . $this->search . '%')
                  ->orWhere('nama_ortu', 'like', '%' . $this->search . '%');
            });
        }

        // Apply filters
        if ($this->filterType_id) {
            $query->where('type_id', $this->filterType_id);
        }
        if ($this->filterMonth) {
            $query->where('month', $this->filterMonth);
        }
        if ($this->filterYear) {
            $query->where('year', $this->filterYear);
        }

        $statusGizis = $query->orderBy('year', 'desc')->orderBy('month', 'desc')->orderBy('nama', 'asc')->paginate(10);
        return view('livewire.admin.pages.status-gizi-import', [
            'statusGizis' => $statusGizis,
        ]);
    }
}

