@extends('admin.layouts.app')

@section('title', 'Dashboard - HSBL Administrator')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 pt-2">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <div class="bg-primary bg-opacity-10 p-1 rounded-2">
                    <i class="fas fa-chart-pie text-primary" style="font-size: 0.9rem;"></i>
                </div>
                <h1 class="fw-semibold mb-0" style="font-size: 1.25rem; color: #1e293b; letter-spacing: -0.01em;">
                    Dashboard
                </h1>
            </div>
            <div class="d-flex align-items-center gap-3">
                <p class="text-secondary mb-0 d-flex align-items-center" style="font-size: 0.8rem;">
                    <i class="far fa-calendar me-1 opacity-50" style="font-size: 0.7rem;"></i>
                    {{ now()->format('l, d F Y') }}
                </p>
                <p class="text-secondary mb-0 d-flex align-items-center" id="liveClock" style="font-size: 0.8rem;">
                    <i class="far fa-clock me-1 opacity-50" style="font-size: 0.7rem;"></i>
                    <span>00:00:00</span>
                </p>
            </div>
        </div>
        <div class="mt-2 mt-sm-0">
            <span class="badge bg-white text-secondary border px-3 py-1.5 rounded-pill shadow-sm" style="font-size: 0.75rem; font-weight: 400;">
                <span class="bg-success" style="display: inline-block; width: 6px; height: 6px; border-radius: 50%; margin-right: 6px;"></span>
                System Online
            </span>
        </div>
    </div>

    <!-- STATS CARDS - 3 Cards (Payments dihapus) -->
    <div class="row g-2 mb-4">
        <!-- Total Teams -->
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm h-100 stats-card">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 p-2 rounded-2" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-users text-primary" style="font-size: 1.1rem;"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="text-secondary text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.02em;">Total Teams</span>
                                <span class="badge bg-success bg-opacity-10 text-success border-0" style="font-size: 0.65rem;">+{{ $data['team_growth'] }} new</span>
                            </div>
                            <div class="d-flex align-items-baseline mt-1">
                                <span class="fw-semibold" style="font-size: 1.6rem; color: #0f172a;">{{ number_format($data['total_teams']) }}</span>
                                <span class="text-secondary ms-2" style="font-size: 0.75rem;">
                                    {{ number_format($data['basket_putra'] ?? 0) }}P • {{ number_format($data['basket_putri'] ?? 0) }}P • {{ number_format($data['dancer_teams'] ?? 0) }}D
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Verification -->
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm h-100 stats-card">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 p-2 rounded-2" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-clipboard-check text-warning" style="font-size: 1.1rem;"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="text-secondary text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.02em;">Verification</span>
                                <span class="text-secondary" style="font-size: 0.7rem;">{{ $data['verification_percentage'] }}%</span>
                            </div>
                            <div class="d-flex align-items-baseline mt-1">
                                <span class="fw-semibold" style="font-size: 1.6rem; color: #0f172a;">{{ number_format($data['verified_teams']) }}</span>
                                <span class="text-secondary ms-2" style="font-size: 0.75rem;">/ {{ number_format($data['total_teams']) }}</span>
                                @if($data['pending_verification'] > 0)
                                <span class="text-warning ms-2" style="font-size: 0.75rem;">({{ $data['pending_verification'] }} pending)</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lock Status -->
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm h-100 stats-card">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-opacity-10 p-2 rounded-2" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-lock text-info" style="font-size: 1.1rem;"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="text-secondary text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.02em;">Team Status</span>
                            </div>
                            <div class="d-flex align-items-baseline mt-1">
                                <span class="fw-semibold" style="font-size: 1.6rem; color: #0f172a;">{{ number_format($data['locked_teams']) }}</span>
                                <span class="text-secondary ms-2" style="font-size: 0.75rem;">locked</span>
                                <span class="text-secondary ms-2" style="font-size: 0.75rem;">• {{ $data['unlocked_teams'] }} unlocked</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CONTENT STATS - 2 Cards (School & Participants dihapus) -->
    <div class="row g-2 mb-4">
        <!-- CONTENT STATS - 2 Cards -->
        <!-- CONTENT STATS - 2 Cards -->
        <div class="row g-2 mb-4">
            <!-- Published Content - News, Photos, Videos, Sponsors -->
            <div class="col-xl-6 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-success bg-opacity-10 p-1 rounded-2 me-2">
                                <i class="fas fa-layer-group text-success" style="font-size: 0.9rem;"></i>
                            </div>
                            <span class="fw-medium" style="font-size: 0.9rem; color: #1e293b;">Published Content</span>
                        </div>

                        <!-- First Row - News, Photos, Videos -->
                        <div class="row g-2 mb-2">
                            <div class="col-4">
                                <div class="p-2 bg-light rounded-2">
                                    <span class="text-secondary d-block" style="font-size: 0.65rem;">News</span>
                                    <span class="fw-semibold" style="font-size: 1.4rem; color: #0f172a;">{{ number_format($data['total_news']) }}</span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 bg-light rounded-2">
                                    <span class="text-secondary d-block" style="font-size: 0.65rem;">Photos</span>
                                    <span class="fw-semibold" style="font-size: 1.4rem; color: #0f172a;">{{ number_format($data['total_photos'] ?? 0) }}</span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 bg-light rounded-2">
                                    <span class="text-secondary d-block" style="font-size: 0.65rem;">Videos</span>
                                    <span class="fw-semibold" style="font-size: 1.4rem; color: #0f172a;">{{ number_format($data['total_videos'] ?? 0) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Second Row - Sponsors (full width) -->
                        <div class="row g-2">
                            <div class="col-12">
                                <div class="p-2 bg-light rounded-2 d-flex align-items-center justify-content-between">
                                    <div>
                                        <span class="text-secondary d-block" style="font-size: 0.65rem;">Sponsors</span>
                                        <span class="fw-semibold" style="font-size: 1.4rem; color: #0f172a;">{{ number_format($data['total_sponsors']) }}</span>
                                    </div>
                                    <div class="bg-white p-2 rounded-2">
                                        <i class="fas fa-handshake text-secondary" style="font-size: 1.2rem;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Match Stats -->
            <div class="col-xl-6 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-warning bg-opacity-10 p-1 rounded-2 me-2">
                                <i class="fas fa-basketball-ball text-warning" style="font-size: 0.9rem;"></i>
                            </div>
                            <span class="fw-medium" style="font-size: 0.9rem; color: #1e293b;">Matches Overview</span>
                        </div>
                        <div class="row g-2">
                            <div class="col-4">
                                <div class="p-2 bg-light rounded-2">
                                    <span class="text-secondary d-block" style="font-size: 0.65rem;">Total</span>
                                    <span class="fw-semibold" style="font-size: 1.4rem; color: #0f172a;">{{ number_format($data['total_matches']) }}</span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 bg-light rounded-2">
                                    <span class="text-secondary d-block" style="font-size: 0.65rem;">Upcoming</span>
                                    <span class="fw-semibold" style="font-size: 1.4rem; color: #0f172a;">{{ number_format($data['upcoming_matches']) }}</span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 bg-light rounded-2">
                                    <span class="text-secondary d-block" style="font-size: 0.65rem;">Completed</span>
                                    <span class="fw-semibold" style="font-size: 1.4rem; color: #0f172a;">{{ number_format($data['completed_matches']) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT ROW - Recent Teams & Quick Actions -->
    <div class="row g-2">
        <!-- Recent Teams Table -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0 pt-2 pb-0 px-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-medium" style="font-size: 0.9rem; color: #1e293b;">
                            <i class="fas fa-history me-1 text-primary" style="font-size: 0.85rem;"></i> Recent Registrations
                        </span>
                        <span class="badge bg-light text-secondary border-0" style="font-size: 0.7rem;">Live</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 py-2 ps-3" style="font-size: 0.7rem; font-weight: 500; color: #64748b;">Time</th>
                                    <th class="border-0 py-2" style="font-size: 0.7rem; font-weight: 500; color: #64748b;">Team</th>
                                    <th class="border-0 py-2" style="font-size: 0.7rem; font-weight: 500; color: #64748b;">Category</th>
                                    <th class="border-0 py-2" style="font-size: 0.7rem; font-weight: 500; color: #64748b;">Status</th>
                                    <th class="border-0 py-2 pe-3 text-end" style="font-size: 0.7rem; font-weight: 500; color: #64748b;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data['recent_teams'] ?? [] as $team)
                                <tr>
                                    <td class="ps-3" style="color: #64748b;">{{ $team->created_at->diffForHumans() }}</td>
                                    <td>
                                        <span class="fw-medium" style="color: #0f172a;">{{ $team->team_name ?? $team->school_name }}</span>
                                        <small class="d-block text-secondary" style="font-size: 0.7rem;">{{ $team->school->name ?? $team->school_name }}</small>
                                    </td>
                                    <td>
                                        @if($team->team_category == 'Basket Putra')
                                        <span class="badge bg-primary bg-opacity-10 text-primary border-0" style="font-size: 0.7rem; font-weight: 400;">Putra</span>
                                        @elseif($team->team_category == 'Basket Putri')
                                        <span class="badge bg-danger bg-opacity-10 text-danger border-0" style="font-size: 0.7rem; font-weight: 400;">Putri</span>
                                        @else
                                        <span class="badge bg-info bg-opacity-10 text-info border-0" style="font-size: 0.7rem; font-weight: 400;">Dancer</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            @if($team->verification_status == 'verified')
                                            <span class="badge bg-success bg-opacity-10 text-success border-0" style="font-size: 0.7rem; font-weight: 400;">Verif</span>
                                            @else
                                            <span class="badge bg-warning bg-opacity-10 text-warning border-0" style="font-size: 0.7rem; font-weight: 400;">Pending</span>
                                            @endif

                                            @if($team->payment_status == 'paid')
                                            <span class="badge bg-success bg-opacity-10 text-success border-0" style="font-size: 0.7rem; font-weight: 400;">Paid</span>
                                            @else
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary border-0" style="font-size: 0.7rem; font-weight: 400;">Unpaid</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="pe-3 text-end">
                                        <a href="#" class="text-primary" style="font-size: 0.75rem; text-decoration: none;">View</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-secondary" style="font-size: 0.8rem;">
                                        No recent registrations
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0 pb-2 px-3">
                    <a href="{{ route('admin.tv_team_list') }}" class="text-primary" style="font-size: 0.75rem; text-decoration: none;">
                        View all <i class="fas fa-arrow-right ms-1" style="font-size: 0.65rem;"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Right Column - Quick Actions yang lebih visible -->
        <div class="col-lg-4">
            <div class="row g-2">
                <!-- Progress Bar -->
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="fw-medium" style="font-size: 0.85rem; color: #1e293b;">Progress</span>
                                <span class="text-secondary" style="font-size: 0.75rem;">{{ $data['verification_percentage'] }}%</span>
                            </div>
                            <div class="progress mb-2" style="height: 6px;">
                                <div class="progress-bar bg-success" style="width: {{ $data['verification_percentage'] }}%"></div>
                            </div>
                            <div class="d-flex justify-content-between text-secondary" style="font-size: 0.7rem;">
                                <span>{{ number_format($data['verified_teams']) }} verified</span>
                                <span>{{ number_format($data['pending_verification']) }} pending</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions - REDESIGNED lebih visible -->
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-3">
                            <span class="fw-medium d-block mb-3" style="font-size: 0.9rem; color: #1e293b;">
                                <i class="fas fa-bolt me-1 text-primary"></i> Quick Actions
                            </span>

                            <div class="d-flex flex-column gap-2">
                                <div class="row g-2">
                                    <div class="col-6">
                                        <a href="{{ route('admin.pub_schedule.create') }}" class="btn btn-outline-primary w-100 py-2 d-flex align-items-center justify-content-center" style="font-size: 0.8rem; border-radius: 8px;">
                                            <i class="fas fa-calendar-plus me-2"></i> Schedule
                                        </a>
                                    </div>
                                    <div class="col-6">
                                        <a href="{{ route('admin.pub_result.create') }}" class="btn btn-outline-success w-100 py-2 d-flex align-items-center justify-content-center" style="font-size: 0.8rem; border-radius: 8px;">
                                            <i class="fas fa-file-alt me-2"></i> Result
                                        </a>
                                    </div>
                                </div>

                                <div class="row g-2">
                                    <div class="col-6">
                                        <a href="{{ route('admin.news.create') }}" class="btn btn-outline-info w-100 py-2 d-flex align-items-center justify-content-center" style="font-size: 0.8rem; border-radius: 8px;">
                                            <i class="fas fa-newspaper me-2"></i> News
                                        </a>
                                    </div>
                                    <div class="col-6">
                                        <a href="{{ route('admin.tv_team_verification') }}" class="btn btn-outline-warning w-100 py-2 d-flex align-items-center justify-content-center position-relative" style="font-size: 0.8rem; border-radius: 8px;">
                                            <i class="fas fa-user-check me-2"></i> Verify
                                            @if($data['pending_verification'] > 0)
                                            <span class="badge bg-danger rounded-pill ms-1" style="font-size: 0.6rem;">{{ $data['pending_verification'] }}</span>
                                            @endif
                                        </a>
                                    </div>
                                </div>

                                <div class="row g-2">
                                    <div class="col-6">
                                        <a href="{{ route('admin.gallery.photos.create') }}" class="btn btn-outline-secondary w-100 py-2 d-flex align-items-center justify-content-center" style="font-size: 0.8rem; border-radius: 8px;">
                                            <i class="fas fa-images me-2"></i> Gallery
                                        </a>
                                    </div>
                                    <div class="col-6">
                                        <a href="{{ route('admin.sponsor.sponsor') }}" class="btn btn-outline-dark w-100 py-2 d-flex align-items-center justify-content-center" style="font-size: 0.8rem; border-radius: 8px;">
                                            <i class="fas fa-handshake me-2"></i> Sponsors
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- System Status -->
    <div class="row mt-3">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body py-2 px-3">
                    <div class="d-flex align-items-center gap-3" style="font-size: 0.7rem;">
                        <span class="text-secondary">System</span>
                        <span class="d-flex align-items-center gap-1"><span class="bg-success" style="width: 6px; height: 6px; border-radius: 50%;"></span> Database</span>
                        <span class="d-flex align-items-center gap-1"><span class="bg-success" style="width: 6px; height: 6px; border-radius: 50%;"></span> Storage</span>
                        <span class="d-flex align-items-center gap-1"><span class="bg-success" style="width: 6px; height: 6px; border-radius: 50%;"></span> Cache</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Typography System */
    :root {
        --font-size-xs: 0.65rem;
        --font-size-sm: 0.7rem;
        --font-size-base: 0.8rem;
        --font-size-lg: 0.9rem;
        --font-size-xl: 1rem;
        --font-size-2xl: 1.1rem;
        --font-size-3xl: 1.35rem;
        --font-size-4xl: 1.6rem;

        --color-text-primary: #0f172a;
        --color-text-secondary: #64748b;
        --color-text-tertiary: #94a3b8;
        --color-border: #f1f5f9;
    }

    /* Card Styles */
    .card {
        border-radius: 10px !important;
        transition: all 0.2s ease;
    }

    .stats-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 12px -4px rgba(0, 0, 0, 0.05) !important;
    }

    /* Table Styles */
    .table th {
        font-weight: 500;
        color: var(--color-text-secondary);
        border-bottom: 1px solid var(--color-border);
    }

    .table td {
        padding: 0.6rem 0.25rem;
        border-bottom: 1px solid var(--color-border);
        color: var(--color-text-primary);
    }

    .table tr:last-child td {
        border-bottom: none;
    }

    /* Badge Styles */
    .badge {
        font-weight: 400;
        padding: 0.25rem 0.6rem;
        border-radius: 20px;
    }

    /* Button Styles - Enhanced untuk quick actions */
    .btn-outline-primary,
    .btn-outline-success,
    .btn-outline-info,
    .btn-outline-warning,
    .btn-outline-secondary,
    .btn-outline-dark {
        border-width: 1.5px;
        font-weight: 500;
        transition: all 0.2s;
    }

    .btn-outline-primary {
        color: #2563eb;
        border-color: #2563eb;
        background-color: #f8fafc;
    }

    .btn-outline-primary:hover {
        background-color: #2563eb;
        color: white;
        border-color: #2563eb;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(37, 99, 235, 0.1);
    }

    .btn-outline-success {
        color: #16a34a;
        border-color: #16a34a;
        background-color: #f8fafc;
    }

    .btn-outline-success:hover {
        background-color: #16a34a;
        color: white;
        border-color: #16a34a;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(22, 163, 74, 0.1);
    }

    .btn-outline-info {
        color: #0891b2;
        border-color: #0891b2;
        background-color: #f8fafc;
    }

    .btn-outline-info:hover {
        background-color: #0891b2;
        color: white;
        border-color: #0891b2;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(8, 145, 178, 0.1);
    }

    .btn-outline-warning {
        color: #d97706;
        border-color: #d97706;
        background-color: #f8fafc;
    }

    .btn-outline-warning:hover {
        background-color: #d97706;
        color: white;
        border-color: #d97706;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(217, 119, 6, 0.1);
    }

    .btn-outline-secondary {
        color: #64748b;
        border-color: #64748b;
        background-color: #f8fafc;
    }

    .btn-outline-secondary:hover {
        background-color: #64748b;
        color: white;
        border-color: #64748b;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(100, 116, 139, 0.1);
    }

    .btn-outline-dark {
        color: #334155;
        border-color: #334155;
        background-color: #f8fafc;
    }

    .btn-outline-dark:hover {
        background-color: #334155;
        color: white;
        border-color: #334155;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(51, 65, 85, 0.1);
    }

    /* Progress Bar */
    .progress {
        background-color: #f1f5f9;
        border-radius: 30px;
        overflow: hidden;
    }

    /* Spacing */
    .gap-1 {
        gap: 0.25rem;
    }

    .gap-2 {
        gap: 0.5rem;
    }

    .gap-3 {
        gap: 0.75rem;
    }

    /* Text Utilities */
    .text-secondary {
        color: var(--color-text-secondary) !important;
    }

    /* Border */
    .border-top {
        border-color: var(--color-border) !important;
    }
</style>
@endpush

@push('scripts')
<script>
    // Live clock
    function updateClock() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('id-ID', {
            hour12: false,
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
        document.querySelector('#liveClock span').innerText = timeString;
    }
    setInterval(updateClock, 1000);
    updateClock();
</script>
@endpush