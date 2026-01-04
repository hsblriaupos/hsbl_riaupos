@extends('admin.layouts.app')
@section('title', 'Team List - Administrator')

@section('content')
@include('partials.sweetalert')

@push('styles')
<style>
    .page-header {
        margin-bottom: 20px;
    }

    .page-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 3px;
    }

    .page-subtitle {
        color: #6c757d;
        font-size: 0.85rem;
    }

    /* Card Style */
    .admin-card {
        background: white;
        border: 1px solid #e3e6f0;
        border-radius: 6px;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        margin-bottom: 20px;
        overflow: hidden;
    }

    .card-header {
        background-color: #f8f9fc;
        border-bottom: 1px solid #e3e6f0;
        padding: 12px 16px;
        font-weight: 600;
        color: #4a4c54;
        font-size: 0.95rem;
    }

    .card-body {
        padding: 16px;
    }

    /* Table Styles - Compact */
    .table-wrapper {
        border-radius: 4px;
        overflow: hidden;
        border: 1px solid #e3e6f0;
    }

    .table {
        margin-bottom: 0;
        font-size: 0.875rem;
        color: #4a4c54;
    }

    .table th {
        background-color: #f8f9fc;
        border-bottom: 2px solid #e3e6f0;
        color: #4a4c54;
        font-weight: 600;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        padding: 10px 12px;
        white-space: nowrap;
        border-top: none;
    }

    .table td {
        padding: 10px 12px;
        vertical-align: middle;
        border-top: 1px solid #e3e6f0;
        font-size: 0.875rem;
    }

    .table tbody tr {
        transition: background-color 0.15s ease;
    }

    .table tbody tr:hover {
        background-color: #f8f9fc;
    }

    /* Badge Styles - More Subtle */
    .status-badge {
        padding: 3px 8px;
        font-size: 0.75rem;
        font-weight: 500;
        border-radius: 4px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .badge-locked {
        background-color: #fee2e2;
        color: #dc3545;
        border: 1px solid #fecaca;
    }

    .badge-unlocked {
        background-color: #d1fae5;
        color: #28a745;
        border: 1px solid #a7f3d0;
    }

    .badge-verified {
        background-color: #d1fae5;
        color: #28a745;
        border: 1px solid #a7f3d0;
    }

    .badge-pending {
        background-color: #fef3c7;
        color: #ffc107;
        border: 1px solid #fde68a;
    }

    .badge-rejected {
        background-color: #fee2e2;
        color: #dc3545;
        border: 1px solid #fecaca;
    }

    /* Action Buttons - Minimal */
    .action-buttons {
        display: flex;
        gap: 4px;
        flex-wrap: nowrap;
    }

    .btn-action {
        padding: 4px 8px;
        font-size: 0.75rem;
        border-radius: 3px;
        line-height: 1;
        min-width: 28px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-action i {
        font-size: 0.8rem;
    }

    /* Category Tag */
    .category-tag {
        font-size: 0.75rem;
        padding: 2px 8px;
        border-radius: 3px;
        background-color: #e0f2fe;
        color: #0369a1;
        border: 1px solid #bae6fd;
        white-space: nowrap;
    }

    /* Referral Code */
    .ref-code {
        font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
        font-size: 0.8rem;
        color: #495057;
        background-color: #f8f9fa;
        padding: 2px 6px;
        border-radius: 3px;
        border: 1px solid #e9ecef;
    }

    /* Filter Section - Compact */
    .filter-section {
        background: white;
        border: 1px solid #e3e6f0;
        border-radius: 6px;
        margin-bottom: 16px;
        overflow: hidden;
    }

    .filter-header {
        background-color: #f8f9fc;
        padding: 10px 16px;
        border-bottom: 1px solid #e3e6f0;
        font-size: 0.9rem;
        color: #4a4c54;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .filter-body {
        padding: 16px;
    }

    .filter-row {
        display: flex;
        gap: 12px;
        align-items: flex-end;
        flex-wrap: wrap;
    }

    .filter-group {
        flex: 1;
        min-width: 150px;
    }

    .filter-label {
        font-size: 0.8rem;
        color: #6c757d;
        margin-bottom: 4px;
        font-weight: 500;
    }

    .search-group {
        flex: 2;
        min-width: 200px;
        position: relative;
    }

    .search-icon {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
        font-size: 0.9rem;
    }

    .search-input {
        padding-left: 32px;
        font-size: 0.875rem;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 40px 20px;
    }

    .empty-icon {
        font-size: 2.5rem;
        color: #dee2e6;
        margin-bottom: 12px;
        opacity: 0.6;
    }

    .empty-title {
        font-size: 1rem;
        color: #6c757d;
        margin-bottom: 6px;
        font-weight: 500;
    }

    .empty-text {
        color: #adb5bd;
        font-size: 0.85rem;
        max-width: 350px;
        margin: 0 auto;
        line-height: 1.4;
    }

    /* Pagination Info */
    .table-footer {
        background-color: #f8f9fc;
        padding: 10px 16px;
        border-top: 1px solid #e3e6f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .pagination-info {
        font-size: 0.825rem;
        color: #6c757d;
    }

    /* Quick Actions */
    .quick-actions {
        display: inline-flex;
        gap: 8px;
        margin-bottom: 12px;
    }

    .btn-quick-action {
        padding: 4px 12px;
        font-size: 0.8rem;
        border-radius: 3px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .filter-row {
            flex-direction: column;
            align-items: stretch;
        }
        
        .filter-group,
        .search-group {
            width: 100%;
            min-width: 100%;
        }
        
        .table-wrapper {
            overflow-x: auto;
        }
        
        .action-buttons {
            flex-direction: column;
            gap: 3px;
        }
        
        .btn-action {
            width: 100%;
            justify-content: flex-start;
        }
    }

    /* Compact View Toggle */
    .compact-view {
        font-size: 0.8rem;
    }
    
    .compact-view td {
        padding: 8px 10px;
    }
</style>
@endpush

<div class="container-fluid px-3">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="page-title mt-3">
                    <i class="fas fa-users text-primary me-2"></i>Daftar Tim
                </h1>
                <p class="page-subtitle">Kelola tim peserta HSBL</p>
            </div>
            <div class="quick-actions">
                <button class="btn btn-outline-secondary btn-sm" onclick="toggleCompactView()">
                    <i class="fas fa-compress-alt me-1"></i>Compact
                </button>
                <a href="#" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-download me-1"></i>Export
                </a>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <div class="filter-header">
            <i class="fas fa-filter text-muted"></i>
            <span>Filter Data</span>
        </div>
        <div class="filter-body">
            <form method="GET" action="{{ url()->current() }}" class="mb-0">
                <div class="filter-row">
                    <div class="filter-group">
                        <label class="filter-label">Sekolah</label>
                        <select name="school" class="form-control form-control-sm" onchange="this.form.submit()">
                            <option value="">Semua Sekolah</option>
                            @php
                            $schools = $teamList->pluck('school_name')->unique();
                            @endphp
                            @foreach($schools as $school)
                            <option value="{{ $school }}" {{ request('school') == $school ? 'selected' : '' }}>
                                {{ $school }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-group">
                        <label class="filter-label">Status</label>
                        <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            <option value="unverified" {{ request('status') == 'unverified' ? 'selected' : '' }}>Belum Diverifikasi</option>
                            <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Terverifikasi</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label class="filter-label">Kategori</label>
                        <select name="category" class="form-control form-control-sm" onchange="this.form.submit()">
                            <option value="">Semua Kategori</option>
                            <option value="Basket Putra" {{ request('category') == 'Basket Putra' ? 'selected' : '' }}>Basket Putra</option>
                            <option value="Basket Putri" {{ request('category') == 'Basket Putri' ? 'selected' : '' }}>Basket Putri</option>
                            <option value="Dancer" {{ request('category') == 'Dancer' ? 'selected' : '' }}>Dancer</option>
                        </select>
                    </div>

                    <div class="search-group">
                        <label class="filter-label">Pencarian</label>
                        <div class="position-relative">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" 
                                   name="search" 
                                   class="form-control form-control-sm search-input" 
                                   placeholder="Cari nama sekolah atau referral code..."
                                   value="{{ request('search') }}">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-search me-1"></i>Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table -->
    <div class="admin-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Daftar Tim Terdaftar</span>
            <span class="badge bg-primary">{{ $teamList->count() }} tim</span>
        </div>
        <div class="card-body p-0">
            <div class="table-wrapper">
                <table class="table" id="teamTable">
                    <thead>
                        <tr>
                            <th style="width: 50px">#</th>
                            <th>Sekolah</th>
                            <th>Referral Code</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            <th>Verifikasi</th>
                            <th>Dibuat</th>
                            <th style="width: 120px" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($teamList as $index => $team)
                        <tr>
                            <td class="text-muted">{{ $index + 1 }}</td>
                            <td>
                                <div class="fw-medium mb-1">{{ $team->school_name }}</div>
                                <small class="text-muted d-block">
                                    {{ $team->competition }} • {{ $team->season }}
                                </small>
                            </td>
                            <td>
                                <span class="ref-code">{{ $team->referral_code }}</span>
                            </td>
                            <td>
                                <span class="category-tag">{{ $team->team_category }}</span>
                            </td>
                            <td>
                                @if($team->locked_status == 'locked')
                                <span class="status-badge badge-locked">
                                    <i class="fas fa-lock"></i> Terkunci
                                </span>
                                @else
                                <span class="status-badge badge-unlocked">
                                    <i class="fas fa-unlock"></i> Terbuka
                                </span>
                                @endif
                            </td>
                            <td>
                                @if($team->verification_status == 'verified')
                                <span class="status-badge badge-verified">
                                    <i class="fas fa-check-circle"></i> Terverifikasi
                                </span>
                                @elseif($team->verification_status == 'pending')
                                <span class="status-badge badge-pending">
                                    <i class="fas fa-clock"></i> Pending
                                </span>
                                @else
                                <span class="status-badge badge-rejected">
                                    <i class="fas fa-times-circle"></i> Ditolak
                                </span>
                                @endif
                            </td>
                            <td>
                                <div class="small text-muted">
                                    {{ $team->created_at->format('d/m/Y') }}
                                </div>
                                <div class="small">
                                    {{ $team->registered_by }}
                                </div>
                            </td>
                            <td>
                                <div class="action-buttons justify-content-center">
                                    <!-- View Button -->
                                    <a href="{{ route('admin.team-list.show', $team->team_id) }}" 
                                       class="btn btn-primary btn-action"
                                       title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <!-- Verification Actions -->
                                    @if($team->verification_status == 'pending')
                                    <form action="{{ url('/admin/team/' . $team->team_id . '/verify') }}" 
                                          method="POST" 
                                          class="d-inline">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" 
                                                class="btn btn-success btn-action"
                                                title="Verifikasi"
                                                onclick="return confirm('Verifikasi tim ini?')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>

                                    <form action="{{ url('/admin/team/' . $team->team_id . '/reject') }}" 
                                          method="POST" 
                                          class="d-inline">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" 
                                                class="btn btn-danger btn-action"
                                                title="Tolak"
                                                onclick="return confirm('Tolak tim ini?')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                    @endif

                                    <!-- Lock/Unlock -->
                                    @if($team->locked_status != 'locked')
                                    <form action="{{ url('/admin/team/' . $team->team_id . '/lock') }}" 
                                          method="POST" 
                                          class="d-inline">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" 
                                                class="btn btn-warning btn-action"
                                                title="Kunci"
                                                onclick="return confirm('Kunci tim ini?')">
                                            <i class="fas fa-lock"></i>
                                        </button>
                                    </form>
                                    @else
                                    <form action="{{ url('/admin/team/' . $team->team_id . '/unlock') }}" 
                                          method="POST" 
                                          class="d-inline">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" 
                                                class="btn btn-info btn-action"
                                                title="Buka"
                                                onclick="return confirm('Buka kunci tim ini?')">
                                            <i class="fas fa-unlock"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <h5 class="empty-title">Tidak ada data tim</h5>
                                    <p class="empty-text">
                                        Belum ada tim yang terdaftar. 
                                        Data tim akan muncul di sini setelah pendaftaran.
                                    </p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Table Footer -->
            <div class="table-footer">
                <div class="pagination-info">
                    Menampilkan {{ $teamList->count() }} data
                </div>
                <div class="text-muted small">
                    {{ now()->format('d/m/Y H:i') }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Toggle compact view
    function toggleCompactView() {
        const table = document.getElementById('teamTable');
        table.classList.toggle('compact-view');
        
        const btn = event.target.closest('button');
        if (table.classList.contains('compact-view')) {
            btn.innerHTML = '<i class="fas fa-expand-alt me-1"></i>Normal';
            btn.classList.remove('btn-outline-secondary');
            btn.classList.add('btn-secondary');
        } else {
            btn.innerHTML = '<i class="fas fa-compress-alt me-1"></i>Compact';
            btn.classList.remove('btn-secondary');
            btn.classList.add('btn-outline-secondary');
        }
    }
    
    // Quick status filter
    function filterByStatus(status) {
        const rows = document.querySelectorAll('#teamTable tbody tr');
        rows.forEach(row => {
            if (status === 'all') {
                row.style.display = '';
            } else {
                const statusCell = row.querySelector('td:nth-child(6)');
                if (statusCell) {
                    const badge = statusCell.querySelector('.status-badge');
                    if (badge && badge.classList.contains(`badge-${status}`)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                }
            }
        });
    }
    
    // Initialize DataTable if you have jQuery DataTables
    document.addEventListener('DOMContentLoaded', function() {
        // Add row click for mobile
        if (window.innerWidth < 768) {
            const rows = document.querySelectorAll('#teamTable tbody tr');
            rows.forEach(row => {
                row.style.cursor = 'pointer';
                row.addEventListener('click', function(e) {
                    if (!e.target.closest('.action-buttons')) {
                        const viewBtn = this.querySelector('a[title="Detail"]');
                        if (viewBtn) viewBtn.click();
                    }
                });
            });
        }
    });
</script>
@endpush
@endsection