@extends('user.form.layout')

@section('title', 'Daftar Tim HSBL')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0">
                <div class="card-header bg-gradient-primary text-white py-4">
                    <h2 class="text-center mb-0">🎯 Daftar Tim HSBL</h2>
                    <p class="text-center mb-0 mt-2 opacity-75">Pilih cara pendaftaran kamu yaa!</p>
                </div>
                
                <div class="card-body p-5">
                    <div class="row">
                        <!-- Option 1: Create New Team -->
                        <div class="col-md-6 mb-4">
                            <a href="{{ route('form.team.create') }}" class="card h-100 text-decoration-none border-primary border-2 hover-shadow">
                                <div class="card-body text-center py-5">
                                    <div class="mb-4">
                                        <i class="fas fa-plus-circle fa-4x text-primary"></i>
                                    </div>
                                    <h4 class="card-title text-primary mb-3">🚀 Buat Tim Baru</h4>
                                    <p class="card-text text-muted">
                                        Kamu yang pertama kali daftarin sekolah? Pilih ini!<br>
                                        <strong>Kamu akan jadi Leader</strong> dan bayar biaya pendaftaran.
                                    </p>
                                    <div class="mt-4">
                                        <span class="badge bg-primary">Leader</span>
                                        <span class="badge bg-info">Pembayaran</span>
                                        <span class="badge bg-success">Buat Referral Code</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        
                        <!-- Option 2: Join Existing Team -->
                        <div class="col-md-6 mb-4">
                            <a href="{{ route('form.team.join') }}" class="card h-100 text-decoration-none border-success border-2 hover-shadow">
                                <div class="card-body text-center py-5">
                                    <div class="mb-4">
                                        <i class="fas fa-users fa-4x text-success"></i>
                                    </div>
                                    <h4 class="card-title text-success mb-3">🤝 Gabung ke Tim</h4>
                                    <p class="card-text text-muted">
                                        Sudah ada teman yang daftar duluan?<br>
                                        <strong>Masukkan referral code</strong> untuk bergabung.
                                    </p>
                                    <div class="mt-4">
                                        <span class="badge bg-success">Member</span>
                                        <span class="badge bg-secondary">Gratis</span>
                                        <span class="badge bg-warning">Butuh Kode</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Informasi Penting -->
                    <div class="alert alert-info mt-4">
                        <h5 class="alert-heading">💡 Informasi Penting:</h5>
                        <ul class="mb-0">
                            <li>Setiap kategori (Basket Putra, Basket Putri, Dancer) memiliki Leader sendiri</li>
                            <li>Hanya Leader yang membayar biaya pendaftaran tim</li>
                            <li>Referral code akan diberikan setelah Leader mendaftar</li>
                            <li>Leader bisa membagikan referral code ke teman satu sekolah</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ✅ FLOATING BUTTON UNTUK DOWNLOAD TEMPLATE (VERSI BERSIH - SATU BUTTON UNTUK SEMUA) -->
<div class="floating-download-btn" id="floatingDownloadBtn">
    <!-- Animasi Pulse -->
    <div class="pulse-ring"></div>
    <div class="pulse-ring delay"></div>
    
    <!-- Main Button -->
    <a href="{{ route('user.download_terms') }}" 
       class="download-btn"
       id="downloadSnKButton"
       target="_blank"
       aria-label="Download Template">
        <i class="fas fa-file-pdf"></i>
    </a>
    
    <!-- Badge "PENTING!" -->
    <span class="badge-penting">PENTING!</span>
    
    <!-- Text Label -->
    <span class="download-label">Download Template</span>
</div>

<!-- Modal untuk Preview SnK (Opsional) -->
<div class="modal fade" id="snkPreviewModal" tabindex="-1" aria-labelledby="snkPreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title" id="snkPreviewModalLabel">
                    <i class="fas fa-file-pdf me-2"></i>
                    Preview Syarat & Ketentuan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <iframe id="snkPreviewIframe" src="" width="100%" height="600px" frameborder="0" style="background: #f8f9fa;"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Tutup
                </button>
                <a href="{{ route('user.download_terms') }}" class="btn btn-success" target="_blank" id="modalDownloadBtn">
                    <i class="fas fa-download me-2"></i>Download PDF
                </a>
            </div>
        </div>
    </div>
