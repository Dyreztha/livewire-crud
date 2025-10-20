<div>
    <div class="container">
        <div class="my-3 p-3 bg-body rounded shadow-sm">
            @if (session()->has('message'))
                <div class="pt-3">
                    <div class="alert alert-success">
                        {{ session('message') }}
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="pt-3">
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form>
                <div class="mb-3 row">
                    <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" wire:model="nama">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="email" class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control" wire:model="email">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" wire:model="alamat">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-10">
                        @if ($updateMode)
                            <button type="button" class="btn btn-primary" wire:click="update">UPDATE</button>
                            <button type="button" class="btn btn-secondary" wire:click="cancel">BATAL</button>
                        @else
                            <button type="button" class="btn btn-primary" wire:click="store">SIMPAN</button>
                            <button type="button" class="btn btn-secondary" wire:click="clear">Clear</button>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <div class="my-3 p-3 bg-body rounded shadow-sm">
            <h1>Data Pegawai</h1>
            
            <div class="pb-3">
                @if (count($employeeSelectedId))
                    <button class="btn btn-danger mb-3" wire:click="deleteConfirmation('')">
                        Del {{ count($employeeSelectedId) }} Data
                    </button>
                @endif
                <input type="text" class="form-control" placeholder="Cari data..." wire:model.live="search">
            </div>
            
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th></th>
                        <th class="col-md-1">No</th>
                        <th class="col-md-3" wire:click="sortBy('nama')" style="cursor: pointer;">
                            Nama 
                            @if ($sortColumn == 'nama')
                                @if ($sortDirection == 'ASC')
                                    ▲
                                @else
                                    ▼
                                @endif
                            @endif
                        </th>
                        <th class="col-md-3" wire:click="sortBy('email')" style="cursor: pointer;">
                            Email
                            @if ($sortColumn == 'email')
                                @if ($sortDirection == 'ASC')
                                    ▲
                                @else
                                    ▼
                                @endif
                            @endif
                        </th>
                        <th class="col-md-2" wire:click="sortBy('alamat')" style="cursor: pointer;">
                            Alamat
                            @if ($sortColumn == 'alamat')
                                @if ($sortDirection == 'ASC')
                                    ▲
                                @else
                                    ▼
                                @endif
                            @endif
                        </th>
                        <th class="col-md-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $no = ($employees->currentPage() - 1) * $employees->perPage() + 1;
                    @endphp
                    @foreach ($employees as $employee)
                    <tr>
                        <td>
                            <input type="checkbox" wire:model.live="employeeSelectedId" value="{{ $employee->id }}">
                        </td>
                        <td>{{ $no++ }}</td>
                        <td>{{ $employee->nama }}</td>
                        <td>{{ $employee->email }}</td>
                        <td>{{ $employee->alamat }}</td>
                        <td>
                            <button class="btn btn-warning btn-sm" wire:click="edit({{ $employee->id }})">Edit</button>
                            <button class="btn btn-danger btn-sm" wire:click="deleteConfirmation({{ $employee->id }})">Del</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-3">
                {{ $employees->links() }}
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('show-delete-confirmation', event => {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('delete');
                }
            });
        });
    </script>
</div>
