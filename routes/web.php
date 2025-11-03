<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\KontakController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\SejarahController;
use App\Http\Controllers\AnggaranController;
use App\Http\Controllers\kategoriController;
use App\Http\Controllers\VisiMisiController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\SurveyOrganisasiController;

// New Puskesmas Controllers
use App\Http\Controllers\PoliklinikController;
use App\Http\Controllers\TenagaMedisController;
use App\Http\Controllers\JadwalDokterController;
use App\Http\Controllers\DataPasienController;
use App\Http\Controllers\LayananKesehatanController;
use App\Http\Controllers\SambutanController;
use App\Http\Controllers\SurveiKepuasanMasyarakatController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\AlurPelayananController;
use App\Http\Controllers\BerkasController;
use App\Http\Controllers\PerangkatDesaController;

// Admin Controllers
use App\Http\Controllers\AdminProfilController;
use App\Http\Controllers\AdminSliderController;
use App\Http\Controllers\AdminAgamaController;
use App\Http\Controllers\AdminBeritaController;
use App\Http\Controllers\AdminKontakController;
use App\Http\Controllers\AdminCommentController;
use App\Http\Controllers\AdminGalleryController;
use App\Http\Controllers\AdminLayananController;
use App\Http\Controllers\AdminSejarahController;
use App\Http\Controllers\AdminPekerjaanController;
use App\Http\Controllers\AdminAnnouncementController;
use App\Http\Controllers\AdminJenisKelaminController;
use App\Http\Controllers\AdminIdentitasSitusController;
use App\Http\Controllers\AdminKategoriController;
use App\Http\Controllers\AdminVisiMisiController;
use App\Http\Controllers\AdminAnggaranController;
use App\Http\Controllers\AdminPerangkatDesaController;

// New Puskesmas Admin Controllers
use App\Http\Controllers\AdminPoliklinikController;
use App\Http\Controllers\AdminTenagaMedisController;
use App\Http\Controllers\AdminJadwalDokterController;
use App\Http\Controllers\AdminLayananKesehatanController;
use App\Http\Controllers\AdminSambutanController;
use App\Http\Controllers\AdminSurveiKepuasanMasyarakatController;
use App\Http\Controllers\AdminAgendaController;
use App\Http\Controllers\AdminAlurPelayananController;
use App\Http\Controllers\AdminBerkasController;

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

Route::get('/', [BerandaController::class, 'index']);

Route::get('/berita/{beritas:slug}', [BeritaController::class, 'berita']);
Route::get('/berita', [BeritaController::class, 'index']);

Route::post('/berita/{slug}', [CommentController::class, 'comment']);
Route::post('/berita/{slug}/reply', [CommentController::class, 'commentReply']);

Route::get('/kategori/{kategori:slug}', [kategoriController::class, 'index']);

Route::get('/sambutan', [SambutanController::class, 'index']);

Route::get('/sejarah', [SejarahController::class, 'index']);

Route::get('/visi-misi', [VisiMisiController::class, 'index']);

Route::get('/perangkat-desa', [PerangkatDesaController::class, 'index']);

// Puskesmas Routes
Route::get('/poliklinik', [PoliklinikController::class, 'index']);
Route::get('/poliklinik/{id}', [PoliklinikController::class, 'detail']);

Route::get('/tenaga-medis', [TenagaMedisController::class, 'index']);
Route::get('/tenaga-medis/{id}', [TenagaMedisController::class, 'detail']);

Route::get('/jadwal-dokter', [JadwalDokterController::class, 'index']);

Route::get('/layanan-kesehatan', [LayananKesehatanController::class, 'index']);
Route::get('/layanan-kesehatan/{layanan:slug}', [LayananKesehatanController::class, 'detail']);

Route::get('/data-pasien', [DataPasienController::class, 'index']);

// SKM Routes
Route::get('/skm', [SurveiKepuasanMasyarakatController::class, 'index']);
Route::get('/skm/form', [SurveiKepuasanMasyarakatController::class, 'form']);
Route::post('/skm/submit', [SurveiKepuasanMasyarakatController::class, 'submit']);

Route::get('/kontak', [KontakController::class, 'index']);

Route::get('/layanan', [LayananController::class, 'index']);
Route::get('/alur-pelayanan', [AlurPelayananController::class, 'index']);

Route::get('/berkas', [BerkasController::class, 'index'])->name('berkas.frontend');
Route::get('/berkas/download/{id}', [BerkasController::class, 'download'])->name('berkas.download');