</div>

<style>
/* Gradient Background */
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

/* Hover Effect untuk Cards */
.hover-shadow {
    transition: transform 0.3s, box-shadow 0.3s;
}
.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
}

/* ===== FLOATING BUTTON STYLES (VERSI BERSIH) ===== */
.floating-download-btn {
    position: fixed;
    bottom: 30px;
    right: 30px;
    z-index: 9999;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
}

/* Download Button */
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
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
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
    filter: drop-shadow(0 2px 5px rgba(0,0,0,0.2));
}

/* Pulse Animation Rings */
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

/* Badge "PENTING!" */
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
    0% { opacity: 1; }
    50% { opacity: 0.7; transform: scale(0.95); }
    100% { opacity: 1; }
}

/* Download Label (muncul di hover) */
.download-label {
    background: #1e293b;
    color: white;
    padding: 8px 15px;
    border-radius: 30px;
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
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

.floating-download-btn:hover .download-label {
    opacity: 1;
    visibility: visible;
    transform: translateX(0);
}

/* Loading Spinner */
.spinner-border-sm {
    width: 1rem;
    height: 1rem;
    border-width: 0.15em;
}

/* Modal Styling */
.modal-content {
    border: none;
    border-radius: 15px;
    overflow: hidden;
}

.modal-header {
    padding: 1rem 1.5rem;
}

.modal-header .btn-close-white {
    filter: brightness(0) invert(1);
}

.modal-footer {
    padding: 1rem 1.5rem;
}

/* Responsive untuk Mobile */
@media (max-width: 768px) {
    .floating-download-btn {
        bottom: 20px;
        right: 20px;
    }
    
    .download-btn {
        width: 55px;
        height: 55px;
        font-size: 24px;
        border-width: 2px;
    }
    
    .pulse-ring {
        width: 55px;
        height: 55px;
    }
    
    .badge-penting {
        font-size: 10px;
        padding: 3px 6px;
        top: -6px;
        right: -6px;
    }
    
    .download-label {
        font-size: 12px;
        padding: 6px 12px;
        right: 70px;
        bottom: 12px;
    }
}

/* Untuk layar sangat kecil */
@media (max-width: 480px) {
    .floating-download-btn {
        bottom: 15px;
        right: 15px;
    }
    
    .download-btn {
        width: 50px;
        height: 50px;
        font-size: 22px;
    }
    
    .pulse-ring {
        width: 50px;
        height: 50px;
    }
}

/* Custom Scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #ff416c, #ff4b2b);
    border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #ff4b2b, #ff416c);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle download button click with loading state
    const downloadBtn = document.getElementById('downloadSnKButton');
    if (downloadBtn) {
        downloadBtn.addEventListener('click', function(e) {
            // Show loading state
            const originalIcon = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            
            // Simulate download (will redirect to actual download)
            setTimeout(() => {
                // Reset setelah 1.5 detik (tapi redirect akan terjadi)
                this.innerHTML = originalIcon;
            }, 1500);
        });
    }
});

// Function to preview SnK in modal (optional)
function previewSnK() {
    const modal = new bootstrap.Modal(document.getElementById('snkPreviewModal'));
    const iframe = document.getElementById('snkPreviewIframe');
    
    // Set iframe source to latest terms (you need to create this route)
    iframe.src = "{{ route('user.view_terms') }}";
    
    modal.show();
    
    // Clear iframe when modal is hidden
    document.getElementById('snkPreviewModal').addEventListener('hidden.bs.modal', function () {
        iframe.src = '';
    });
}

// Optional: Add keyboard shortcut (Ctrl+D) to download SnK
document.addEventListener('keydown', function(e) {
    if (e.ctrlKey && e.key === 'd') {
        e.preventDefault();
        window.open("{{ route('user.download_terms') }}", '_blank');
    }
});
</script>
@endsection