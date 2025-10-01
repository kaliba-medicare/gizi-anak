<?php

namespace App\Livewire\Frontend\Pages;

use App\Models\Desa;
use App\Models\Posyandu;
use App\Models\StatusGizi;
use App\Models\Type;
use Carbon\Carbon;
use Livewire\Component;

class FrontMapDesa extends Component
{
    // Filter
    public $filterYear;
    public $posyandus = [];
    public $kecamatans = [];
    public $dataGizi_posyandu = [];
    public $dataPosyandu= [];

    public function mount(){
        $this->dataGizi_posyandu =StatusGizi::select('posyandu')->distinct()->orderBy('posyandu','asc')
            ->get();
        $this->dataPosyandu = Posyandu::select('nama_posyandu')->orderby('nama_posyandu','asc')->get();
            

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
        return view('livewire.frontend.pages.front-map-desa',[
            'posyandus' => $this->posyandus,
            'kecamatans' => $this->kecamatans,
            'dataGizi_posyandu' => $this->dataGizi_posyandu,
            'dataPosyandu' => $this->dataPosyandu,
        ])->layout('layouts.frontend.app-front');
    }
}
