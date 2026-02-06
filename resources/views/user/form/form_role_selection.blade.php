@extends('user.form.layout')
@section('title', 'Pilih Posisi - HSBL')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0">
                <!-- Header -->
                <div class="card-header bg-gradient-primary text-white py-4">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('form.team.choice') }}" class="text-white me-3">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <div>
                            <h2 class="mb-0">🎯 Pilih Posisi & Kategori</h2>
                            <p class="mb-0 opacity-75">Pilih posisi dan kategori yang sesuai dengan peran Anda</p>
                        </div>
                    </div>
                </div>

                <!-- Form Selection -->
                <div class="card-body p-5">
                    <form action="{{ route('form.team.join.select-role') }}" method="POST">
                        @csrf

                        <!-- Referral Code (Dari URL/Form Sebelumnya) -->
                        <input type="hidden" name="referral_code" value="{{ $referralCode }}">

                        <!-- Info Box -->
                        <div class="alert alert-info mb-4">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Penting!</strong> Pilih posisi dan kategori sesuai dengan peran Anda dalam tim.
                            Pilihan ini akan menentukan form pendaftaran yang akan Anda isi.
                        </div>

                        <!-- Role Selection -->
                        <div class="mb-4">
                            <h5 class="text-primary mb-3">
                                <i class="fas fa-users me-2"></i>Pilih Posisi
                            </h5>

                            <div class="row g-3">
                                <!-- Basket Putra -->
                                <div class="col-md-6">
                                    <div class="form-check-card">
                                        <input class="form-check-input visually-hidden"
                                            type="radio"
                                            name="team_category"
                                            id="basket_putra"
                                            value="Basket Putra"
                                            {{ old('team_category') == 'Basket Putra' ? 'checked' : '' }}
                                            required>
                                        <label class="form-check-label card-hover" for="basket_putra">
                                            <div class="card border-2 h-100">
                                                <div class="card-body text-center p-4">
                                                    <div class="mb-3">
                                                        <i class="fas fa-basketball-ball fa-3x text-primary"></i>
                                                    </div>
                                                    <h5 class="card-title">🏀 Basket Putra</h5>
                                                    <p class="card-text text-muted small">
                                                        Pemain basket putra (boys)
                                                    </p>
                                                    <div class="mt-3">
                                                        <span class="badge bg-primary bg-opacity-10 text-primary">
                                                            <i class="fas fa-male me-1"></i>Putra
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <!-- Basket Putri -->
                                <div class="col-md-6">
                                    <div class="form-check-card">
                                        <input class="form-check-input visually-hidden"
                                            type="radio"
                                            name="team_category"
                                            id="basket_putri"
                                            value="Basket Putri"
                                            {{ old('team_category') == 'Basket Putri' ? 'checked' : '' }}>
                                        <label class="form-check-label card-hover" for="basket_putri">
                                            <div class="card border-2 h-100">
                                                <div class="card-body text-center p-4">
                                                    <div class="mb-3">
                                                        <i class="fas fa-basketball-ball fa-3x text-danger"></i>
                                                    </div>
                                                    <h5 class="card-title">🏀 Basket Putri</h5>
                                                    <p class="card-text text-muted small">
                                                        Pemain basket putri (girls)
                                                    </p>
                                                    <div class="mt-3">
                                                        <span class="badge bg-danger bg-opacity-10 text-danger">
                                                            <i class="fas fa-female me-1"></i>Putri
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <!-- Dancer -->
                                <div class="col-md-6 mt-3">
                                    <div class="form-check-card">
                                        <input class="form-check-input visually-hidden"
                                            type="radio"
                                            name="team_category"
                                            id="dancer"
                                            value="Dancer"
                                            {{ old('team_category') == 'Dancer' ? 'checked' : '' }}>
                                        <label class="form-check-label card-hover" for="dancer">
                                            <div class="card border-2 h-100">
                                                <div class="card-body text-center p-4">
                                                    <div class="mb-3">
                                                        <i class="fas fa-music fa-3x text-success"></i>
                                                    </div>
                                                    <h5 class="card-title">💃 Dancer</h5>
                                                    <p class="card-text text-muted small">
                                                        Tim penari (cheerleaders)
                                                    </p>
                                                    <div class="mt-3">
                                                        <span class="badge bg-success bg-opacity-10 text-success">
                                                            <i class="fas fa-star me-1"></i>Dancer
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <!-- Official (AKTIFKAN) -->
                                <div class="col-md-6 mt-3">
                                    <div class="form-check-card">
                                        <input class="form-check-input visually-hidden"
                                            type="radio"
                                            name="team_category"
                                            id="official"
                                            value="Official"
                                            {{ old('team_category') == 'Official' ? 'checked' : '' }}> <!-- Hapus disabled -->
                                        <label class="form-check-label card-hover" for="official">
                                            <div class="card border-2 h-100">
                                                <div class="card-body text-center p-4">
                                                    <div class="mb-3">
                                                        <i class="fas fa-clipboard-list fa-3x text-warning"></i>
                                                    </div>
                                                    <h5 class="card-title">📋 Official</h5>
                                                    <p class="card-text text-muted small">
                                                        Official tim (pelatih, manajer, pendamping)
                                                    </p>
                                                    <div class="mt-3">
                                                        <span class="badge bg-warning bg-opacity-10 text-warning">
                                                            <i class="fas fa-user-tie me-1"></i>Official
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Role Info -->
                        <div class="alert alert-warning">
                            <h6><i class="fas fa-exclamation-triangle me-2"></i>Informasi Penting:</h6>
                            <ul class="mb-0 small">
                                <li><strong>Basket Putra/Putri:</strong> Form pendaftaran pemain basket dengan data teknis (posisi, jersey, dll)</li>
                                <li><strong>Dancer:</strong> Form khusus untuk penari</li>
                                <li><strong>Official:</strong> Form untuk pelatih/manajer tim (coming soon)</li>
                                <li class="text-primary fw-bold">Jika kategori ini belum memiliki Leader/Kapten yang membayar, Anda bisa mendaftar sebagai Leader</li>
                            </ul>
                        </div>

                        <!-- Submit Button -->
                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary btn-lg px-5 py-3">
                                <i class="fas fa-arrow-right me-2"></i> Lanjut ke Form Pendaftaran
                            </button>
                            <a href="{{ route('form.team.join') }}" class="btn btn-outline-secondary ms-3">
                                <i class="fas fa-arrow-left me-2"></i> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Additional Info -->
            <div class="text-center mt-4">
                <p class="text-muted">
                    Bingung memilih posisi? Hubungi Kapten tim Anda di:
                    <a href="https://wa.me/628xxxxxxxxxx" class="text-primary fw-bold">
                        <i class="fab fa-whatsapp me-1"></i>WhatsApp Kapten
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

