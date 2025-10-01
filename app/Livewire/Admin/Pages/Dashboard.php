<?php

namespace App\Livewire\Admin\Pages;

use App\Models\Desa;
use App\Models\Posyandu;
use App\Models\Type;
use Livewire\Attributes\Title;
use Livewire\Component;

class Dashboard extends Component
{
    #[Title('Dashboard | Gizi Balita Lombok Utara')]
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
        return view('livewire.admin.pages.dashboard', [
            'posyandus' => $this->posyandus,
            'kecamatans' => $this->kecamatans,
            'dataGizi' => $this->dataGizi,
        ]);
    }
}