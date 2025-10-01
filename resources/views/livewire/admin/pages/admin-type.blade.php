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
                            <h4 class="mb-3">{{ $typeId ? 'Edit Status Gizi' : 'Tambah Status Gizi' }}</h4>
                            <form wire:submit.prevent="save" class="bg-light p-4">
                                <div class="mb-3">
                                    <label for="name">Status Gizi</label>
                                    <input type="text" wire:model.lazy="name" class="form-control" id="name">
                                    @error('name') 
                                        <small class="text-danger">{{ $message }}</small> 
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary mt-3">{{ $typeId ? 'Ubah' : 'Simpan' }}</button>
                                <button type="button" wire:click="resetForm" class="btn btn-secondary mt-3">Reset</button>
                            </form>

                        </div>
                        
                    </div>
                    
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <input type="text" class="form-control"
                        placeholder="Cari Type Gizi"
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
                                        <th>Status Gizi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($typeGizis as $index => $item)
                                        <tr>
                                             <td>{{ $typeGizis->firstItem() + $index }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>
                                                <button wire:click="edit({{ $item->id }})" class="btn btn-sm btn-warning">Edit</button>
                                                <button wire:click="delete({{ $item->id }})" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">Hapus</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            
                            {{ $typeGizis->links() }}
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
