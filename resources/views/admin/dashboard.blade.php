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
                Welcome back, {{ Auth::user()->name ?? 'Administrator' }}
            </p>
        </div>
        <div class="text-muted" style="font-size: 0.9rem;">
            <i class="fas fa-calendar me-1"></i> {{ now()->format('l, d F Y') }}
            <span class="badge bg-primary ms-2" id="liveClock"></span>
        </div>
    </div>

    <!-- Stats Cards Row 1 -->
    <div class="row mb-4">
        <!-- Total Teams -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary h-100">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div style="font-size: 0.85rem; color: #4e73df; font-weight: 500;">
                                Total Teams
                            </div>
                            <div class="mt-1" style="font-size: 1.5rem; font-weight: 600; color: #2e3a59;">
                                {{ number_format($data['total_teams']) }}
                            </div>
                            <div class="mt-2">
                                @if($data['team_growth'] > 0)
                                    <span class="badge bg-success" style="font-size: 0.75rem;">
                                        <i class="fas fa-arrow-up me-1"></i>+{{ $data['team_growth'] }} new
                                    </span>
                                    <small class="text-muted ms-1">this month</small>
                                @endif
                                <span class="badge bg-info ms-1" style="font-size: 0.75rem;">
                                    <i class="fas fa-tag me-1"></i>
                                    {{ number_format($data['basket_putra'] ?? 0) }} P • 
                                    {{ number_format($data['basket_putri'] ?? 0) }} P • 
                                    {{ number_format($data['dancer_teams'] ?? 0) }} D
                                </span>
                            </div>
                        </div>
                        <div>
                            <i class="fas fa-users fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Verification -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning h-100">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div style="font-size: 0.85rem; color: #f6c23e; font-weight: 500;">
                                Pending Verification
                            </div>
                            <div class="mt-1" style="font-size: 1.5rem; font-weight: 600; color: #2e3a59;">
                                {{ number_format($data['pending_verification']) }}
                            </div>
                            <div class="mt-2">
                                <a href="{{ route('admin.tv_team_verification') }}" class="text-decoration-none" style="font-size: 0.8rem;">
                                    Verify now 
                                    @if($data['pending_verification'] > 0)
                                        <span class="badge bg-danger ms-1">{{ $data['pending_verification'] }} need action</span>
                                    @else
                                        <i class="fas fa-check-circle ms-1 text-success"></i>
                                    @endif
                                </a>
                            </div>
                        </div>
                        <div>
                            <i class="fas fa-clock fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Status -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success h-100">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div style="font-size: 0.85rem; color: #1cc88a; font-weight: 500;">
                                Payment Status
                            </div>
                            <div class="mt-1" style="font-size: 1.5rem; font-weight: 600; color: #2e3a59;">
                                {{ number_format($data['paid_teams']) }} / {{ number_format($data['total_teams']) }}
                            </div>
                            <div class="mt-2">
                                <span class="badge bg-success" style="font-size: 0.75rem;">
                                    <i class="fas fa-check-circle me-1"></i> {{ number_format($data['leader_paid']) }} Leader Paid
                                </span>
                                @if($data['pending_payment'] > 0)
                                    <span class="badge bg-warning" style="font-size: 0.75rem;">
                                        {{ number_format($data['pending_payment']) }} Pending
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div>
                            <i class="fas fa-credit-card fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Matches -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info h-100">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div style="font-size: 0.85rem; color: #36b9cc; font-weight: 500;">
                                Upcoming Matches
                            </div>
                            <div class="mt-1" style="font-size: 1.5rem; font-weight: 600; color: #2e3a59;">
                                {{ number_format($data['upcoming_matches']) }}
                            </div>
                            <div class="mt-2">
                                <span class="badge bg-primary" style="font-size: 0.75rem;">
                                    {{ number_format($data['completed_matches']) }} Completed
                                </span>
                                <span class="badge bg-secondary" style="font-size: 0.75rem;">
                                    {{ number_format($data['total_matches']) }} Total
                                </span>
                            </div>
                        </div>
                        <div>
                            <i class="fas fa-basketball-ball fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards Row 2 - Additional Stats -->
    <div class="row mb-4">
        <!-- Schools & Members -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary h-100">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div style="font-size: 0.85rem; color: #4e73df; font-weight: 500;">
                                Schools & Members
                            </div>
                            <div class="mt-1">
                                <span style="font-size: 1.2rem; font-weight: 600; color: #2e3a59;">
                                    {{ number_format($data['total_schools']) }}
                                </span>
                                <span style="font-size: 0.9rem; color: #666;">Schools</span>
                            </div>
                            <div class="mt-1">
                                <span style="font-size: 1rem; font-weight: 600; color: #2e3a59;">
                                    {{ number_format($data['total_players'] + $data['total_dancers'] + $data['total_officials']) }}
                                </span>
                                <span style="font-size: 0.85rem; color: #666;">Total Participants</span>
                            </div>
                            <div class="mt-2">
                                <small class="text-muted">
                                    <i class="fas fa-user me-1"></i>{{ number_format($data['total_players']) }} Players • 
                                    <i class="fas fa-user-graduate me-1"></i>{{ number_format($data['total_dancers']) }} Dancers • 
                                    <i class="fas fa-user-tie me-1"></i>{{ number_format($data['total_officials']) }} Officials
                                </small>
                            </div>
                        </div>
                        <div>
                            <i class="fas fa-school fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Team Lock Status -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-warning h-100">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div style="font-size: 0.85rem; color: #f6c23e; font-weight: 500;">
                                Team Lock Status
                            </div>
                            <div class="mt-1">
                                <span class="badge bg-success" style="font-size: 0.85rem; padding: 6px 12px;">
                                    <i class="fas fa-unlock me-1"></i> {{ number_format($data['unlocked_teams']) }} Unlocked
                                </span>
                                <span class="badge bg-danger" style="font-size: 0.85rem; padding: 6px 12px;">
                                    <i class="fas fa-lock me-1"></i> {{ number_format($data['locked_teams']) }} Locked
                                </span>
                            </div>
                            <div class="mt-3">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i> Locked teams cannot edit data
                                </small>
                            </div>
                        </div>
                        <div>
                            <i class="fas fa-lock fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

       <!-- Content Stats -->
