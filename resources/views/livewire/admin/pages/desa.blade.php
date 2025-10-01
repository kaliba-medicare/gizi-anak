@push('styles')
    <link href="/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css">
@endpush
@push('scripts')
    <script src="/assets/libs/select2/js/select2.min.js"></script>
@endpush
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            
            <div class="card">
                <div class="card-body">

                    @if (session()->has('message'))
                        <div class="alert alert-success">{{ session('message') }}</div>
                    @endif
                    <div class="row">
                        <div class="col-md-12  " >
                            <h4 class="mb-3">{{ $desaId ? 'Edit Desa' : 'Tambah Desa' }}</h4>
                            <form wire:submit.prevent="save" class="bg-light p-4">
                                <div class="mb-3">
                                    <label for="nama_desa">Nama Desa</label>
                                    <input type="text" wire:model.lazy="nama_desa" class="form-control" id="nama_desa">
                                    @error('nama_desa') 
                                        <small class="text-danger">{{ $message }}</small> 
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="kecamatan">Kecamatan</label>
                                    <select wire:model="kecamatan" class="form-control" id="kecamatan">
                                        <option value="">-- Pilih Kecamatan --</option>
                                        <option value="BAYAN">BAYAN</option>
                                        <option value="KAYANGAN">KAYANGAN</option>
                                        <option value="GANGGA">GANGGA</option>
                                        <option value="TANJUNG">TANJUNG</option>
                                        <option value="PEMENANG">PEMENANG</option>
                                    </select>
                                    @error('kecamatan') 
                                        <small class="text-danger">{{ $message }}</small> 
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary mt-3">{{ $desaId ? 'Ubah' : 'Simpan' }}</button>
                                <button type="button" wire:click="resetForm" class="btn btn-secondary mt-3">Reset</button>
                            </form>

                        </div>
                        
                    </div>
                    
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <input type="text" class="form-control"
                        placeholder="Cari nama nama desa, atau nama kecamatan..."
                        wire:model.live.debounce.500ms="search"
                    />
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="col-md-12 mt-4">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Desa</th>
                                        <th>Kecamatan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($desas as $index => $desa)
                                        <tr>
                                             <td>{{ $desas->firstItem() + $index }}</td>
                                            <td>{{ $desa->nama_desa }}</td>
                                            <td>{{ $desa->kecamatan }}</td>
                                            <td>
                                                <button wire:click="edit({{ $desa->id }})" class="btn btn-sm btn-warning">Edit</button>
                                                <button wire:click="delete({{ $desa->id }})" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">Hapus</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            
                            {{ $desas->links() }}
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
