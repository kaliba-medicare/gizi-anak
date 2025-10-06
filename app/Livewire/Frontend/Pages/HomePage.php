<?php

namespace App\Livewire\Frontend\Pages;

use App\Models\Desa;
use App\Models\Posyandu;
use App\Models\Type;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Log;

class HomePage extends Component
{
    protected $listeners = ['updateYear'];
    
    // Data posyandu dan kecamatan
    public $posyandus = [];
    public $kecamatans = [];

    // Data bulan dan tahun
    public $listMonths = [];
    public $listYears = [];
    public $nowYear = '';

    // Filter
    public $filterYear;

    public function mount(): void
    {
        Carbon::setLocale('id');
        $currentYear = now()->year;
        $this->listYears = collect(range($currentYear - 5, $currentYear))->toArray();
        $this->nowYear = now()->year;
        $this->filterYear = $this->nowYear;
    }

    public function updateYear($year)
    {
        $this->filterYear = $year;
        $this->render(); // Re-render the component to update the data
    }


    public function render()
    {
        $dataGizi = Type::with(['status_gizis' => function ($query) {
            $query->where('year', $this->filterYear);
        }])
            ->get()
            ->map(function ($type) {
                return [
                    'type' => $type->name,
                    'total' => $type->status_gizis->count(),
                    'dataPerMonth' => $type->status_gizis->groupBy('month')
                        ->map(function ($status_gizis) {
                            return $status_gizis->count();
                        })
                        ->toArray()
                ];
            })
            ->toArray();

        return view('livewire.frontend.pages.home-page', [
            'posyandus' => $this->posyandus,
            'kecamatans' => $this->kecamatans,
            'dataGizi' => $dataGizi,
            'listYears' => $this->listYears, // Ensure this is passed to the view
            'filterYear' => $this->filterYear, // Ensure this is passed to the view
        ])->layout('layouts.frontend.minimal', [
            'title' => 'Dashboard Gizi Anak',
            'page-title' => 'Grafik Status Gizi Anak',
            'page-description' => 'Visualisasi data status gizi anak Lombok Utara'
        ]);
    }
}