<div class="col-xl-4 col-md-6 mb-4">
    <div class="card border-left-info h-100">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div style="font-size: 0.85rem; color: #36b9cc; font-weight: 500;">
                        Content Published
                    </div>
                    <div class="mt-2">
                        <div class="d-flex align-items-center mb-2">
                            <div class="me-4">
                                <span style="font-size: 1.3rem; font-weight: 600;">{{ number_format($data['total_news']) }}</span>
                                <span style="font-size: 0.85rem; color: #666;">News</span>
                            </div>
                            <div>
                                <span style="font-size: 1.3rem; font-weight: 600;">{{ number_format($data['total_awards']) }}</span>
                                <span style="font-size: 0.85rem; color: #666;">Awards</span>
                            </div>
                        </div>
                        <div>
                            <span style="font-size: 1rem; font-weight: 600;">{{ number_format($data['total_sponsors']) }}</span>
                            <span style="font-size: 0.85rem; color: #666;">Total Sponsors</span>
                        </div>
                    </div>
                </div>
                <div>
                    <i class="fas fa-handshake fa-2x text-info"></i>
                </div>
            </div>
        </div>
    </div>
</div>
    </div>

    <!-- Main Content Row -->
    <div class="row">
        <!-- Recent Activity with Dynamic Data -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0" style="font-size: 1rem; font-weight: 600; color: #4e73df;">
                        <i class="fas fa-history me-2"></i> Recent Team Registrations
                    </h6>
                    <span class="badge bg-light text-dark">
                        <i class="fas fa-sync-alt me-1"></i> Live
                    </span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th style="font-size: 0.8rem;">Time</th>
                                    <th style="font-size: 0.8rem;">Team</th>
                                    <th style="font-size: 0.8rem;">Category</th>
                                    <th style="font-size: 0.8rem;">Verification</th>
                                    <th style="font-size: 0.8rem;">Payment</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data['recent_teams'] ?? [] as $team)
                                <tr>
                                    <td style="font-size: 0.8rem;">{{ $team->created_at->diffForHumans() }}</td>
                                    <td style="font-size: 0.8rem;">
                                        <strong>{{ $team->team_name ?? $team->school_name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $team->school->name ?? $team->school_name }}</small>
                                    </td>
                                    <td style="font-size: 0.8rem;">
                                        @if($team->team_category == 'Basket Putra')
                                            <span class="badge bg-primary">Putra</span>
                                        @elseif($team->team_category == 'Basket Putri')
                                            <span class="badge bg-danger">Putri</span>
                                        @else
                                            <span class="badge bg-info">Dancer</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($team->verification_status == 'verified')
                                            <span class="badge bg-success">Verified</span>
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($team->payment_status == 'paid')
                                            <span class="badge bg-success">Paid</span>
                                        @elseif($team->payment_status == 'failed')
                                            <span class="badge bg-danger">Failed</span>
                                        @else
                                            <span class="badge bg-secondary">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-3">
                                        <i class="fas fa-inbox me-2"></i> No recent team registrations
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white py-2">
                    <a href="{{ route('admin.tv_team_list') }}" class="text-decoration-none small">
                        View all teams <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Progress & Stats -->
        <div class="col-lg-4 mb-4">
            <!-- Verification Progress Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3">
                    <h6 class="mb-0" style="font-size: 1rem; font-weight: 600; color: #4e73df;">
                        <i class="fas fa-check-circle me-2"></i> Verification Progress
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span style="font-size: 0.85rem;">Overall Verification</span>
                            <span class="fw-bold">{{ $data['verification_percentage'] ?? 0 }}%</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" style="width: {{ $data['verification_percentage'] ?? 0 }}%"></div>
                        </div>
                        <small class="text-muted">
                            {{ number_format($data['verified_teams']) }} of {{ number_format($data['total_teams']) }} teams verified
                        </small>
                    </div>
                    
                    <div class="mt-3">
                        <div class="d-flex justify-content-between">
                            <span style="font-size: 0.85rem;">
                                <i class="fas fa-circle text-success me-1"></i> Verified
                            </span>
                            <span class="fw-bold">{{ number_format($data['verified_teams']) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mt-1">
                            <span style="font-size: 0.85rem;">
                                <i class="fas fa-circle text-warning me-1"></i> Unverified
                            </span>
                            <span class="fw-bold">{{ number_format($data['pending_verification']) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mt-1">
                            <span style="font-size: 0.85rem;">
                                <i class="fas fa-circle text-primary me-1"></i> Locked
                            </span>
                            <span class="fw-bold">{{ number_format($data['locked_teams']) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Progress Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3">
                    <h6 class="mb-0" style="font-size: 1rem; font-weight: 600; color: #4e73df;">
                        <i class="fas fa-credit-card me-2"></i> Payment Progress
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span style="font-size: 0.85rem;">Payment Completion</span>
                            <span class="fw-bold">{{ $data['payment_percentage'] ?? 0 }}%</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" style="width: {{ $data['payment_percentage'] ?? 0 }}%"></div>
                        </div>
                    </div>
                    
                    <div class="row text-center mt-3">
                        <div class="col-4">
                            <div class="fw-bold text-success" style="font-size: 1.1rem;">{{ number_format($data['paid_teams']) }}</div>
                            <small class="text-muted">Paid</small>
                        </div>
                        <div class="col-4">
                            <div class="fw-bold text-warning" style="font-size: 1.1rem;">{{ number_format($data['pending_payment']) }}</div>
                            <small class="text-muted">Pending</small>
                        </div>
                        <div class="col-4">
                            <div class="fw-bold text-danger" style="font-size: 1.1rem;">{{ number_format($data['failed_payment']) }}</div>
                            <small class="text-muted">Failed</small>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fas fa-check-circle me-1"></i> {{ number_format($data['leader_paid']) }} Team Leaders have paid
                        </small>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow-sm">
                <div class="card-header py-3">
                    <h6 class="mb-0" style="font-size: 1rem; font-weight: 600; color: #4e73df;">
                        <i class="fas fa-bolt me-2"></i> Quick Actions
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.pub_schedule.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-calendar-plus me-2"></i> Add Schedule
                        </a>
                        <a href="{{ route('admin.pub_result.create') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-file-alt me-2"></i> Add Result
                        </a>
                        <a href="{{ route('admin.news.create') }}" class="btn btn-info btn-sm">
                            <i class="fas fa-newspaper me-2"></i> Write News
                        </a>
                        <a href="{{ route('admin.tv_team_verification') }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-user-check me-2"></i> Verify Teams
                            @if($data['pending_verification'] > 0)
                                <span class="badge bg-danger ms-2">{{ $data['pending_verification'] }}</span>
                            @endif
                        </a>
                        <a href="{{ route('admin.sponsor.sponsor') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-handshake me-2"></i> Manage Sponsors
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Matches Preview -->
    @if(isset($data['recent_matches']) && count($data['recent_matches']) > 0)
    <div class="row mt-3">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header py-3">
                    <h6 class="mb-0" style="font-size: 1rem; font-weight: 600; color: #4e73df;">
                        <i class="fas fa-calendar-alt me-2"></i> Today & Upcoming Matches
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="row">
                        @foreach($data['recent_matches'] as $match)
                        <div class="col-md-4 mb-3">
                            <div class="border rounded p-3">
                                <div class="d-flex justify-content-between">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i> {{ \Carbon\Carbon::parse($match->upload_date)->format('d M Y') }}
                                    </small>
                                    <small class="text-primary">
                                        <i class="fas fa-tag me-1"></i> {{ $match->series_name ?? 'Series' }}
                                    </small>
                                </div>
                                <div class="text-center my-3">
                                    <h6 class="mb-0">{{ $match->main_title ?? 'Match Schedule' }}</h6>
                                    <small class="text-muted">{{ $match->caption ?? '' }}</small>
                                </div>
                                <div class="text-center mt-2">
                                    <span class="badge bg-{{ $match->status == 'publish' ? 'success' : 'secondary' }}">
                                        {{ $match->status }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer bg-white py-2">
                    <a href="{{ route('admin.pub_schedule.index') }}" class="text-decoration-none small">
                        View all schedules <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- System Status -->
    <div class="row mt-3">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header py-3">
                    <h6 class="mb-0" style="font-size: 1rem; font-weight: 600; color: #4e73df;">
                        <i class="fas fa-server me-2"></i> System Status
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-success me-2" style="width: 10px; height: 10px; border-radius: 50%; padding: 0;"></span>
                                <span style="font-size: 0.85rem;">Database Online</span>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-success me-2" style="width: 10px; height: 10px; border-radius: 50%; padding: 0;"></span>
                                <span style="font-size: 0.85rem;">Storage Active</span>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-success me-2" style="width: 10px; height: 10px; border-radius: 50%; padding: 0;"></span>
                                <span style="font-size: 0.85rem;">Cache Ready</span>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-success me-2" style="width: 10px; height: 10px; border-radius: 50%; padding: 0;"></span>
                                <span style="font-size: 0.85rem;">Session Active</span>
                            </div>
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
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
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
        padding: 12px 12px;
        vertical-align: middle;
    }
    
    .badge {
        font-weight: 500;
        padding: 4px 8px;
    }
    
    .progress {
        background-color: #eaecf4;
        border-radius: 4px;
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
    const clockElement = document.getElementById('liveClock');
    if (clockElement) clockElement.innerText = timeString;
}
setInterval(updateClock, 1000);
updateClock();

// Simple refresh every 2 minutes
setTimeout(() => {
    location.reload();
}, 120000);
</script>
@endpush