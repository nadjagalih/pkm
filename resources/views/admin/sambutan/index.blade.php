@extends('admin.layouts.app')

@section('title', 'Kelola Sambutan Kepala Puskesmas')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h5 class="card-title fw-semibold text-white mb-0">Sambutan Kepala Puskesmas</h5>
                        </div>
                        <div class="col-6 text-right">
                            <a href="{{ url('/sambutan') }}" target="_blank" class="btn btn-warning float-end">Live Preview</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="mb-3">
                        <a href="{{ route('sambutan.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Sambutan
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="15%">Foto</th>
                                    <th width="25%">Nama Kepala</th>
                                    <th width="20%">Jabatan</th>
                                    <th width="15%">Tanggal Dibuat</th>
                                    <th width="20%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sambutan as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            @if($item->foto)
                                                <img src="{{ asset('storage/' . $item->foto) }}" 
                                                     alt="{{ $item->nama }}" 
                                                     class="img-thumbnail" 
                                                     style="max-width: 100px;">
                                            @else
                                                <span class="badge badge-secondary">Tidak ada foto</span>
                                            @endif
                                        </td>
                                        <td>{{ $item->nama }}</td>
                                        <td>{{ $item->jabatan }}</td>
                                        <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <a href="{{ route('sambutan.edit', $item->id) }}" 
                                               class="btn btn-warning mb-1">
                                                <i class="ti ti-edit"></i>
                                            </a>
                                            <form action="{{ route('sambutan.destroy', $item->id) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Yakin ingin menghapus sambutan ini?')">
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
                                        <td colspan="6" class="text-center">Belum ada data sambutan</td>
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
