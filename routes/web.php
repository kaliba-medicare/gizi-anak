<?php

use App\Http\Controllers\Data\GetDataGiziController;
use App\Livewire\Admin\Auth\LoginPage;
use App\Livewire\Admin\Pages\AdminPosyanduImport;
use App\Livewire\Admin\Pages\AdminType;
use App\Livewire\Admin\Pages\Dashboard;
use App\Livewire\Admin\Pages\Desa;
use App\Livewire\Admin\Pages\StatusGiziImport;
use App\Livewire\Frontend\Pages\FrontMapDesa;
use App\Livewire\Frontend\Pages\HomePage;
use App\Livewire\Frontend\Pages\Dashboard as FrontendDashboard;
use App\Models\Posyandu;
use App\Models\StatusGizi;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

// Admin Authentication Routes (Livewire Only)
Route::middleware('guest')->group(function () {
    Route::get('/admin/login', LoginPage::class)->name('admin.login');
});

// Admin Logout Route
Route::post('/admin/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('admin.login')->with('success', 'You have been logged out successfully.');
})->middleware('auth')->name('admin.logout');

/*
|--------------------------------------------------------------------------
| Admin Protected Routes
|--------------------------------------------------------------------------
*/

// Admin Management Routes (Protected by admin middleware)
Route::prefix('admin_management')
    ->middleware(['auth', 'admin'])
    ->group(function () {
        Route::get('/', function () {
            return redirect()->route('admin.dashboard');
        });
        Route::get('/dashboard', Dashboard::class)->name('admin.dashboard');
        Route::get('/desa', Desa::class)->name('admin.data_desa');
        Route::get('/posyandu', AdminPosyanduImport::class)->name('admin.data_posyandu');
        Route::get('/status_gizi', StatusGiziImport::class)->name('admin.status_gizi');
        Route::get('/type', AdminType::class)->name('admin.type');
    });

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Make the dashboard the root page
Route::get('/', FrontendDashboard::class)->name('home');
Route::get('/grafik', HomePage::class)->name('grafik');
Route::get('/peta', FrontMapDesa::class)->name('map.desa');
Route::get('/dashboard', FrontendDashboard::class)->name('frontend.dashboard');

Route::get('/getAllCountStunting', [GetDataGiziController::class, 'getAllCountStunting']);
Route::get('/getAllCountGiziForDesa', [GetDataGiziController::class, 'getAllCountGiziForDesa']);

Route::get('to-excel', function () {
    // Ambil data dari sumber kedua (Database Posyandu)
    $daftarPosyanduDatabase = Posyandu::join('desas', 'posyandus.desa_id', '=', 'desas.id')
        ->select('posyandus.nama_posyandu as posyandu', 'desas.nama_desa as desa')
        ->orderBy('posyandus.nama_posyandu', 'asc')
        ->get()
        ->map(function ($item) {
            return [
                'desa' => strtoupper($item->desa),
                'posyandu' => $item->posyandu,
                'sumber' => 'database',
            ];
        });
    // Ambil data dari sumber pertama (Status Gizi)
    $daftarPosyanduSigiziKesga = StatusGizi::select('posyandu', 'desa_kel')
        ->distinct()
        ->orderBy('posyandu', 'asc')
        ->get()
        ->map(function ($item) {
            return [
                'desa' => strtoupper($item->desa_kel),
                'posyandu' => $item->posyandu,
                'sumber' => 'sigizikesga',
            ];
        });

    

    // Gabungkan dan hilangkan duplikat berdasarkan desa + posyandu + sumber
    $gabungan = $daftarPosyanduDatabase
        ->concat($daftarPosyanduSigiziKesga)
        ->unique(function ($item) {
            return strtoupper($item['desa']) . '|' . strtoupper($item['posyandu']) . '|' . $item['sumber'];
        });


    // Kelompokkan berdasarkan desa
    $dataGizi_posyandu = $gabungan->groupBy('desa');
    return view('to-excel', compact('dataGizi_posyandu'));

        // $html = view('to-excel', compact('dataGizi_posyandu'))->render();
        // return response($html)
        //     ->header('Content-Type', 'application/vnd.ms-excel')
        //     ->header('Content-Disposition', 'attachment; filename="Daftar_Posyandu_berdasarkan_data_sigizikesga.xls"');

})->name('to-excel');