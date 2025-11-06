@extends('admin.layouts.main')

@section('content')
<div class="row">
    <div class="col-lg-12 d-flex align-items-strech">
      <div class="card w-100">
        <div class="card-header bg-primary">
            <div class="row align-items-center">
                <div class="col-6">
                    <h5 class="card-title fw-semibold text-white">Edit Berita</h5>
                </div>
                <div class="col-6 text-right">
                    <a href="/admin/berita" type="button" class="btn btn-warning float-end">Kembali</a>
                </div>
            </div>
        </div>
      </div>
    </div>
</div>

<div class="row">
    <form method="POST" action="/admin/berita/{{ $berita->id }}" enctype="multipart/form-data">
        @method('put')
        @csrf

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="judul" class="form-label">Judul <span style="color: red">*</span></label>
                            <input type="text" class="form-control" name="judul" id="judul" value="{{ old('judul', $berita->judul) }}">
                            @error('judul')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="slug" class="form-label">Slug/Permalink <span style="color: red">*</span></label>
                            <input type="text" class="form-control" name="slug" id="slug" value="{{ old('slug', $berita->slug) }}">
                            @error('slug')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="body" class="form-label">Isi Berita <span style="color: red">*</span></label>
                            <textarea class="form-control" id="editor" name="body" rows="10">{{ old('body', $berita->body) }}</textarea>
                            @error('body')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
               <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="gambar" class="form-label">Gambar Slider <span style="color: red">*</span></label>
                            @if($berita->gambar && file_exists(public_path('storage/' . $berita->gambar)))
                                <img src="{{ asset('storage/' . $berita->gambar) }}" class="img-preview img-fluid mb-3 d-block" id="preview" style="border-radius: 5px; max-height:300px; width: 100%; object-fit: cover;">
                            @else
                                <img class="img-preview img-fluid mb-3 d-block" id="preview" style="border-radius: 5px; max-height:300px; width: 100%; object-fit: cover; display: none;">
                                @if($berita->gambar && !file_exists(public_path('storage/' . $berita->gambar)))
                                    <div class="alert alert-warning mb-3">
                                        <small><i class="ti ti-alert-circle"></i> Gambar sebelumnya tidak ditemukan. Silakan upload gambar baru.</small>
                                    </div>
                                @endif
                            @endif
                            <input class="form-control" type="file" id="gambar" name="gambar" onchange="previewImage()" accept="image/jpeg,image/jpg,image/png">
                            <small class="text-muted">Format: JPEG, JPG, PNG</small>
                            @error('gambar')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="kategori" class="form-label">Kategori Berita <span style="color: red">*</span></label>
                            <select class="form-control" name="kategori_id" id="kategori_id">
                                <option value=""> -- Pilih Kategori -- </option>
                                @foreach ($kategories as $kategori)
                                    @if (old('kategori', $kategori->kategori) == $kategori->kategori)
                                        <option value="{{ $kategori->id }}" selected>{{ $kategori->kategori }}</option>
                                    @else
                                        <option value="{{ $kategori->id }}">{{ $kategori->kategori }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('kategori_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status Berita <span style="color: red">*</span></label>
                            <select class="form-control" name="status_id" id="status_id">
                                <option value=""> -- Pilih Status -- </option>
                                @foreach ($postStatus as $status)
                                    @if (old('status', $status->status) == $status->status)
                                        <option value="{{ $status->id }}" selected>{{ $status->status }}</option>
                                    @else
                                        <option value="{{ $status->id }}">{{ $status->status }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('status_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary m-1 float-end">Update</button>
                    </div>
               </div>
            </div>
        </div>
    </form>
</div>

<!-- Generate Slug Otomatis -->
<script>
    const judul     = document.querySelector('#judul');
    const slug      = document.querySelector('#slug');

    judul.addEventListener('change', function(){
        fetch('/admin/berita/slug?judul=' + judul.value)
            .then(response => response.json())
            .then(data => slug.value = data.slug)
    });
</script>

<!-- Preview Image -->
<script>
    function previewImage(){
        const preview   = document.getElementById('preview');
        const fileInput = document.getElementById('gambar');
        const file      = fileInput.files[0];
        
        if(file){
            // Validasi tipe file
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            if(!validTypes.includes(file.type)){
                alert('Format file harus JPEG, JPG, atau PNG');
                fileInput.value = '';
                return;
            }
            
            // Validasi ukuran file (max 2MB)
            const maxSize = 2 * 1024 * 1024; // 2MB
            if(file.size > maxSize){
                alert('Ukuran file maksimal 2MB');
                fileInput.value = '';
                return;
            }
            
            const reader = new FileReader();
            
            reader.onload = function(e){
                preview.src = e.target.result;
                preview.style.display = 'block';
                console.log('Preview image loaded successfully');
            };
            
            reader.onerror = function(e){
                console.error('Error reading file:', e);
                alert('Gagal membaca file gambar');
            };
            
            reader.readAsDataURL(file);
        }
    }
</script>

<!-- Ck Editor 5 -->
<script>
    let editorInstance;
    ClassicEditor
        .create( document.querySelector( '#editor' ) )
        .then( editor => {
             editorInstance =editor;
        } )
        .catch( error => {
            console.error( error );
        } );
</script>

@endsection