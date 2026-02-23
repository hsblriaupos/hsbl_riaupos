@if(session('success') || session('error') || session('warning') || session('info'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: false,
            position: 'center',
            backdrop: true,
            allowOutsideClick: true,
            allowEscapeKey: true,
            customClass: {
                popup: 'center-toast',
                title: 'center-toast-title',
                htmlContainer: 'center-toast-text'
            }
        });
        @endif

        @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '{{ session('error') }}',
            timer: 4000,
            timerProgressBar: true,
            showConfirmButton: false,
            position: 'center',
            backdrop: true,
            allowOutsideClick: true,
            allowEscapeKey: true,
            customClass: {
                popup: 'center-toast',
                title: 'center-toast-title',
                htmlContainer: 'center-toast-text'
            }
        });
        @endif

        @if(session('warning'))
        Swal.fire({
            icon: 'warning',
            title: 'Perhatian!',
            text: '{{ session('warning') }}',
            timer: 4000,
            timerProgressBar: true,
            showConfirmButton: false,
            position: 'center',
            backdrop: true,
            allowOutsideClick: true,
            allowEscapeKey: true,
            customClass: {
                popup: 'center-toast',
                title: 'center-toast-title',
                htmlContainer: 'center-toast-text'
            }
        });
        @endif

        @if(session('info'))
        Swal.fire({
            icon: 'info',
            title: 'Informasi',
            text: '{{ session('info') }}',
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: false,
            position: 'center',
            backdrop: true,
            allowOutsideClick: true,
            allowEscapeKey: true,
            customClass: {
                popup: 'center-toast',
                title: 'center-toast-title',
                htmlContainer: 'center-toast-text'
            }
        });
        @endif
    });
</script>

<style>
/* Ukuran yang pas untuk notifikasi center */
.center-toast {
    width: auto !important;
    max-width: 400px !important;
    padding: 1.2rem 1.5rem !important;
    border-radius: 12px !important;
    box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04) !important;
}

.center-toast-title {
    font-size: 1.1rem !important;
    font-weight: 600 !important;
    margin-bottom: 0.25rem !important;
}

.center-toast-text {
    font-size: 0.9rem !important;
    margin: 0 !important;
}

/* Responsive untuk HP */
@media (max-width: 768px) {
    .center-toast {
        max-width: 90% !important;
        padding: 1rem 1.2rem !important;
    }
    
    .center-toast-title {
        font-size: 1rem !important;
    }
    
    .center-toast-text {
        font-size: 0.85rem !important;
    }
}

@media (max-width: 576px) {
    .center-toast {
        max-width: 95% !important;
        padding: 0.9rem 1rem !important;
    }
    
    .center-toast-title {
        font-size: 0.95rem !important;
    }
    
    .center-toast-text {
        font-size: 0.8rem !important;
    }
}
</style>
@endif