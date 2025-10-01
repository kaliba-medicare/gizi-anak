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
    // protected $listeners = ['updateYear'];
    
    // // Data posyandu dan kecamatan
    // public $posyandus = [];
    // public $kecamatans = [];

    // // Data bulan dan tahun
    // public $listMonths = [];
    // public $listYears = [];
    // public $nowYear = '';

    // // Filter
    // public $filterYear;

    // public function mount(): void
    // {
    //     Carbon::setLocale('id');
    //     $currentYear = now()->year;
    //     $this->listYears = collect(range($currentYear - 5, $currentYear))->toArray();
    //     $this->nowYear = now()->year;
    //     $this->filterYear = $this->nowYear;
    // }

    // public function updateYear($year)
    // {
    //     $this->filterYear = $year;
    //     $this->render(); // Re-render the component to update the data
    // }


    // public function render()
    // {
    //     $dataGizi = Type::with(['status_gizis' => function ($query) {
    //         $query->where('year', $this->filterYear);
    //     }])
    //         ->get()
    //         ->map(function ($type) {
    //             return [
    //                 'type' => $type->name,
    //                 'total' => $type->status_gizis->count(),
    //                 'dataPerMonth' => $type->status_gizis->groupBy('month')
    //                     ->map(function ($status_gizis) {
    //                         return $status_gizis->count();
    //                     })
    //                     ->toArray()
    //             ];
    //         })
    //         ->toArray();

    //     return view('livewire.frontend.pages.home-page', [
    //         'posyandus' => $this->posyandus,
    //         'kecamatans' => $this->kecamatans,
    //         'dataGizi' => $dataGizi,
    //         'listYears' => $this->listYears, // Ensure this is passed to the view
    //         'filterYear' => $this->filterYear, // Ensure this is passed to the view
    //     ])->layout('layouts.frontend.app-front');
    // }

    #[Title('Gizi Balita Lombok Utara')]
    public $posyandus = [];
    public $kecamatans = [];
    public $dataGizi = [];

    public function mount(): void
    {
        // GRAFIK
        $this->dataGizi = Type::with('status_gizis')->get()
            ->map(function ($type) {
                return [
                    'type' => $type->name,
                    'total' => $type->status_gizis->count(),
                    'dataPerMonth' => $type->status_gizis->groupBy('month')
                        ->map(function ($status_gizis) {
                            return $status_gizis->count();
                        })
                ];
            })->toArray();
        //MAP
        $this->posyandus = Posyandu::with('desa')
            ->whereNotNull('latlong')
            ->get()
            ->map(function ($posyandu) {
                return [
                    'id' => $posyandu->id,
                    'nama_posyandu' => $posyandu->nama_posyandu,
                    'nama_dusun' => $posyandu->nama_dusun,
                    'nama_desa' => $posyandu->desa->nama_desa,
                    'kecamatan' => $posyandu->desa->kecamatan,
                    'latlong' => $posyandu->latlong
                ];
            })
            ->toArray();

        $this->kecamatans = Desa::select('kecamatan')
            ->distinct()
            ->orderBy('kecamatan')
            ->pluck('kecamatan')
            ->toArray();
    }

    public function render()
    {
        // dd($this->dataGizi);
        return view('livewire.frontend.pages.home-page', [
            'posyandus' => $this->posyandus,
            'kecamatans' => $this->kecamatans,
            'dataGizi' => $this->dataGizi,
        ])->layout('layouts.frontend.app-front');
    }
}
