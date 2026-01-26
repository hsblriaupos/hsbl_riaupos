{{-- Template untuk form admin dengan notifikasi seragam --}}
@push('styles')
<style>
    .page-header {
        margin-bottom: 25px;
    }
    
    .page-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 5px;
    }
    
    .page-subtitle {
        color: #7f8c8d;
        font-size: 0.9rem;
    }
    
    .card {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        margin-bottom: 20px;
    }
    
    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e0e0e0;
        padding: 12px 16px;
        font-size: 0.95rem;
        font-weight: 600;
        color: #2c3e50;
    }
    
    .card-body {
        padding: 16px;
    }
    
    .form-label {
        font-size: 0.9rem;
        font-weight: 500;
        color: #2c3e50;
        margin-bottom: 6px;
        display: block;
    }
    
    .form-control {
        border: 1px solid #ced4da;
        border-radius: 5px;
        padding: 8px 12px;
        font-size: 0.9rem;
        width: 100%;
        transition: all 0.2s;
    }
    
    .form-control:focus {
        border-color: #3498db;
        box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
        outline: none;
    }
    
    .btn-submit {
        background-color: #3498db;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 8px 16px;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.2s;
    }
    
    .btn-submit:hover {
        background-color: #2980b9;
    }
    
    .btn-secondary {
        background-color: #95a5a6;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 8px 16px;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.2s;
    }
    
    .btn-secondary:hover {
        background-color: #7f8c8d;
    }
    
    .empty-state {
        text-align: center;
        padding: 30px;
        color: #95a5a6;
        font-size: 0.9rem;
    }
    
    .empty-state i {
        font-size: 2rem;
        margin-bottom: 10px;
        color: #bdc3c7;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .card-body {
            padding: 12px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Standard form submission with loading
    document.addEventListener('DOMContentLoaded', function() {
        // Auto focus on first input
        const firstInput = document.querySelector('form input[type="text"]');
        if (firstInput) {
            firstInput.focus();
        }
        
        // Handle all form submissions with loading
        document.querySelectorAll('form').forEach(form => {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                form.addEventListener('submit', function(e) {
                    // For forms with validation, don't show loading if invalid
                    if (!form.checkValidity()) {
                        return true;
                    }
                    
                    // Disable button and show loading
                    submitBtn.disabled = true;
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Memproses...';
                    
                    // Show loading toast
                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Sedang menyimpan data',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    return true;
                });
            }
        });
        
        // Standard delete confirmation
        document.querySelectorAll('.btn-delete').forEach(button => {
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
                        // Show loading popup
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
    });
</script>
@endpush