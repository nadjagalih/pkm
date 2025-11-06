@extends('layouts.main')

@section('content')

<!-- ======= Breadcrumbs ======= -->
<section id="breadcrumbs" class="breadcrumbs">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center">
      <h2>Sambutan Kepala Puskesmas</h2>
      <ol>
        <li><a href="/">Beranda</a></li>
        <li>Sambutan Kepala Puskesmas</li>
      </ol>
    </div>
  </div>
</section><!-- End Breadcrumbs -->
    <!-- ======= Sambutan Section ======= -->
    <section id="sambutan-detail" class="sambutan-detail section-bg">
      <div class="container" data-aos="fade-up">

        @if($sambutan)
          <div class="row">
            <div class="col-lg-4 mb-4" data-aos="fade-right">
              <div class="text-center">
                @if($sambutan->foto)
                  <img src="{{ asset('storage/' . $sambutan->foto) }}" 
                       alt="{{ $sambutan->nama }}" 
                       class="img-fluid rounded shadow-lg mb-3"
                       style="max-width: 350px;">
                @else
                  <img src="{{ asset('assets/img/default-avatar.png') }}" 
                       alt="{{ $sambutan->nama }}" 
                       class="img-fluid rounded shadow-lg mb-3"
                       style="max-width: 350px;">
                @endif
                <div class="info-box bg-white p-4 rounded shadow-sm">
                  <h4 class="fw-bold text-primary mb-2">{{ $sambutan->nama }}</h4>
                  <p class="text-muted mb-0">{{ $sambutan->jabatan }}</p>
                </div>
              </div>
            </div>

            <div class="col-lg-8" data-aos="fade-left">
              <div class="sambutan-content bg-white p-4 rounded shadow-sm">
                <h3 class="mb-4 text-primary border-bottom pb-3">
                  <i class="bi bi-chat-quote me-2"></i>Sambutan Kepala Puskesmas
                </h3>
                <div class="sambutan-text" style="text-align: justify; line-height: 2; font-size: 1.05rem;">
                  {!! nl2br(e($sambutan->isi_sambutan)) !!}
                </div>
              </div>
            </div>
          </div>
        @else
          <div class="alert alert-info text-center" role="alert">
            <i class="bi bi-info-circle me-2"></i>
            Belum ada sambutan yang aktif saat ini.
          </div>
        @endif

  </div>
</section><!-- End Sambutan Section -->

<style>
    .sambutan-detail {
      padding: 60px 0;
      background-color: #f8f9fa;
    }

    .sambutan-detail .info-box {
      margin-top: 20px;
    }

    .sambutan-detail .info-box h4 {
      font-size: 1.5rem;
    }

    .sambutan-detail .sambutan-content {
      border-left: 4px solid #0d6efd;
    }

    .sambutan-detail .sambutan-text {
      color: #495057;
    }

    .sambutan-detail img {
      border: 5px solid white;
    }

    @media (max-width: 991px) {
      .sambutan-detail .col-lg-4 {
        margin-bottom: 2rem !important;
      }
  }
</style>

@endsection
