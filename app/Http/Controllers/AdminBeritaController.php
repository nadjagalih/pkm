<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Kategori;
use App\Models\PostStatus;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Cviebrock\EloquentSluggable\Services\SlugService;

class AdminBeritaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.berita.index', [
            'beritas'     => Berita::where('status_id', 2)->with(['user', 'status'])
                ->orderBy('id', 'DESC')->get(),
            'beritaDraft' => Berita::where('status_id', 1)->with(['user', 'status'])
                ->orderBy('id', 'DESC')->get(),       
        ]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.berita.create', [
            'postStatus'    => PostStatus::all(),
            'kategories'    => Kategori::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'gambar'        => 'required|mimes:jpeg,jpg,png',
            'judul'         => 'required|max:255',
            'slug'          => 'required|unique:beritas',
            'body'          => 'required',
            'kategori_id'   => 'required',
            'status_id'     => 'required'
        ],[
            'gambar.required'       => 'Wajib menambahkan gambar !',
            'gambar.mimes'          => 'Format gambar yang di izinkan Jpeg, Jpg, Png',
            'judul.required'        => 'Wajib menambahkan judul !',
            'slug.required'         => 'Wajib menambahkan slug !',
            'slug.unique'           => 'Slug sudah digunakan',
            'body.required'         => 'Wajib menambahkan isi berita !',
            'kategori_id.required'  => 'Wajib memilih kategori !',
            'status_id.required'    => 'Wajib memilih status berita !'
        ]);

        if($request->hasFile('gambar')){
            $path       = 'img-berita/';
            $file       = $request->file('gambar');
            $extension  = $file->getClientOriginalExtension(); 
            $fileName   = uniqid() . '.' . $extension; 
            $gambar     = $file->storeAs($path, $fileName, 'public');
        } else {
            $gambar     = null;
        }

        if ($validator->fails()) {
            return redirect('/admin/berita/create')
                ->withErrors($validator)
                ->withInput();
        }

        $berita = Berita::create([
            'judul'         =>  $request->judul,
            'slug'          =>  $request->slug,
            'body'          =>  $request->body,
            'gambar'        =>  $gambar,
            'excerpt'       =>  Str::limit(strip_tags($request->body), 100),
            'user_id'       =>  Auth::id(),
            'status_id'     =>  $request->status_id,
            'kategori_id'   =>  $request->kategori_id
        ]);

        return redirect('/admin/berita')->with('success', 'Berhasil menambahkan data berita');
    }


    /*
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $berita = Berita::find($id);
        return view('admin.berita.edit', [
            'berita'        => $berita,
            'kategories'    => Kategori::all(),
            'postStatus'    => PostStatus::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $berita = Berita::find($id);
        
        // Validasi dasar
        $rules = [
            'judul'         => 'required|max:255',
            'slug'          => 'required',
            'body'          => 'required',
            'kategori_id'   => 'required',
            'status_id'     => 'required'
        ];
        
        $messages = [
            'judul.required'        => 'Wajib menambahkan judul !',
            'slug.required'         => 'Wajib menambahkan slug !',
            'body.required'         => 'Wajib menambahkan isi berita !',
            'kategori_id.required'  => 'Wajib memilih kategori !',
            'status_id.required'    => 'Wajib memilih status berita !'
        ];

        // Validasi slug jika berubah
        if($request->slug != $berita->slug){
            $rules['slug'] = 'required|unique:beritas';
            $messages['slug.unique'] = 'Slug sudah digunakan';
        }

        // Validasi gambar jika ada upload baru
        if($request->hasFile('gambar')){
            $rules['gambar'] = 'required|mimes:jpeg,jpg,png';
            $messages['gambar.mimes'] = 'Format gambar yang di izinkan Jpeg, Jpg, Png';
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect("/admin/berita/{$berita->id}/edit")
                ->withErrors($validator)
                ->withInput();
        }

        // Handle upload gambar baru
        if($request->hasFile('gambar')){
            // Hapus gambar lama jika ada
            if($berita->gambar && Storage::disk('public')->exists($berita->gambar)){
                Storage::disk('public')->delete($berita->gambar);
            }
            
            $path       = 'img-berita/';
            $file       = $request->file('gambar');
            $extension  = $file->getClientOriginalExtension(); 
            $fileName   = uniqid() . '.' . $extension; 
            $gambar     = $file->storeAs($path, $fileName, 'public');
            
            // Log untuk debugging
            Log::info('Gambar baru diupload', [
                'path' => $gambar,
                'filename' => $fileName,
                'old_image' => $berita->gambar
            ]);
        } else {
            // Gunakan gambar lama jika tidak ada upload baru
            $gambar = $berita->gambar;
            
            Log::info('Menggunakan gambar lama', ['gambar' => $gambar]);
        }

        $berita->update([
            'judul'         => $request->judul,
            'slug'          => $request->slug,
            'body'          => $request->body,
            'gambar'        => $gambar,
            'excerpt'       => Str::limit(strip_tags($request->body), 100),
            'user_id'       => Auth::id(),
            'status_id'     => $request->status_id,
            'kategori_id'   => $request->kategori_id
        ]);

        return redirect('/admin/berita')->with('success', 'Berhasil memperbarui berita');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $berita = Berita::find($id);
        
        // Cek dan hapus gambar jika ada
        if($berita->gambar && Storage::disk('public')->exists($berita->gambar)){
            Storage::disk('public')->delete($berita->gambar);
        }
        
        $berita->delete();

        return redirect('/admin/berita')->with('success', 'Berhasil menghapus berita');
    }

    /**
     * Generate slug / permalink by Judul.
     */
    public function slug(Request $request)
    {
        $slug = SlugService::createSlug(Berita::class, 'slug', $request->judul);
        return response()->json(['slug' => $slug]);
    }
}
