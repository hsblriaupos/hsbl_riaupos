@extends('admin.layouts.app')

@section('title', 'Statistics - HSBL Admin')

@section('content')
<div class="admin-main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="admin-page-title">
                <i class="fas fa-chart-line me-2"></i>Statistics Dashboard
            </h1>
            <p class="admin-page-subtitle">
                Comprehensive analytics and insights for HSBL tournament
            </p>
        </div>
    </div>

    <!-- Coming Soon Section -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-tools me-2 text-primary"></i>
                        Statistics Dashboard - Coming Soon
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h4 class="text-primary mb-3">Advanced Analytics Dashboard</h4>
                                <p class="text-muted">
                                    We're currently developing a comprehensive statistics dashboard with real-time analytics, 
                                    detailed performance metrics, and data visualization tools specifically designed for 
                                    tournament administrators.
                                </p>
                                
                                <div class="mt-4">
                                    <h6 class="mb-3">
                                        <i class="fas fa-lightbulb text-warning me-2"></i>
                                        Planned Features
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="d-flex align-items-start">
                                                <i class="fas fa-check-circle text-success mt-1 me-2"></i>
                                                <div>
                                                    <small class="fw-bold">Real-time Analytics</small>
                                                    <p class="text-muted mb-0 small">Live match statistics and trends</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="d-flex align-items-start">
                                                <i class="fas fa-check-circle text-success mt-1 me-2"></i>
                                                <div>
                                                    <small class="fw-bold">Performance Metrics</small>
                                                    <p class="text-muted mb-0 small">Detailed team/player analytics</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="d-flex align-items-start">
                                                <i class="fas fa-check-circle text-success mt-1 me-2"></i>
                                                <div>
                                                    <small class="fw-bold">Data Export</small>
                                                    <p class="text-muted mb-0 small">Export statistics to PDF/Excel</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="d-flex align-items-start">
                                                <i class="fas fa-check-circle text-success mt-1 me-2"></i>
                                                <div>
                                                    <small class="fw-bold">Custom Reports</small>
                                                    <p class="text-muted mb-0 small">Generate custom statistical reports</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="text-center p-4">
                                <div class="mb-4">
                                    <div class="position-relative d-inline-block">
                                        <i class="fas fa-chart-bar fa-6x text-primary"></i>
                                        <div class="position-absolute top-0 start-100 translate-middle">
                                            <span class="badge bg-warning p-2">COMING SOON</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <h6 class="text-muted mb-3">Development Progress</h6>
                                    <div class="progress" style="height: 12px;">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" 
                                             role="progressbar" 
                                             style="width: 65%" 
                                             aria-valuenow="65" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100">
                                            <span class="fw-bold">65%</span>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <small class="text-muted">
                                            <i class="fas fa-calendar-alt me-1"></i>
                                            Estimated completion: Q2 2024
                                        </small>
                                    </div>
                                </div>
                                
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <small>
                                        The statistics module is currently in active development. 
                                        Basic statistics will be available soon.
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Current Available Links -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="mb-3">
                                <i class="fas fa-external-link-alt me-2 text-success"></i>
                                Available Data Pages
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <a href="{{ route('admin.pub_result.index') }}" class="text-decoration-none">
                                        <div class="card border h-100 hover-shadow">
                                            <div class="card-body text-center">
                                                <i class="fas fa-trophy fa-2x text-primary mb-3"></i>
                                                <h6>Match Results</h6>
                                                <p class="small text-muted mb-0">View published match outcomes and scores</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a href="{{ route('admin.pub_schedule.index') }}" class="text-decoration-none">
                                        <div class="card border h-100 hover-shadow">
                                            <div class="card-body text-center">
                                                <i class="fas fa-calendar-alt fa-2x text-success mb-3"></i>
                                                <h6>Schedules</h6>
                                                <p class="small text-muted mb-0">Manage tournament schedules</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a href="{{ route('admin.tv_team_list') }}" class="text-decoration-none">
                                        <div class="card border h-100 hover-shadow">
                                            <div class="card-body text-center">
                                                <i class="fas fa-users fa-2x text-info mb-3"></i>
                                                <h6>Teams</h6>
                                                <p class="small text-muted mb-0">View registered teams and players</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>
                                Last updated: {{ now()->format('d M Y, H:i') }}
                            </small>
                        </div>
                        <div>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .progress-bar-animated {
        animation: progress-bar-stripes 1s linear infinite;
    }
    
    @keyframes progress-bar-stripes {
        from { background-position: 1rem 0; }
        to { background-position: 0 0; }
    }
    
    .border-start {
        border-left-width: 4px !important;
    }
    
    .hover-shadow:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
        transition: all 0.3s ease;
    }
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Statistics dashboard loaded - Coming Soon');
    });
</script>
@endpush
@endsection