<style>
    .form-check-card {
        position: relative;
    }

    .form-check-input:checked+.form-check-label .card {
        border-color: var(--primary-color) !important;
        background: rgba(66, 165, 245, 0.05);
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(66, 165, 245, 0.2);
    }

    .card-hover:hover .card {
        transform: translateY(-3px);
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        cursor: pointer;
    }

    .card {
        transition: all 0.3s;
        border: 2px solid #e9ecef;
    }

    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .badge {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
    }

    .visually-hidden {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        border: 0;
    }

    /* Animation for selection */
    @keyframes pulse {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.02);
        }

        100% {
            transform: scale(1);
        }
    }

    .form-check-input:checked+.form-check-label .card {
        animation: pulse 0.5s ease-in-out;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.form-check-label');

        cards.forEach(card => {
            card.addEventListener('click', function() {
                // Remove active class from all cards
                cards.forEach(c => {
                    c.querySelector('.card').classList.remove('border-primary');
                    c.querySelector('.card').classList.add('border-2');
                });

                // Add active class to selected card
                const radio = this.previousElementSibling;
                if (!radio.disabled) {
                    radio.checked = true;
                    this.querySelector('.card').classList.add('border-primary');
                    this.querySelector('.card').classList.remove('border-2');
                }
            });
        });

        // Handle disabled options
        const disabledCards = document.querySelectorAll('input[disabled] + .form-check-label');
        disabledCards.forEach(card => {
            card.addEventListener('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    icon: 'info',
                    title: 'Coming Soon!',
                    text: 'Fitur ini akan segera hadir. Pilih opsi lain yang tersedia.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#667eea'
                });
            });
        });
    });
</script>
@endsection