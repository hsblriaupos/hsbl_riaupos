@extends('user.form.layout')

@section('title', 'Daftar Tim SBL')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Header -->
            <div class="text-center mb-4">
                <span class="badge bg-soft-primary text-primary px-3 py-1 mb-2 rounded-pill" style="font-size: 0.8rem;">
                    🏀 SBL Registration
                </span>
                <h2 class="fw-bold text-dark mb-2" style="font-size: 2rem;">Daftar Tim HSBL</h2>
                <p class="text-muted" style="font-size: 1rem;">Pilih cara pendaftaran yang sesuai dengan kebutuhanmu!</p>
            </div>

            <!-- PERBAIKAN: Hanya 1 row g-3 (tidak double) -->
            <div class="row g-3">
                <!-- Option 1: Create New Team -->
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow">
                        <div class="card-body p-4">
                            <div class="text-center mb-3">
                                <div class="icon-circle bg-gradient-orange mb-3" style="width: 70px; height: 70px;">
                                    <i class="fas fa-plus-circle fa-2x text-white"></i>
                                </div>
                                <h4 class="fw-bold text-dark mb-1">Buat Tim Baru</h4>
                                <span class="badge bg-soft-warning text-warning px-2 py-1 rounded-pill mb-2" style="font-size: 0.7rem;">
                                    👑 Kamu akan jadi Leader
                                </span>
                            </div>

                            <p class="text-muted text-center mb-3" style="font-size: 0.9rem;">
                                Pertama kali daftarin sekolah? Pilih ini! Kamu yang akan mengelola tim dan membayar biaya pendaftaran.
                            </p>

                            <div class="feature-list mb-3 p-3">
                                <div class="d-flex align-items-center mb-1 small">
                                    <i class="fas fa-check-circle text-success me-1" style="font-size: 0.9rem;"></i>
                                    <span style="font-size: 0.9rem;">Buat tim baru untuk sekolahmu</span>
                                </div>
                                <div class="d-flex align-items-center mb-1 small">
                                    <i class="fas fa-check-circle text-success me-1" style="font-size: 0.9rem;"></i>
                                    <span style="font-size: 0.9rem;">Dapatkan referral code untuk anggota</span>
                                </div>
                                <div class="d-flex align-items-center small">
                                    <i class="fas fa-check-circle text-success me-1" style="font-size: 0.9rem;"></i>
                                    <span style="font-size: 0.9rem;">Kelola anggota tim dengan mudah</span>
                                </div>
                            </div>

                            <!-- PERINGATAN PENTING - STAY FOREVER (tidak akan hilang) -->
                            <div class="alert alert-danger py-3 px-3 mb-3 stay-alert" 
                                 style="font-size: 0.75rem; background: #f8d7da; border-left: 4px solid #dc3545; border-radius: 8px;">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-exclamation-triangle text-danger me-2 mt-1" style="font-size: 1rem;"></i>
                                    <div class="text-start">
                                        <strong class="text-danger d-block mb-1">⚠️ PERINGATAN PENTING!</strong>
                                        <span class="text-danger" style="font-size: 0.7rem;">
                                            Jika Anda keluar dari halaman ini atau tidak langsung mendaftar sebagai Leader:
                                        </span>
                                        <ul class="mb-0 mt-2 ps-3" style="font-size: 0.7rem; color: #721c24;">
                                            <li class="mb-1">❌ Tim Anda tidak akan memiliki Leader</li>
                                            <li class="mb-1">❌ Referral code TIDAK AKAN DIDAPATKAN</li>
                                            <li class="mb-1">❌ Anggota tim lain tidak bisa bergabung</li>
                                            <li>✅ WAJIB langsung daftar & bayar agar referral code aktif</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Notes Download Template - STAY FOREVER -->
                            <div class="alert alert-info py-2 px-3 mb-3 stay-alert" 
                                 style="font-size: 0.7rem; background: #d1ecf1; border-left: 4px solid #17a2b8; border-radius: 8px;">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-download text-info me-2 mt-1" style="font-size: 0.8rem;"></i>
                                    <div class="text-start">
                                        <strong class="text-info">📄 CATATAN PENTING!</strong><br>
                                        <span style="color: #0c5460;">
                                            Jangan lupa download template surat pendaftaran terlebih dahulu sebelum mengisi formulir. 
                                            Klik tombol <strong>"Download Template"</strong> di pojok kanan bawah.
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <a href="{{ route('form.team.create') }}"
                                class="btn btn-warning w-100 py-2 fw-semibold" 
                                style="font-size: 0.95rem;">
                                Buat Tim Baru
                                <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Option 2: Join Existing Team -->
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow">
                        <div class="card-body p-4">
                            <div class="text-center mb-3">
                                <div class="icon-circle bg-gradient-teal mb-3" style="width: 70px; height: 70px;">
                                    <i class="fas fa-users fa-2x text-white"></i>
                                </div>
                                <h4 class="fw-bold text-dark mb-1">Gabung ke Tim</h4>
                                <span class="badge bg-soft-teal text-teal px-2 py-1 rounded-pill mb-2" style="font-size: 0.7rem;">
                                    🔗 Pakai Referral Code
                                </span>
                            </div>

                            <p class="text-muted text-center mb-3" style="font-size: 0.9rem;">
                                Udah ada temen yang daftar duluan? Gabung aja pake referral code dari leader tim!
                            </p>

                            <div class="feature-list mb-3 p-3">
                                <div class="d-flex align-items-center mb-1 small">
                                    <i class="fas fa-check-circle text-success me-1" style="font-size: 0.9rem;"></i>
                                    <span style="font-size: 0.9rem;">Masuk ke tim yang sudah ada</span>
                                </div>
                                <div class="d-flex align-items-center mb-1 small">
                                    <i class="fas fa-check-circle text-success me-1" style="font-size: 0.9rem;"></i>
                                    <span style="font-size: 0.9rem;">Gak perlu bayar pendaftaran</span>
                                </div>
                                <div class="d-flex align-items-center small">
                                    <i class="fas fa-check-circle text-success me-1" style="font-size: 0.9rem;"></i>
                                    <span style="font-size: 0.9rem;">Langsung jadi anggota tim</span>
                                </div>
                            </div>

                            <!-- Informasi Penting untuk Join Team - STAY FOREVER -->
                            <div class="alert alert-info py-3 px-3 mb-3 stay-alert" 
                                 style="font-size: 0.75rem; background: #d1ecf1; border-left: 4px solid #17a2b8; border-radius: 8px;">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-info-circle text-info me-2 mt-1" style="font-size: 1rem;"></i>
                                    <div class="text-start">
                                        <strong class="text-info d-block mb-1">📌 INFORMASI PENTING!</strong>
                                        <ul class="mb-0 mt-1 ps-3" style="font-size: 0.7rem; color: #0c5460;">
                                            <li class="mb-1">🔑 Pastikan referral code yang Anda masukkan VALID</li>
                                            <li class="mb-1">💰 Biaya pendaftaran sudah ditanggung oleh Leader tim</li>
                                            <li class="mb-1">✅ Anda hanya perlu mengisi data diri dan upload dokumen</li>
                                            <li>📞 Hubungi Leader tim jika referral code tidak berfungsi</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Notes Download Template untuk Join Team - STAY FOREVER -->
                            <div class="alert alert-warning py-2 px-3 mb-3 stay-alert" 
                                 style="font-size: 0.7rem; background: #fff3cd; border-left: 4px solid #ffc107; border-radius: 8px;">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-download text-warning me-2 mt-1" style="font-size: 0.8rem;"></i>
                                    <div class="text-start">
                                        <strong class="text-warning">📄 CATATAN PENTING!</strong><br>
                                        <span style="color: #856404;">
                                            Pastikan Anda sudah download template surat pendaftaran. 
                                            Klik tombol <strong>"Download Template"</strong> di pojok kanan bawah.
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <a href="{{ route('form.team.join') }}"
                                class="btn btn-teal w-100 py-2 fw-semibold" 
                                style="font-size: 0.95rem;">
                                Gabung ke Tim
                                <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Floating Download Button -->
            <div class="floating-download" id="floatingDownloadBtn">
                <div class="pulse-ring"></div>
                <div class="pulse-ring delay"></div>

                <a href="{{ route('user.download_terms') }}"
                    class="download-btn"
                    id="downloadSnKButton"
                    aria-label="Download Template">
                    <i class="fas fa-file-pdf"></i>
                </a>

                <span class="badge-penting">PENTING!</span>
                <span class="download-label">Download Template</span>
            </div>

            <!-- Informasi Penting Bawah - STAY FOREVER -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm bg-soft-primary">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-start">
                                <div class="info-icon bg-primary text-white rounded-circle me-2" style="min-width: 30px; height: 30px; font-size: 0.9rem;">
                                    <i class="fas fa-lightbulb"></i>
                                </div>
                                <div>
                                    <h6 class="text-primary fw-bold mb-2" style="font-size: 0.95rem;">💡 Informasi Penting:</h6>
                                    <div class="row g-1">
                                        <div class="col-md-6">
                                            <ul class="list-unstyled mb-0">
                                                <li class="mb-1 d-flex align-items-center">
                                                    <i class="fas fa-circle text-primary me-1" style="font-size: 6px;"></i>
                                                    <span style="font-size: 0.85rem;">Setiap kategori punya Leader sendiri (Basket Putra, Putri, Dancer)</span>
                                                </li>
                                                <li class="mb-1 d-flex align-items-center">
                                                    <i class="fas fa-circle text-primary me-1" style="font-size: 6px;"></i>
                                                    <span style="font-size: 0.85rem;">Hanya Leader yang bayar biaya pendaftaran tim</span>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <ul class="list-unstyled mb-0">
                                                <li class="mb-1 d-flex align-items-center">
                                                    <i class="fas fa-circle text-primary me-1" style="font-size: 6px;"></i>
                                                    <span style="font-size: 0.85rem;">Referral code langsung didapat setelah Leader daftar</span>
                                                </li>
                                                <li class="mb-1 d-flex align-items-center">
                                                    <i class="fas fa-circle text-primary me-1" style="font-size: 6px;"></i>
                                                    <span style="font-size: 0.85rem;">Leader bisa bagikan referral code ke teman satu sekolah</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Notifikasi Template Belum Tersedia -->
