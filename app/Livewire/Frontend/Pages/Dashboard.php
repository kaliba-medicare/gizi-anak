<?php

namespace App\Livewire\Frontend\Pages;

use App\Models\Desa;
use App\Models\Posyandu;
use App\Models\Type;
use App\Models\StatusGizi;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Http\Request;

class Dashboard extends Component
{
    // Data posyandu dan kecamatan
    public $posyandus = [];
    public $kecamatans = [];

    // Data bulan dan tahun
    public $listYears = [];
    public $nowYear = '';

    // Filter
    public $filterYear;
    public $filterMonth = 'all';
    public $filterCategory = 1; // Default to Stunting (type_id = 1)

    public function mount(Request $request): void
    {
        Carbon::setLocale('id');
        $currentYear = now()->year;
        $this->listYears = collect(range($currentYear - 5, $currentYear))->toArray();
        $this->nowYear = now()->year;
        
        // Find the latest data year and month for Stunting (type_id = 1)
        $latestData = StatusGizi::where('type_id', 1) // Stunting
            ->select('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->first();
            
        // Set defaults to latest available data
        if ($latestData) {
            $this->filterYear = $latestData->year;
            $this->filterMonth = $latestData->month;
        } else {
            // Fallback to current year if no data found
            $this->filterYear = $this->nowYear;
            $this->filterMonth = 1; // Default to January if no data
        }
        
        // Override with URL parameters if provided
        if ($request->query('year')) {
            $this->filterYear = $request->query('year');
        }
        
        if ($request->query('month')) {
            $this->filterMonth = $request->query('month');
        }
        
        if ($request->query('category')) {
            $this->filterCategory = $request->query('category');
        }
        
        // Validate year is in the list of available years
        if (!in_array($this->filterYear, $this->listYears)) {
            $this->filterYear = $latestData ? $latestData->year : $this->nowYear;
        }
        
        // Validate month is valid
        if ($this->filterMonth !== 'all' && !in_array($this->filterMonth, range(1, 12))) {
            $this->filterMonth = $latestData ? $latestData->month : 1;
        }
        
        // Validate category exists
        $validCategory = Type::find($this->filterCategory);
        if (!$validCategory) {
            $this->filterCategory = 1; // Default to Stunting
        }

        // Load posyandu data
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

        // Load kecamatan data
        $this->kecamatans = Desa::select('kecamatan')
            ->distinct()
            ->orderBy('kecamatan')
            ->pluck('kecamatan')
            ->toArray();
    }

    public function render()
    {
        // Data for charts - get all months for the selected year
        $chartDataGizi = Type::with(['status_gizis' => function ($query) {
            $query->where('year', $this->filterYear);
            // Do NOT apply month filter for charts - we want all months
        }])
            ->get()
            ->map(function ($type) {
                $statusGizis = $type->status_gizis;
                
                return [
                    'type' => $type->name,
                    'total' => $statusGizis->count(),
                    'dataPerMonth' => $statusGizis->groupBy('month')
                        ->map(function ($monthlyStatusGizis) {
                            return $monthlyStatusGizis->count();
                        })
                        ->toArray()
                ];
            })
            ->toArray();

        // Data for map - apply both year and month filters
        $mapDataGizi = Type::with(['status_gizis' => function ($query) {
            $query->where('year', $this->filterYear);
            
            // Apply month filter for map data
            if ($this->filterMonth !== 'all') {
                $query->where('month', $this->filterMonth);
            }
        }])
            ->get()
            ->map(function ($type) {
                $statusGizis = $type->status_gizis;
                
                return [
                    'type' => $type->name,
                    'total' => $statusGizis->count(),
                    'dataPerMonth' => $statusGizis->groupBy('month')
                        ->map(function ($monthlyStatusGizis) {
                            return $monthlyStatusGizis->count();
                        })
                        ->toArray()
                ];
            })
            ->toArray();

        // Get all categories for the filter
        $categories = Type::all();

        // Prepare bulan labels for the view
        $bulanLabels = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        return view('livewire.frontend.pages.dashboard', [
            'posyandus' => $this->posyandus,
            'kecamatans' => $this->kecamatans,
            'chartDataGizi' => $chartDataGizi,
            'mapDataGizi' => $mapDataGizi,
            'categories' => $categories,
            'listYears' => $this->listYears,
            'filterYear' => $this->filterYear,
            'filterMonth' => $this->filterMonth,
            'filterCategory' => $this->filterCategory,
            'bulanLabels' => $bulanLabels
        ])->layout('layouts.frontend.minimal', [
            'title' => 'Dashboard',
            'page-title' => 'Dashboard Status Gizi Anak',
            'page-description' => 'Visualisasi komprehensif data gizi anak Lombok Utara'
        ]);
    }
}