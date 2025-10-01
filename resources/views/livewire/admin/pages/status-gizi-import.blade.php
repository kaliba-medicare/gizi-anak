<div class="container-fluid">
    <h3>Import Status Gizi</h3>

    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card mb-3">
        <div class="card-body">
            <form wire:submit.prevent="import" enctype="multipart/form-data">
                <div class="row g-3 mb-3">
                    <div class="col-md-3">
                        <label for="type_id" class="form-label">Jenis Gizi</label>
                        <select  id="type_id" class="form-control w-100" wire:model="type_id" required>
                            <option value="">-- Pilih Jenis Gizi --</option>
                            @foreach ($typeGizis as $item)
                                <option value="{{ $item->id }}" >{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label for="month" class="form-label">Bulan</label>
                        <select id="month" wire:model="month" class="form-control w-100">
                            <option value="">-- Pilih Bulan --</option>
                            @foreach ($listMonths as $key => $month)
                                <option value="{{ $key }}" {{ $key == $nowMonth ? 'selected' : '' }}>{{ $month }}</option>
                            @endforeach
                        </select>
                        @error('month')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-2">
                        <label for="year" class="form-label">Tahun</label>
                        <select id="year"  wire:model="year" class="form-control w-100">
                            <option value="">-- Pilih Tahun --</option>
                            @foreach ($listYears as $year)
                                <option value="{{ $year }}" {{ $year == $nowYear ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                        @error('year')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="file" class="form-label">File Excel</label>
                        <input type="file" id="file" wire:model="file" class="form-control w-100" />
                        @error('file')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100" wire:target="import" wire:loading.attr="disabled">
                            <span wire:loading wire:target="import" class="spinner-border spinner-border-sm me-2" role="status"></span>
                            <span>Import</span>
                        </button>
                    </div>
                </div>

                
            </form>
        </div>
    </div>

    <div class="card bg-secondary">
        <div class="card-body">
            <h5 class="text-center ">FILTER DATA</h5>
            <form action="" wire:submit.prevent="filter">
                <div class="row g-3 mb-3 justify-content-center ">
                    <div class="col-md-3">
                        <select id="filterType_id" class="form-control w-100" wire:model="filterType_id" required>
                            <option value="">-- Pilih Jenis Gizi --</option>
                            @foreach ($typeGizis as $item)
                                <option value="{{ $item->id }}" >{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select id="filterMonth" wire:model="filterMonth" class="form-control w-100">
                            <option value="">-- Pilih Bulan --</option>
                            @foreach ($listMonths as $key => $month)
                                <option value="{{ $key }}" {{ $key == $nowMonth ? 'selected' : '' }}>{{ $month }}</option>
                            @endforeach
                        </select>
                        @error('month')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-2">
                        <select id="filterYear"  wire:model="filterYear" class="form-control w-100">
                            <option value="">-- Pilih Tahun --</option>
                            @foreach ($listYears as $year)
                                <option value="{{ $year }}" {{ $year == $nowYear ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                        @error('year')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-2 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary w-100" wire:target="filter" wire:loading.attr="disabled">
                            <span wire:loading wire:target="filter" class="spinner-border spinner-border-sm me-2" role="status"></span>
                            <span>Filter</span>
                        </button>
                        <button type="button" class="btn btn-danger w-100" wire:click="confirmDeleteFiltered">
                            <span wire:loading wire:target="deleteFiltered" class="spinner-border spinner-border-sm me-2" role="status"></span>
                            <span>Hapus</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Search Box -->
    <div class="card bg-secondary p-2">
        <div class="card mb-3">
            <div class="card-body">
                <input type="text" class="form-control"
                    placeholder="Cari nama, NIK, atau nama ortu..."
                    wire:model.live.debounce.500ms="search"
                />
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-responsive">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIK</th>
                                <th>Nama</th>
                                <th>Jenis Kelamin</th>
                                <th>Tanggal Lahir</th>
                                <th>Status Gizi</th>
                                <th>Data Bulan/Tahun</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($statusGizis as $index => $item)
                                <tr>
                                    <td>{{ $statusGizis->firstItem() + $index }}</td>
                                    <td>{{ substr($item->nik, 0, 6) . str_repeat('*', strlen($item->nik) - 6) }}</td>
                                    <td>{{ $item->nama }}</td>
                                    <td>{{ $item->jk }}</td>
                                    <td>{{ $item->tgl_lahir }}</td>
                                    <td>{{ $item->type->name }}</td>
                                    <td>{{ \Carbon\Carbon::create($item->year, $item->month, 1)->translatedFormat('F Y') }}</td>
                                    <td>
                                        <button wire:click="showDetail({{ $item->id }})" class="btn btn-info btn-sm">
                                            Detail
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Data tidak ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $statusGizis->links() }}
            </div>
        </div>
    </div>

    <!-- Modal Detail -->
    @if ($selectedData)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Status Gizi - {{ $selectedData->nama }}</h5>
                        <button type="button" class="btn-close" wire:click="closeDetail"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-sm table-bordered">
                            <tbody>
                                <tr><th>NIK</th><td>{{ substr($selectedData->nik, 0, 6) . str_repeat('*', strlen($selectedData->nik) - 6) }}</td></tr>
                                <tr><th>Nama</th><td>{{ $selectedData->nama }}</td></tr>
                                <tr><th>Jenis Kelamin</th><td>{{ $selectedData->jk }}</td></tr>
                                <tr><th>Tanggal Lahir</th><td>{{ $selectedData->tgl_lahir }}</td></tr>
                                <tr><th>BB Lahir</th><td>{{ $selectedData->bb_lahir }} kg</td></tr>
                                <tr><th>TB Lahir</th><td>{{ $selectedData->tb_lahir }} cm</td></tr>
                                {{-- <tr><th>BB Lahir</th><td>{{ number_format($selectedData->bb_lahir, 2) }} kg</td></tr>
                                <tr><th>TB Lahir</th><td>{{ number_format($selectedData->tb_lahir, 2) }} cm</td></tr> --}}
                                <tr><th>Nama Ortu</th><td>{{ $selectedData->nama_ortu }}</td></tr>
                                <tr><th>Provinsi</th><td>{{ $selectedData->prov }}</td></tr>
                                <tr><th>Kab/Kota</th><td>{{ $selectedData->kab_kota }}</td></tr>
                                <tr><th>Kecamatan</th><td>{{ $selectedData->kec }}</td></tr>
                                <tr><th>Puskesmas</th><td>{{ $selectedData->puskesmas }}</td></tr>
                                <tr><th>Desa/Kel</th><td>{{ $selectedData->desa_kel }}</td></tr>
                                <tr><th>Posyandu</th><td>{{ $selectedData->posyandu }}</td></tr>
                                <tr><th>RT</th><td>{{ $selectedData->rt }}</td></tr>
                                <tr><th>RW</th><td>{{ $selectedData->rw }}</td></tr>
                                <tr><th>Alamat</th><td>{{ $selectedData->alamat }}</td></tr>
                                <tr>
                                    <th>Usia Saat Ukur</th>
                                    <td>
                                        {{ $selectedData->usia_saat_ukur }}
                                        @if (!empty($selectedData->usia_dalam_hari))
                                            ({{ $selectedData->usia_dalam_hari }} hari)
                                        @endif
                                    </td>
                                </tr>
                                <tr><th>Tanggal Pengukuran</th><td>{{ $selectedData->tanggal_pengukuran }}</td></tr>
                                {{-- <tr><th>Berat</th><td>{{ number_format($selectedData->berat, 2) }} kg</td></tr>
                                <tr><th>Tinggi</th><td>{{ number_format($selectedData->tinggi, 2) }} cm</td></tr> --}}
                                <tr><th>Berat</th><td>{{ $selectedData->berat }} kg</td></tr>
                                <tr><th>Tinggi</th><td>{{ $selectedData->tinggi }} cm</td></tr>
                                <tr><th>Cara Ukur</th><td>{{ $selectedData->cara_ukur }}</td></tr>
                                <tr><th>LILA</th><td>{{ $selectedData->lila }}</td></tr>
                                <tr><th>BB/U</th><td>{{ $selectedData->bb_u }} </td></tr>
                                <tr><th>ZS BB/U</th><td>{{ $selectedData->zs_bb_u }}</td></tr>
                                <tr><th>TB/U</th><td>{{ $selectedData->tb_u }} </td></tr>
                                <tr><th>ZS TB/U</th><td>{{ $selectedData->zs_tb_u }}</td></tr>
                                <tr><th>BB/TB</th><td>{{ $selectedData->bb_tb }}</td></tr>
                                <tr><th>ZS BB/TB</th><td>{{ $selectedData->zs_bb_tb }}</td></tr>
                                <tr><th>Naik Berat Badan</th><td>{{ $selectedData->naik_berat_badan }}</td></tr>
                                <tr><th>Jml Vit A</th><td>{{ $selectedData->jml_vit_a }}</td></tr>
                                <tr><th>KPSP</th><td>{{ $selectedData->kpsp }}</td></tr>
                                <tr><th>KIA</th><td>{{ $selectedData->kia }}</td></tr>
                                <tr><th>Detail</th><td>{{ $selectedData->detail }}</td></tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button wire:click="closeDetail" class="btn btn-secondary">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
    <script>
        window.addEventListener('confirm-delete', event => {
            if (confirm('Apakah Anda yakin ingin menghapus data yang terfilter?')) {
                @this.deleteFiltered();
            }
        });
    </script>
    
@endpush