<div class="modal fade" id="templateUnavailableModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-body text-center p-4">
                <div class="mb-3">
                    <div class="icon-circle bg-warning bg-opacity-10 mx-auto mb-3" style="width: 70px; height: 70px; background: rgba(255, 193, 7, 0.1);">
                        <i class="fas fa-file-pdf fa-2x text-warning"></i>
                    </div>
                </div>
                <h5 class="fw-bold mb-2">⚠️ Template Belum Tersedia</h5>
                <p class="text-muted mb-3" style="font-size: 0.95rem;">
                    Template pendaftaran belum diperbarui oleh admin.<br>
                    Mohon tunggu update selanjutnya ya! 🙏
                </p>
                <div class="alert alert-info py-2 px-3 mb-3" style="font-size: 0.85rem; background: #e3f2fd; border: none;">
                    <i class="fas fa-clock me-1"></i> Pantau terus halaman ini untuk info terbaru
                </div>
                <button type="button" class="btn btn-warning px-4 py-2 fw-semibold" data-bs-dismiss="modal" style="background: linear-gradient(135deg, #FF6B6B 0%, #FF8E53 100%); border: none; color: white;">
                    <i class="fas fa-check-circle me-1"></i> Mengerti
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom Colors */
    .bg-gradient-orange {
        background: linear-gradient(135deg, #FF6B6B 0%, #FF8E53 100%);
    }

    .bg-gradient-teal {
        background: linear-gradient(135deg, #4ECDC4 0%, #556270 100%);
    }

    .bg-soft-primary {
        background-color: rgba(79, 70, 229, 0.1);
    }

    .bg-soft-warning {
        background-color: rgba(255, 159, 67, 0.1);
    }

    .bg-soft-teal {
        background-color: rgba(78, 205, 196, 0.1);
    }

    .text-teal {
        color: #4ECDC4 !important;
    }

    .btn-teal {
        background: linear-gradient(135deg, #4ECDC4 0%, #45b7aa 100%);
        border: none;
        color: white;
        font-size: 0.95rem;
    }

    .btn-teal:hover {
        background: linear-gradient(135deg, #45b7aa 0%, #3aa396 100%);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(78, 205, 196, 0.3);
    }

    .btn-warning {
        background: linear-gradient(135deg, #FF6B6B 0%, #FF8E53 100%);
        border: none;
        color: white;
        font-size: 0.95rem;
    }

    .btn-warning:hover {
        background: linear-gradient(135deg, #ff5252 0%, #ff7b3f 100%);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(255, 107, 107, 0.3);
    }

    .icon-circle {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .icon-circle i {
        font-size: 2rem;
    }

    .feature-list {
        background-color: #f8f9fa;
        padding: 0.75rem !important;
        border-radius: 0.75rem;
    }

    .info-icon {
        min-width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
    }

    .badge {
        font-weight: 500;
        letter-spacing: 0.3px;
        font-size: 0.7rem;
    }

    .card {
        border-radius: 1.25rem;
        overflow: hidden;
    }

    .card-body {
        border-radius: 1.25rem;
    }

    /* ======================================== */
    /* PERLINDUNGAN EKSTRA - ALERT TIDAK AKAN PERNAH HILANG */
    /* ======================================== */
    .stay-alert {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
        animation: none !important;
        transition: none !important;
        transform: none !important;
        pointer-events: auto !important;
        max-height: none !important;
        overflow: visible !important;
    }

    /* Semua alert dengan class .alert */
    .alert {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
        animation: none !important;
        transition: none !important;
        transform: none !important;
    }

    /* Mencegah class fade, show, hide, d-none dari Bootstrap */
    .alert.fade,
    .alert.show,
    .alert.hide,
    .alert.d-none,
    .alert.invisible {
        display: block !important;
        opacity: 1 !important;
        visibility: visible !important;
    }

    /* Floating Download Button Styles */
    .floating-download {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
    }

    .download-btn {
        width: 65px;
        height: 65px;
        background: linear-gradient(135deg, #ff416c, #ff4b2b);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 28px;
        box-shadow: 0 10px 25px rgba(255, 75, 43, 0.4);
        border: 3px solid white;
        text-decoration: none;
        position: relative;
        z-index: 2;
        cursor: pointer;
    }

    .download-btn:hover {
        transform: scale(1.1) rotate(5deg);
        box-shadow: 0 15px 35px rgba(255, 65, 108, 0.6);
        color: white;
    }

    .download-btn:active {
        transform: scale(0.95);
    }

    .download-btn i {
        filter: drop-shadow(0 2px 5px rgba(0, 0, 0, 0.2));
    }

    .pulse-ring {
        position: absolute;
        top: 0;
        left: 0;
        width: 65px;
        height: 65px;
        border-radius: 50%;
        background: rgba(255, 75, 43, 0.3);
        animation: pulse 2s infinite;
        z-index: 1;
    }

    .pulse-ring.delay {
        animation: pulse 2s infinite 0.5s;
        background: rgba(255, 65, 108, 0.3);
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
            opacity: 0.7;
        }
        50% {
            transform: scale(1.5);
            opacity: 0.3;
        }
        100% {
            transform: scale(1.8);
            opacity: 0;
        }
    }

    .badge-penting {
        position: absolute;
        top: -8px;
        right: -8px;
        background: #dc3545;
        color: white;
        font-size: 11px;
        font-weight: bold;
        padding: 4px 8px;
        border-radius: 30px;
        box-shadow: 0 4px 10px rgba(220, 53, 69, 0.3);
        border: 2px solid white;
        z-index: 3;
        animation: blink 1.5s infinite;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }

    @keyframes blink {
        0% {
            opacity: 1;
        }
        50% {
            opacity: 0.7;
            transform: scale(0.95);
        }
        100% {
            opacity: 1;
        }
    }

    .download-label {
        background: #1e293b;
        color: white;
        padding: 8px 15px;
        border-radius: 30px;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        border-left: 4px solid #ff4b2b;
        white-space: nowrap;
        opacity: 0;
        visibility: hidden;
        transform: translateX(10px);
        transition: all 0.3s ease;
        position: absolute;
        bottom: 15px;
        right: 80px;
        pointer-events: none;
        z-index: 1;
    }

    .download-label::after {
        content: '';
        position: absolute;
        top: 50%;
        right: -8px;
        transform: translateY(-50%);
        border-left: 8px solid #1e293b;
        border-top: 8px solid transparent;
        border-bottom: 8px solid transparent;
    }

    .floating-download:hover .download-label {
        opacity: 1;
        visibility: visible;
        transform: translateX(0);
    }

    /* Modal custom styling */
    .modal-content {
        border-radius: 1.5rem;
    }

    .modal .btn-warning {
        background: linear-gradient(135deg, #FF6B6B 0%, #FF8E53 100%);
    }

    .modal .btn-warning:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 107, 107, 0.4);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .icon-circle {
            width: 60px;
            height: 60px;
        }
        .icon-circle i {
            font-size: 1.5rem;
        }
        h2 {
            font-size: 1.5rem !important;
        }
        .floating-download {
            bottom: 20px;
            right: 20px;
        }
        .download-btn {
            width: 55px;
            height: 55px;
            font-size: 24px;
        }
        .pulse-ring {
            width: 55px;
            height: 55px;
        }
        .badge-penting {
            font-size: 10px;
            padding: 3px 6px;
        }
        .download-label {
            font-size: 11px;
            padding: 6px 12px;
            right: 70px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ========================================
        // PERLINDUNGAN EKSTRA: Alert TIDAK AKAN PERNAH HILANG
        // ========================================
        
        // 1. Lock semua alert agar tetap terlihat
        function lockAllAlerts() {
            var allAlerts = document.querySelectorAll('.alert, .stay-alert');
            allAlerts.forEach(function(alert) {
                // Hapus semua class yang bisa menyembunyikan
                alert.classList.remove('fade', 'show', 'hide', 'd-none', 'invisible', 'collapsing', 'collapse');
                
                // Set style langsung
                alert.style.display = 'block';
                alert.style.opacity = '1';
                alert.style.visibility = 'visible';
                alert.style.animation = 'none';
                alert.style.transition = 'none';
                alert.style.transform = 'none';
                alert.style.maxHeight = 'none';
                alert.style.overflow = 'visible';
                alert.style.pointerEvents = 'auto';
                
                // Hapus attribute yang bisa menyebabkan auto-hide
                alert.removeAttribute('data-bs-dismiss');
                alert.removeAttribute('data-dismiss');
                alert.removeAttribute('data-bs-auto-close');
                
                // Hapus event listener jika ada (cara sederhana dengan clone)
                var newAlert = alert.cloneNode(true);
                if (alert.parentNode) {
                    alert.parentNode.replaceChild(newAlert, alert);
                }
            });
        }
        
        // Lock alert pertama kali
        lockAllAlerts();
        
        // 2. Monitor perubahan DOM (jika ada yang mencoba menyembunyikan alert)
        var observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'attributes' || mutation.type === 'childList') {
                    var target = mutation.target;
                    if (target.classList && target.classList.contains('alert')) {
                        // Jika ada yang mencoba mengubah style atau class alert
                        if (target.style.display === 'none' || 
                            target.style.opacity === '0' || 
                            target.classList.contains('d-none') ||
                            target.classList.contains('fade')) {
                            
                            // Reset kembali
                            target.style.display = 'block';
                            target.style.opacity = '1';
                            target.style.visibility = 'visible';
                            target.classList.remove('d-none', 'fade', 'hide', 'invisible');
                        }
                    }
                }
            });
        });
        
        // Observasi semua alert
        var allAlerts = document.querySelectorAll('.alert');
        allAlerts.forEach(function(alert) {
            observer.observe(alert, { attributes: true, attributeFilter: ['style', 'class'] });
        });
        
        // 3. Override fungsi setTimeout untuk mencegah auto-hide alert
        var originalSetTimeout = window.setTimeout;
        window.setTimeout = function(fn, delay) {
            // Jika delay kurang dari 10 detik (10000 ms) dan mengandung kata 'alert' atau 'hide'
            if (delay < 10000) {
                var fnString = fn.toString();
                if (fnString.toLowerCase().includes('alert') || 
                    fnString.toLowerCase().includes('hide') ||
                    fnString.toLowerCase().includes('fade') ||
                    fnString.toLowerCase().includes('remove')) {
                    console.log('🛡️ Mencegah auto-hide alert (timeout dicegah)');
                    return null;
                }
            }
            return originalSetTimeout(fn, delay);
        };
        
        // 4. Override jQuery fadeOut/hide jika ada
        if (typeof $ !== 'undefined') {
            var originalFadeOut = $.fn.fadeOut;
            var originalHide = $.fn.hide;
            
            $.fn.fadeOut = function(duration, callback) {
                // Cegah fadeOut pada alert
                if (this.hasClass('alert') || this.hasClass('stay-alert')) {
                    console.log('🛡️ Mencegah fadeOut pada alert');
                    return this;
                }
                return originalFadeOut.call(this, duration, callback);
            };
            
            $.fn.hide = function(duration, callback) {
                // Cegah hide pada alert
                if (this.hasClass('alert') || this.hasClass('stay-alert')) {
                    console.log('🛡️ Mencegah hide pada alert');
                    return this;
                }
                return originalHide.call(this, duration, callback);
            };
        }
        
        // 5. Mencegah event transition/animation
        document.addEventListener('transitionstart', function(e) {
            if (e.target.classList && e.target.classList.contains('alert')) {
                e.stopPropagation();
                e.preventDefault();
            }
        }, true);
        
        document.addEventListener('animationstart', function(e) {
            if (e.target.classList && e.target.classList.contains('alert')) {
                e.stopPropagation();
                e.preventDefault();
            }
        }, true);
        
        // 6. Interval pengecekan berkala (setiap 1 detik) - sebagai safety net
        setInterval(function() {
            var alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                var computedStyle = window.getComputedStyle(alert);
                if (computedStyle.display === 'none' || 
                    computedStyle.opacity === '0' || 
                    computedStyle.visibility === 'hidden') {
                    // Force tampilkan kembali
                    alert.style.display = 'block';
                    alert.style.opacity = '1';
                    alert.style.visibility = 'visible';
                    alert.classList.remove('d-none', 'fade', 'hide', 'invisible');
                }
            });
        }, 1000);
        
        // ========================================
        // DOWNLOAD TEMPLATE FUNCTION
        // ========================================
        const downloadBtn = document.getElementById('downloadSnKButton');

        if (downloadBtn) {
            downloadBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const originalIcon = this.innerHTML;
                const originalHref = this.getAttribute('href');

                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                this.style.pointerEvents = 'none';

                fetch(originalHref, {
                    method: 'HEAD',
                    cache: 'no-cache'
                })
                .then(response => {
                    if (response.ok) {
                        window.open(originalHref, '_blank');
                        setTimeout(() => {
                            this.innerHTML = '<i class="fas fa-check-circle"></i>';
                            setTimeout(() => {
                                if (this.innerHTML !== originalIcon) {
                                    this.innerHTML = originalIcon;
                                }
                            }, 800);
                        }, 300);
                    } else if (response.status === 404) {
                        const modal = new bootstrap.Modal(document.getElementById('templateUnavailableModal'));
                        modal.show();
                        this.innerHTML = originalIcon;
                    } else {
                        alert('Terjadi kesalahan. Silakan coba lagi nanti.');
                        this.innerHTML = originalIcon;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Gagal mengakses file. Coba lagi nanti ya!');
                    this.innerHTML = originalIcon;
                })
                .finally(() => {
                    setTimeout(() => {
                        downloadBtn.style.pointerEvents = 'auto';
                    }, 500);
                });
            });
        }
    });
</script>
@endsection