<div class="container-fluid">
    <h3>Data Posyandu</h3>

    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card mb-3">
        <div class="card-body">
            <form wire:submit.prevent="import" enctype="multipart/form-data">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <input type="file" wire:model="file" class="form-control" />
                    <button type="submit" class="btn btn-primary btn-md" wire:target="import" wire:loading.attr="disabled">
                        <div class="d-flex align-items-center">
                            <span wire:loading wire:target="import" class="spinner-border spinner-border-sm me-2" role="status"></span>
                            <span>Import</span>
                        </div>
                    </button>
                </div>
                @error('file') <span class="text-danger">{{ $message }}</span> @enderror
            </form>
        </div>
    </div>

    <!-- Search Box -->
    <div class="card mb-3">
        <div class="card-body">
            <input type="text" class="form-control"
                   placeholder="Cari nama dusun, atau nama posyandu, nama desa..."
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
                            <th>Nama Dusun</th>
                            <th>Nama Posyandu</th>
                            <th>Nama Desa</th>
                            <th>Latlong</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dataPosyandu as $index => $item)
                            <tr>
                                <td>{{ $dataPosyandu->firstItem() + $index }}</td>
                                <td>{{ $item->nama_dusun }}</td>
                                <td>{{ $item->nama_posyandu }}</td>
                                <td>{{ $item->nama_desa }}</td>
                                <td>{{ $item->latlong }}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning" wire:click="edit({{ $item->id }})" data-bs-toggle="modal" data-bs-target="#editModal">
                                        Edit
                                    </button>
                                    <button class="btn btn-sm btn-danger" wire:click="confirmDelete({{ $item->id }})" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                        Hapus
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

            {{ $dataPosyandu->links() }}
        </div>
    </div>
    <!-- Modal Edit -->
    <div wire:ignore.self class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <form wire:submit.prevent="update">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title">Edit Posyandu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                <div class="mb-2">
                    <label>Nama Dusun</label>
                    <input type="text" wire:model="nama_dusun" class="form-control">
                    @error('nama_dusun') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="mb-2">
                    <label>Nama Posyandu</label>
                    <input type="text" wire:model="nama_posyandu" class="form-control">
                    @error('nama_posyandu') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="mb-2">
                    <label>Nama Desa</label>
                    <select wire:model="nama_desa" class="form-select">
                        <option value="">-- Pilih Desa --</option>
                        @foreach ($listDesa as $desa)
                            <option value="{{ $desa->id }}" {{ $desa->nama_desa == $nama_desa ? 'selected' : '' }} >{{ $desa->nama_desa }}</option>
                        @endforeach
                    </select>
                    @error('nama_desa') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="mb-2">
                    <label>Latlong</label>
                    <input type="text" wire:model="latlong" class="form-control">
                    @error('latlong') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </div>
            </form>
        </div>
        </div>
        <!-- Modal Delete -->
        <div wire:ignore.self class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus data ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" wire:click="delete" data-bs-dismiss="modal">Hapus</button>
            </div>
            </div>
        </div>
        </div>


</div>