@extends('admin.layouts.app')

@section('title', 'Kelola Alur Pelayanan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Kelola Alur Pelayanan</h3>
                    <div class="card-tools">
                        <a href="{{ route('alur-pelayanan.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Tambah Alur Pelayanan
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="10%">Urutan</th>
                                    <th width="20%">Judul</th>
                                    <th width="35%">Deskripsi</th>
                                    <th width="10%">Icon</th>
                                    <th width="10%">Status</th>
                                    <th width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($alurPelayanans as $index => $alur)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td><span class="badge badge-primary">Langkah {{ $alur->urutan }}</span></td>
                                        <td>{{ $alur->judul }}</td>
                                        <td>{{ Str::limit($alur->deskripsi, 100) }}</td>
                                        <td>
                                            @if($alur->icon)
                                                <img src="{{ asset('storage/' . $alur->icon) }}" 
                                                     alt="{{ $alur->judul }}" 
                                                     class="img-thumbnail" 
                                                     style="max-width: 50px;">
                                            @else
                                                <span class="badge badge-secondary">Tidak ada</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($alur->status == 'Aktif')
                                                <span class="badge badge-success">Aktif</span>
                                            @else
                                                <span class="badge badge-secondary">Non-Aktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('alur-pelayanan.edit', $alur->id) }}" 
                                               class="btn btn-warning mb-1">
                                                <i class="ti ti-edit"></i>
                                            </a>
                                            <form action="{{ route('alur-pelayanan.destroy', $alur->id) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Yakin ingin menghapus alur pelayanan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger mb-1">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Belum ada data alur pelayanan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
