@extends('user.form.layout')

@section('title', 'Halaman Sedang Dalam Perbaikan - SBL')

@section('content')
<div class="container" style="min-height: 100vh;">
    <div class="row justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="col-md-5 col-lg-4">
            <div class="card border">
                <div class="card-body text-center p-4">
                    <!-- Icon -->
                    <i class="fas fa-tools fa-2x text-muted mb-3 d-block"></i>
                    
                    <!-- Title -->
                    <h6 class="fw-bold text-dark mb-2">Sedang Dalam Perbaikan</h6>
                    
                    <p class="small text-muted mb-2">
                        Halaman Form Official sedang diperbaiki.
                    </p>

                    <hr class="my-3">

                    <p class="small text-muted mb-3">
                        Coba lagi nanti.
                    </p>

                    <!-- Buttons -->
                    <div class="d-flex gap-2 justify-content-center">
                        <a href="{{ url('/') }}" class="btn btn-light border btn-sm px-3">
                            ← Beranda
                        </a>
                        <button onclick="window.location.reload()" class="btn btn-light border btn-sm px-3">
                            ↻ Refresh
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection