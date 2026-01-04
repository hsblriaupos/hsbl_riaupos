@extends('admin.layouts.app')

@section('title', 'Dashboard - HSBL Administrator')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-1 mt-2" style="font-size: 1.4rem; font-weight: 600;">
                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
            </h1>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">
                Welcome back, Administrator
            </p>
        </div>
        <div class="text-muted" style="font-size: 0.9rem;">
            <i class="fas fa-calendar me-1"></i> {{ now()->format('l, d F Y') }}
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary h-100">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div style="font-size: 0.85rem; color: #4e73df; font-weight: 500;">
                                Total Teams
                            </div>
                            <div class="mt-1" style="font-size: 1.5rem; font-weight: 600; color: #2e3a59;">
                                24
                            </div>
                            <div class="mt-2">
                                <span class="badge bg-success" style="font-size: 0.75rem;">+3 new</span>
                            </div>
                        </div>
                        <div>
                            <i class="fas fa-users fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success h-100">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div style="font-size: 0.85rem; color: #1cc88a; font-weight: 500;">
                                Pending Verification
                            </div>
                            <div class="mt-1" style="font-size: 1.5rem; font-weight: 600; color: #2e3a59;">
                                8
                            </div>
                            <div class="mt-2">
                                <a href="{{ route('admin.tv_team_verification') }}" class="text-decoration-none" style="font-size: 0.8rem;">
                                    Verify now <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                        <div>
                            <i class="fas fa-clipboard-check fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning h-100">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div style="font-size: 0.85rem; color: #f6c23e; font-weight: 500;">
                                Upcoming Matches
                            </div>
                            <div class="mt-1" style="font-size: 1.5rem; font-weight: 600; color: #2e3a59;">
                                5
                            </div>
                            <div class="mt-2">
                                <span class="badge bg-info" style="font-size: 0.75rem;">Next: Tomorrow</span>
                            </div>
                        </div>
                        <div>
                            <i class="fas fa-calendar-alt fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info h-100">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div style="font-size: 0.85rem; color: #36b9cc; font-weight: 500;">
                                Total News
                            </div>
                            <div class="mt-1" style="font-size: 1.5rem; font-weight: 600; color: #2e3a59;">
                                15
                            </div>
                            <div class="mt-2">
                                <a href="{{ route('admin.news.index') }}" class="text-decoration-none" style="font-size: 0.8rem;">
                                    Manage <i class="fas fa-edit ms-1"></i>
                                </a>
                            </div>
                        </div>
                        <div>
                            <i class="fas fa-newspaper fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="row">
        <!-- Recent Activity -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header py-3" style="background-color: #f8f9fa; border-bottom: 1px solid #e3e6f0;">
                    <h6 class="mb-0" style="font-size: 1rem; font-weight: 600; color: #4e73df;">
                        <i class="fas fa-history me-2"></i> Recent Activity
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th style="font-size: 0.85rem; font-weight: 500;">Time</th>
                                    <th style="font-size: 0.85rem; font-weight: 500;">Activity</th>
                                    <th style="font-size: 0.85rem; font-weight: 500;">User</th>
                                    <th style="font-size: 0.85rem; font-weight: 500;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="font-size: 0.85rem;">10:30 AM</td>
                                    <td style="font-size: 0.85rem;">Team "SMA 1 Pekanbaru" registered</td>
                                    <td style="font-size: 0.85rem;">System</td>
                                    <td><span class="badge bg-warning" style="font-size: 0.75rem;">Pending</span></td>
                                </tr>
                                <tr>
                                    <td style="font-size: 0.85rem;">09:15 AM</td>
                                    <td style="font-size: 0.85rem;">News "HSBL Opening Ceremony" published</td>
                                    <td style="font-size: 0.85rem;">Admin</td>
                                    <td><span class="badge bg-success" style="font-size: 0.75rem;">Published</span></td>
                                </tr>
                                <tr>
                                    <td style="font-size: 0.85rem;">Yesterday</td>
                                    <td style="font-size: 0.85rem;">Sponsor "Honda" added</td>
                                    <td style="font-size: 0.85rem;">Admin</td>
                                    <td><span class="badge bg-info" style="font-size: 0.75rem;">Active</span></td>
                                </tr>
                                <tr>
                                    <td style="font-size: 0.85rem;">2 days ago</td>
                                    <td style="font-size: 0.85rem;">Schedule for Week 3 updated</td>
                                    <td style="font-size: 0.85rem;">Admin</td>
                                    <td><span class="badge bg-primary" style="font-size: 0.75rem;">Updated</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header py-3" style="background-color: #f8f9fa; border-bottom: 1px solid #e3e6f0;">
                    <h6 class="mb-0" style="font-size: 1rem; font-weight: 600; color: #4e73df;">
                        <i class="fas fa-bolt me-2"></i> Quick Actions
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="d-grid gap-2 mb-3">
                        <a href="{{ route('admin.news.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-2"></i> Add New News
                        </a>
                        <a href="{{ route('admin.sponsor.sponsor') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-handshake me-2"></i> Manage Sponsors
                        </a>
                        <a href="{{ route('admin.pub_schedule') }}" class="btn btn-info btn-sm">
                            <i class="fas fa-calendar-plus me-2"></i> Add Match Schedule
                        </a>
                        <a href="{{ route('admin.tv_team_verification') }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-user-check me-2"></i> Verify Teams
                        </a>
                        <a href="{{ route('admin.term_conditions.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-file-contract me-2"></i> Update Terms & Conditions
                        </a>
                    </div>
                    
                    <hr class="my-3">
                    
                    <h6 class="mb-2" style="font-size: 0.9rem; font-weight: 600;">System Status</h6>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span style="font-size: 0.85rem;">Database</span>
                            <span class="text-success" style="font-size: 0.85rem;"><i class="fas fa-check-circle"></i> Online</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-success" style="width: 100%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span style="font-size: 0.85rem;">Storage</span>
                            <span style="font-size: 0.85rem;">65% used</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-warning" style="width: 65%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        border: 1px solid #e3e6f0;
        border-radius: 6px;
        transition: all 0.2s;
    }
    
    .card:hover {
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    .border-left-primary {
        border-left: 4px solid #4e73df !important;
    }
    
    .border-left-success {
        border-left: 4px solid #1cc88a !important;
    }
    
    .border-left-warning {
        border-left: 4px solid #f6c23e !important;
    }
    
    .border-left-info {
        border-left: 4px solid #36b9cc !important;
    }
    
    .btn-sm {
        padding: 6px 12px;
        font-size: 0.85rem;
    }
    
    .table th,
    .table td {
        padding: 10px 12px;
    }
</style>
@endpush

@push('scripts')
<script>
    // Simple auto refresh for dashboard
    setTimeout(() => {
        location.reload();
    }, 120000); // Refresh every 2 minutes
</script>
@endpush