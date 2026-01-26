{{-- resources/views/partials/sweetalert.blade.php --}}
{{-- SweetAlert2 CDN --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- Session Notifications Handler untuk SEMUA halaman --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fungsi untuk menampilkan notifikasi toast
    function showToastNotification(type, message) {
        const config = {
            success: { 
                icon: 'success', 
                title: 'Sukses!', 
                background: '#f0fdf4', 
                iconColor: '#10b981', 
                textColor: '#065f46' 
            },
            warning: { 
                icon: 'warning', 
                title: 'Peringatan!', 
                background: '#fffbeb', 
                iconColor: '#f59e0b', 
                textColor: '#92400e' 
            },
            error: { 
                icon: 'error', 
                title: 'Oops...', 
                background: '#fef2f2', 
                iconColor: '#ef4444', 
                textColor: '#991b1b' 
            }
        };
        
        const conf = config[type] || config.success;
        
        Swal.fire({
            icon: conf.icon,
            title: conf.title,
            text: message,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: type === 'error' ? 10000 : 5000,
            timerProgressBar: true,
            background: conf.background,
            iconColor: conf.iconColor,
            color: conf.textColor,
            customClass: {
                container: 'sweetalert-toast-container',
                popup: 'sweetalert-toast-popup'
            },
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });
    }
    
    // Cek session dan tampilkan notifikasi (untuk semua halaman)
    @if(session('success'))
    setTimeout(() => {
        showToastNotification('success', '{{ session('success') }}');
    }, 300);
    @endif
    
    @if(session('warning'))
    setTimeout(() => {
        showToastNotification('warning', '{{ session('warning') }}');
    }, 600);
    @endif
    
    @if(session('error'))
    setTimeout(() => {
        showToastNotification('error', '{{ session('error') }}');
    }, 900);
    @endif
    
    // Global delete confirmation
    const deleteButtons = document.querySelectorAll('.btn-delete:not([data-no-global])');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');
            const itemName = this.getAttribute('data-item-name') || 'data ini';
            
            Swal.fire({
                title: 'Hapus Data?',
                html: `Apakah Anda yakin ingin menghapus <strong>${itemName}</strong>?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading modal (BUKAN toast)
                    Swal.fire({
                        title: 'Menghapus...',
                        text: 'Sedang menghapus data',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    form.submit();
                }
            });
        });
    });
    
    // Global done confirmation
    document.querySelectorAll('.btn-done').forEach(button => {
        button.addEventListener('click', e => {
            e.preventDefault();
            const form = button.closest('form');
            
            Swal.fire({
                title: 'Tandai match ini sebagai selesai?',
                text: 'Setelah Anda menandai pertandingan ini sebagai selesai, jadwal tidak akan lagi ditampilkan di halaman utama pengguna, dan data pertandingan tidak dapat diubah kembali.',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Ya, selesai!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then(result => {
                if (result.isConfirmed) {
                    // Show loading modal (BUKAN toast)
                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Sedang menandai sebagai selesai',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    form.submit();
                }
            });
        });
    });
    
    // Fungsi untuk show loading popup
    window.showLoading = function(title = 'Memproses...', text = 'Sedang memproses data') {
        Swal.fire({
            title: title,
            text: text,
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    };
    
    // Fungsi untuk hide loading
    window.hideLoading = function() {
        Swal.close();
    };
    
    // Auto handle form submissions with loading
    document.querySelectorAll('form').forEach(form => {
        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn && !form.hasAttribute('data-no-loading')) {
            form.addEventListener('submit', function(e) {
                // Skip jika form invalid
                if (!form.checkValidity()) {
                    return true;
                }
                
                // Disable button
                submitBtn.disabled = true;
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Memproses...';
                
                // Show loading modal (BUKAN toast)
                Swal.fire({
                    title: 'Memproses...',
                    text: 'Sedang menyimpan data',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                return true;
            });
        }
    });
});
</script>

<style>
/* FIX SWEETALERT POSITION UNTUK HEADER FIXED */
/* Toast Notifications */
.sweetalert-toast-container {
    z-index: 999999 !important;
}

.sweetalert-toast-popup {
    margin-top: 100px !important; /* Lebih banyak margin untuk header fixed */
    z-index: 999999 !important;
}

/* Modal Notifications */
.swal2-container {
    z-index: 999999 !important;
}

.swal2-popup {
    z-index: 999999 !important;
}

/* Loading Modal (center screen, tidak keimpit) */
.swal2-modal {
    position: fixed !important;
    top: 50% !important;
    left: 50% !important;
    transform: translate(-50%, -50%) !important;
    margin: 0 !important;
}

/* Untuk user layout dengan header fixed */
#user-layout .swal2-container {
    z-index: 999999 !important;
}

#user-layout .sweetalert-toast-popup {
    margin-top: 100px !important;
}

/* Responsive adjustment */
@media (max-width: 768px) {
    .sweetalert-toast-popup {
        margin-top: 90px !important;
        width: 90% !important;
        left: 5% !important;
        right: 5% !important;
    }
    
    .swal2-popup {
        width: 90% !important;
        max-width: 400px !important;
    }
}
</style>