Route::get('/gallery', [GalleryController::class, 'index']);

Route::get('/pengumuman', [AnnouncementController::class, 'index']);
Route::get('/pengumuman/{pengumuman:slug}', [AnnouncementController::class, 'detail']);

Route::get('/agenda', [AgendaController::class, 'index']);
Route::get('/agenda/events', [AgendaController::class, 'getEvents']);

Route::get('/berita', [BeritaController::class, 'index']);


Route::get('/apbdesa', [AnggaranController::class, 'index']);
Route::get('/apbdesa/{anggaran:slug}', [AnggaranController::class, 'detail']);

//Admin Dashboard 
Auth::routes();

Route::get('/dashboard', [HomeController::class, 'index'])->name('home');

Route::resource('/admin/slider', AdminSliderController::class);

Route::get('/admin/berita/slug', [AdminBeritaController::class, 'slug']);
Route::resource('/admin/berita', AdminBeritaController::class);

Route::get('/admin/komentar', [AdminCommentController::class, 'index']);
Route::delete('/admin/komentar/{id}', [AdminCommentController::class, 'destroy']);

Route::get('/admin/kategori/slug', [AdminKategoriController::class, 'slug']);
route::resource('/admin/kategori', AdminKategoriController::class);

// Puskesmas Admin Routes
Route::resource('admin/sambutan', AdminSambutanController::class);
Route::resource('admin/agenda', AdminAgendaController::class);
Route::resource('admin/alur-pelayanan', AdminAlurPelayananController::class);

Route::resource('admin/skm', AdminSurveiKepuasanMasyarakatController::class);
Route::delete('admin/skm/responden/{id}', [AdminSurveiKepuasanMasyarakatController::class, 'deleteResponden']);

Route::resource('admin/poliklinik', AdminPoliklinikController::class);
Route::resource('admin/tenaga-medis', AdminTenagaMedisController::class);
Route::resource('admin/jadwal-dokter', AdminJadwalDokterController::class);

Route::get('/admin/layanan-kesehatan/slug', [AdminLayananKesehatanController::class, 'slug']);
Route::resource('admin/layanan-kesehatan', AdminLayananKesehatanController::class);

Route::get('admin/sejarah', [AdminSejarahController::class, 'index']);
Route::get('admin/sejarah/{id}/edit', [AdminSejarahController::class, 'edit']);
Route::put('admin/sejarah/{id}', [AdminSejarahController::class, 'update']);

Route::get('admin/visi-misi', [AdminVisiMisiController::class, 'index']);
Route::get('admin/visi-misi/{id}/edit', [AdminVisiMisiController::class, 'edit']);
Route::put('admin/visi-misi/{id}', [AdminVisiMisiController::class, 'update']);

Route::resource('admin/perangkat-desa', AdminPerangkatDesaController::class);

Route::resource('admin/agama', AdminAgamaController::class);

Route::resource('admin/jenis-kelamin', AdminJenisKelaminController::class);

Route::resource('admin/pekerjaan', AdminPekerjaanController::class);

Route::get('/admin/kontak', [AdminKontakController::class, 'index']);
Route::put('/admin/kontak/{id}', [AdminKontakController::class, 'update']);

Route::get('/admin/identitas-situs/', [AdminIdentitasSitusController::class, 'index']);
Route::put('/admin/identitas-situs/{id}', [AdminIdentitasSitusController::class, 'update']);

Route::get('/admin/profil/', [AdminProfilController::class, 'index']);
Route::put('/admin/profil/{id}', [AdminProfilController::class, 'update']);
Route::put('/admin/profil/', [AdminProfilController::class, 'changePassword']);

Route::resource('/admin/layanan', AdminLayananController::class);

Route::resource('/admin/gallery', AdminGalleryController::class);

Route::get('/admin/pengumuman/slug', [AdminAnnouncementController::class, 'slug']);
Route::resource('/admin/pengumuman', AdminAnnouncementController::class);

Route::get('/admin/apbdes', [AdminAnggaranController::class, 'slug']);
Route::resource('/admin/apbdes', AdminAnggaranController::class);

Route::resource('/admin/berkas', AdminBerkasController::class);

Route::get('/survey-organisasi', [SurveyOrganisasiController::class, 'index']);
Route::get('/galeri/{id}', [GalleryController::class, 'show'])->name('galeri.show');
