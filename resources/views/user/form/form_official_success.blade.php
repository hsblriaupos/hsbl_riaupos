@extends('user.form.layout')

@section('title', 'Pendaftaran Berhasil - SBL')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body p-3">
                    
                    <!-- Header -->
                    <div class="text-center mb-3 mt-2">
                        <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex p-1 mb-1">
                            <i class="fas fa-check-circle text-success" style="font-size: 1.5rem;"></i>
                        </div>
                        <h6 class="fw-bold text-success mb-0">
                            Pendaftaran Official Berhasil
                        </h6>
                        <p class="text-muted" style="font-size: 0.7rem; margin-bottom: 0;">
                            Data official telah tersimpan
                        </p>
                    </div>

                    <!-- Informasi Pendaftaran (Ringkas) -->
                    <div class="bg-light rounded-3 mb-3" style="background: #f8f9fa; padding: 0.75rem;">
                        <div class="row" style="font-size: 0.7rem;">
                            <div class="col-6 mb-2">
                                <span class="text-muted">Nama:</span>
                                <span class="fw-semibold d-block">{{ $official->name }}</span>
                            </div>
                            <div class="col-6 mb-2">
                                <span class="text-muted">Role:</span>
                                <span class="fw-semibold d-block">{{ $official->team_role_label ?? $official->team_role }}</span>
                            </div>
                            <div class="col-6 mb-2">
                                <span class="text-muted">Sekolah:</span>
                                <span class="fw-semibold d-block">{{ $team->school_name }}</span>
                            </div>
                            <div class="col-6 mb-2">
                                <span class="text-muted">Kategori:</span>
                                <span class="fw-semibold d-block">
                                    @if($official->category == 'basket_putra')
                                        Basket Putra
                                    @elseif($official->category == 'basket_putri')
                                        Basket Putri
                                    @elseif($official->category == 'dancer')
                                        Dancer
                                    @else
                                        {{ $official->category }}
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="d-grid gap-2">
                        <a href="{{ route('student.dashboard') }}" class="btn btn-primary" style="font-size: 0.7rem; padding: 0.4rem 0.5rem;">
                            <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                        </a>
                        <a href="{{ route('form.team.choice') }}" class="btn btn-outline-secondary" style="font-size: 0.7rem; padding: 0.4rem 0.5rem;">
                            <i class="fas fa-home me-1"></i> Kembali
                        </a>
                    </div>

                    <!-- Footer -->
                    <div class="text-center mt-3 pt-1 border-top">
                        <span style="font-size: 0.6rem;" class="text-muted">hondahsblriaupos@gmail.com</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .bg-opacity-10 {
        background: rgba(67, 97, 238, 0.1);
    }
</style>
@endpush

@endsection