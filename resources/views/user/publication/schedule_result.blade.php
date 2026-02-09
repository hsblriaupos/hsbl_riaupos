@extends('user.layouts.app')

@section('title', 'Schedule - HSBL Riau Pos')

@section('styles')
<style>
    /* Tab Styles */
    .schedule-tabs {
        border-bottom: 2px solid #e5e7eb;
        margin-bottom: 2rem;
    }
    
    .tab-btn {
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        color: #6b7280;
        border-bottom: 3px solid transparent;
        transition: all 0.3s ease;
        font-size: 0.9375rem;
        background: none;
        border: none;
        cursor: pointer;
    }
    
    .tab-btn:hover {
        color: #3b82f6;
    }
    
    .tab-btn.active {
        color: #3b82f6;
        border-bottom-color: #3b82f6;
        background-color: #eff6ff;
    }
    
    /* Enhanced Match Card Styles */
    .match-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border-radius: 1.25rem;
        padding: 1.75rem;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.1);
        border: 1px solid #f1f5f9;
        height: 100%;
        position: relative;
        overflow: hidden;
    }
    
    .match-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #3b82f6 0%, #60a5fa 100%);
    }
    
    .match-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px -10px rgba(59, 130, 246, 0.15);
    }
    
    .match-card.live {
        background: linear-gradient(135deg, #fff5f5 0%, #ffffff 100%);
        border-left: 4px solid #ef4444;
        animation: pulse 2s infinite;
    }
    
    .match-card.live::before {
        background: linear-gradient(90deg, #ef4444 0%, #f87171 100%);
    }
    
    .match-card.upcoming {
        background: linear-gradient(135deg, #eff6ff 0%, #ffffff 100%);
    }
    
    .match-card.upcoming::before {
        background: linear-gradient(90deg, #3b82f6 0%, #60a5fa 100%);
    }
    
    @keyframes pulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.2); }
        50% { box-shadow: 0 0 0 10px rgba(239, 68, 68, 0); }
    }
    
    /* Enhanced Team Badge */
    .team-badge {
        width: 80px;
        height: 80px;
        border-radius: 16px;
        background: linear-gradient(135deg, #ffffff, #f3f4f6);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        color: #1f2937;
        font-size: 1.5rem;
        margin: 0 auto;
        border: 3px solid white;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }
    
    .team-badge:hover {
        transform: scale(1.05);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
    }
    
    .team-badge img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 13px;
    }
    
    /* Series Badge Enhanced */
    .series-badge {
        display: inline-block;
        padding: 0.375rem 1rem;
        border-radius: 1rem;
        font-size: 0.75rem;
        font-weight: 600;
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    /* Date Header */
    .date-header {
        background: linear-gradient(90deg, #3b82f6 0%, #60a5fa 100%);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 0.75rem;
        margin-bottom: 1.5rem;
        display: inline-flex;
        align-items: center;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
    }
    
    .date-header i {
        font-size: 1.25rem;
        margin-right: 0.75rem;
    }
    
    /* Match Count Badge */
    .match-count-badge {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 500;
        margin-left: 1rem;
    }
    
    /* Status Badge Enhanced */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.375rem 1rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .status-upcoming {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        color: #1d4ed8;
    }
    
    .status-live {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #dc2626;
        animation: pulse 1.5s infinite;
    }
    
    /* Venue Badge - Since no venue column, using competition_type */
    .venue-badge {
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        color: #0369a1;
        padding: 0.5rem 1rem;
        border-radius: 0.75rem;
        font-size: 0.75rem;
        font-weight: 500;
        border: 1px solid #bae6fd;
        margin-top: 0.5rem;
        display: inline-block;
    }
    
    /* Empty State Enhanced */
    .empty-state {
        text-align: center;
        padding: 5rem 2rem;
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border-radius: 1.5rem;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
        border: 2px dashed #e5e7eb;
    }
    
    .empty-state i {
        font-size: 4rem;
        background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 1.5rem;
    }
    
    /* Loading Animation */
    .loading-skeleton {
        animation: shimmer 1.5s infinite linear;
        background: linear-gradient(90deg, #f3f4f6 0%, #e5e7eb 50%, #f3f4f6 100%);
        background-size: 200% 100%;
    }
    
    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }
    
    /* Action Buttons */
    .action-buttons {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1rem;
        margin-top: 1rem;
        border-top: 1px solid #f1f5f9;
    }
    
    .btn-details {
        background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
        color: white;
        padding: 0.5rem 1.25rem;
        border-radius: 0.75rem;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    .btn-details:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3);
    }
    
    .btn-reminder {
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        color: #0369a1;
        padding: 0.5rem 1.25rem;
        border-radius: 0.75rem;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s ease;
        border: 1px solid #bae6fd;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    .btn-reminder:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(2, 132, 199, 0.15);
        background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
    }
</style>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Page Header --}}
    <div class="mb-8 text-center">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">Match Schedule</h1>
        <p class="text-gray-600 max-w-2xl mx-auto">Stay updated with upcoming matches, live games, and never miss an exciting moment of Honda Student Basketball League</p>
    </div>

    {{-- Include Tabs Partial --}}
    @include('partials.tabs-schresult')

    {{-- Filters & Search --}}
    <div class="mb-8 bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex flex-wrap gap-3">
                <select id="seriesFilter" class="px-5 py-3 border border-gray-300 rounded-xl focus:ring-3 focus:ring-blue-500/30 focus:border-blue-500 outline-none text-sm transition-all duration-200 hover:border-blue-400">
                    <option value="">All Series</option>
                    @if(isset($seriesList) && count($seriesList) > 0)
                        @foreach($seriesList as $series)
                            <option value="{{ $series }}" {{ request('series') == $series ? 'selected' : '' }}>
                                {{ $series }}
                            </option>
                        @endforeach
                    @endif
                </select>
                
                <select id="seasonFilter" class="px-5 py-3 border border-gray-300 rounded-xl focus:ring-3 focus:ring-blue-500/30 focus:border-blue-500 outline-none text-sm transition-all duration-200 hover:border-blue-400">
                    <option value="">All Seasons</option>
                    @if(isset($seasons) && count($seasons) > 0)
                        @foreach($seasons as $season)
                            <option value="{{ $season }}" {{ request('season') == $season ? 'selected' : '' }}>
                                {{ $season }}
                            </option>
                        @endforeach
                    @endif
                </select>
                
                @if(isset($phases) && count($phases) > 0)
                <select id="phaseFilter" class="px-5 py-3 border border-gray-300 rounded-xl focus:ring-3 focus:ring-blue-500/30 focus:border-blue-500 outline-none text-sm transition-all duration-200 hover:border-blue-400">
                    <option value="">All Phases</option>
                    @foreach($phases as $phase)
                        <option value="{{ $phase }}" {{ request('phase') == $phase ? 'selected' : '' }}>
                            {{ $phase }}
                        </option>
                    @endforeach
                </select>
                @endif
            </div>
            
            <div class="relative w-full md:w-80">
                <input type="text" 
                       id="searchInput" 
                       placeholder="Search matches, teams..." 
                       value="{{ request('search') }}"
                       class="w-full px-5 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-3 focus:ring-blue-500/30 focus:border-blue-500 outline-none text-sm transition-all duration-200 hover:border-blue-400"
                >
                <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
        </div>
    </div>

    {{-- Live Matches Section --}}
    @if(isset($liveMatches) && $liveMatches->count() > 0)
        <div class="mb-10">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-bold text-gray-900">
                    <i class="fas fa-broadcast-tower text-red-500 mr-3"></i>
                    Live Now
                </h2>
                <span class="flex items-center space-x-2 px-4 py-2 bg-red-50 text-red-700 rounded-full">
                    <span class="h-2 w-2 bg-red-500 rounded-full animate-pulse"></span>
                    <span class="text-sm font-semibold">LIVE</span>
                </span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($liveMatches as $match)
                    <div class="match-card live">
                        <div class="absolute top-4 right-4">
                            <span class="status-badge status-live">
                                <i class="fas fa-broadcast-tower mr-2"></i>
                                LIVE NOW
                            </span>
                        </div>
                        
                        <div class="text-center mb-6">
                            <h3 class="font-bold text-gray-900 text-lg mb-1">{{ $match->competition ?? 'HSBL Match' }}</h3>
                            <div class="flex items-center justify-center space-x-4 text-sm text-gray-600">
                                <span><i class="far fa-clock mr-1"></i> {{ \Carbon\Carbon::parse($match->match_date)->format('h:i A') }}</span>
                                <span class="series-badge">{{ $match->series ?? 'Regular Series' }}</span>
                            </div>
                            @if($match->phase)
                                <div class="mt-2">
                                    <span class="px-3 py-1 bg-purple-50 text-purple-700 rounded-full text-xs font-medium">{{ $match->phase }}</span>
                                </div>
                            @endif
                        </div>
                        
                        <div class="flex items-center justify-between mb-4">
                            <div class="text-center flex-1">
                                <div class="team-badge mb-3">
                                    @if($match->team1 && $match->team1->logo)
                                        <img src="{{ asset('uploads/teams/' . $match->team1->logo) }}" 
                                             alt="{{ $match->team1->name }}" 
                                             class="w-full h-full rounded-lg object-cover">
                                    @else
                                        <div class="text-2xl font-bold text-red-600">
                                            {{ substr($match->team1->name ?? 'T1', 0, 2) }}
                                        </div>
                                    @endif
                                </div>
                                <p class="font-semibold text-gray-900">{{ $match->team1->name ?? 'Team A' }}</p>
                            </div>
                            
                            <div class="text-center mx-4">
                                <div class="text-4xl font-bold text-red-600 mb-2">
                                    {{ $match->score_1 ?? '0' }} - {{ $match->score_2 ?? '0' }}
                                </div>
                                <div class="venue-badge">
                                    <i class="fas fa-basketball-ball mr-1"></i> 
                                    {{ $match->competition_type ?? 'Basketball' }}
                                </div>
                            </div>
                            
                            <div class="text-center flex-1">
                                <div class="team-badge mb-3">
                                    @if($match->team2 && $match->team2->logo)
                                        <img src="{{ asset('uploads/teams/' . $match->team2->logo) }}" 
                                             alt="{{ $match->team2->name }}" 
                                             class="w-full h-full rounded-lg object-cover">
                                    @else
                                        <div class="text-2xl font-bold text-red-600">
                                            {{ substr($match->team2->name ?? 'T2', 0, 2) }}
                                        </div>
                                    @endif
                                </div>
                                <p class="font-semibold text-gray-900">{{ $match->team2->name ?? 'Team B' }}</p>
                            </div>
                        </div>
                        
                        <div class="action-buttons">
                            <a href="{{ route('user.schedule.show', $match->id) }}" 
                               class="btn-details">
                                <i class="fas fa-info-circle mr-2"></i> Match Details
                            </a>
                            <a href="#" class="btn-reminder">
                                <i class="fas fa-play-circle mr-2"></i> Watch Live
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Loading State --}}
    <div id="loadingState" class="hidden">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @for($i = 0; $i < 6; $i++)
                <div class="match-card">
                    <div class="loading-skeleton h-6 w-40 rounded-lg mb-4"></div>
                    <div class="loading-skeleton h-4 w-32 rounded mb-6"></div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="loading-skeleton h-20 w-20 rounded-2xl"></div>
                        <div class="loading-skeleton h-10 w-24 rounded-lg"></div>
                        <div class="loading-skeleton h-20 w-20 rounded-2xl"></div>
                    </div>
                    <div class="loading-skeleton h-10 w-full rounded-lg"></div>
                </div>
            @endfor
        </div>
    </div>

    {{-- Schedule Content --}}
    <div id="scheduleContent" class="tab-content {{ ($activeTab ?? 'schedule') == 'schedule' ? '' : 'hidden' }}">
        @if(isset($groupedMatches) && count($groupedMatches) > 0)
            @foreach($groupedMatches as $date => $matches)
                <div class="mb-12">
                    <div class="flex items-center mb-6">
                        <div class="date-header">
                            <i class="far fa-calendar-alt"></i>
                            <div>
                                <h3 class="text-lg font-bold">{{ \Carbon\Carbon::parse($date)->format('l, F j, Y') }}</h3>
                                <div class="text-sm opacity-90">{{ \Carbon\Carbon::parse($date)->diffForHumans() }}</div>
                            </div>
                            <span class="match-count-badge">{{ $matches->count() }} {{ $matches->count() == 1 ? 'Match' : 'Matches' }}</span>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($matches as $match)
                            <div class="match-card upcoming" 
                                 data-series="{{ $match->series ?? '' }}" 
                                 data-season="{{ $match->season ?? '' }}"
                                 data-phase="{{ $match->phase ?? '' }}">
                                
                                <div class="flex items-center justify-between mb-4">
                                    <span class="series-badge">{{ $match->series ?? 'Regular Series' }}</span>
                                    <span class="status-badge status-upcoming">
                                        <i class="far fa-clock mr-2"></i>
                                        UPCOMING
                                    </span>
                                </div>
                                
                                <div class="text-center mb-6">
                                    <h3 class="font-bold text-gray-900 text-lg mb-2">{{ $match->competition ?? 'HSBL Match' }}</h3>
                                    <div class="flex items-center justify-center space-x-3 text-sm text-gray-600">
                                        <span><i class="far fa-clock mr-1"></i> {{ \Carbon\Carbon::parse($match->match_date)->format('h:i A') }}</span>
                                        @if($match->phase)
                                            <span class="px-2 py-1 bg-purple-50 text-purple-700 rounded-full text-xs font-medium">{{ $match->phase }}</span>
                                        @endif
                                    </div>
                                    <div class="mt-2 text-xs text-gray-500">
                                        Season: {{ $match->season ?? 'N/A' }}
                                    </div>
                                </div>
                                
                                <div class="flex items-center justify-between mb-6">
                                    <div class="text-center flex-1">
                                        <div class="team-badge mb-4">
                                            @if($match->team1 && $match->team1->logo)
                                                <img src="{{ asset('uploads/teams/' . $match->team1->logo) }}" 
                                                     alt="{{ $match->team1->name }}" 
                                                     class="w-full h-full rounded-lg object-cover">
                                            @else
                                                <div class="text-2xl font-bold text-blue-600">
                                                    {{ substr($match->team1->name ?? 'T1', 0, 2) }}
                                                </div>
                                            @endif
                                        </div>
                                        <p class="font-semibold text-gray-900">{{ $match->team1->name ?? 'Team A' }}</p>
                                    </div>
                                    
                                    <div class="text-center mx-4">
                                        <div class="text-2xl font-bold text-blue-600 mb-2">VS</div>
                                        <div class="venue-badge">
                                            <i class="fas fa-basketball-ball mr-1"></i> 
                                            {{ $match->competition_type ?? 'Basketball' }}
                                        </div>
                                    </div>
                                    
                                    <div class="text-center flex-1">
                                        <div class="team-badge mb-4">
                                            @if($match->team2 && $match->team2->logo)
                                                <img src="{{ asset('uploads/teams/' . $match->team2->logo) }}" 
                                                     alt="{{ $match->team2->name }}" 
                                                     class="w-full h-full rounded-lg object-cover">
                                            @else
                                                <div class="text-2xl font-bold text-blue-600">
                                                    {{ substr($match->team2->name ?? 'T2', 0, 2) }}
                                                </div>
                                            @endif
                                        </div>
                                        <p class="font-semibold text-gray-900">{{ $match->team2->name ?? 'Team B' }}</p>
                                    </div>
                                </div>
                                
                                <div class="action-buttons">
                                    <a href="{{ route('user.schedule.show', $match->id) }}" 
                                       class="btn-details">
                                        <i class="fas fa-info-circle mr-2"></i> Match Details
                                    </a>
                                    <button onclick="setReminder({{ $match->id }})" 
                                            class="btn-reminder">
                                        <i class="far fa-bell mr-2"></i> Set Reminder
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @elseif(isset($upcomingMatches) && $upcomingMatches->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($upcomingMatches as $match)
                    <div class="match-card upcoming" 
                         data-series="{{ $match->series ?? '' }}" 
                         data-season="{{ $match->season ?? '' }}"
                         data-phase="{{ $match->phase ?? '' }}">
                        
                        <div class="flex items-center justify-between mb-4">
                            <span class="series-badge">{{ $match->series ?? 'Regular Series' }}</span>
                            <span class="status-badge status-upcoming">
                                <i class="far fa-clock mr-2"></i>
                                UPCOMING
                            </span>
                        </div>
                        
                        <div class="text-center mb-6">
                            <h3 class="font-bold text-gray-900 text-lg mb-2">{{ $match->competition ?? 'HSBL Match' }}</h3>
                            <div class="flex items-center justify-center space-x-3 text-sm text-gray-600">
                                <span><i class="far fa-calendar mr-1"></i> {{ \Carbon\Carbon::parse($match->match_date)->format('d M Y') }}</span>
                                <span><i class="far fa-clock mr-1"></i> {{ \Carbon\Carbon::parse($match->match_date)->format('h:i A') }}</span>
                            </div>
                            @if($match->phase)
                                <div class="mt-2">
                                    <span class="px-3 py-1 bg-purple-50 text-purple-700 rounded-full text-xs font-medium">{{ $match->phase }}</span>
                                </div>
                            @endif
                            <div class="mt-1 text-xs text-gray-500">
                                Season: {{ $match->season ?? 'N/A' }}
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between mb-6">
                            <div class="text-center flex-1">
                                <div class="team-badge mb-4">
                                    @if($match->team1 && $match->team1->logo)
                                        <img src="{{ asset('uploads/teams/' . $match->team1->logo) }}" 
                                             alt="{{ $match->team1->name }}" 
                                             class="w-full h-full rounded-lg object-cover">
                                    @else
                                        <div class="text-2xl font-bold text-blue-600">
                                            {{ substr($match->team1->name ?? 'T1', 0, 2) }}
                                        </div>
                                    @endif
                                </div>
                                <p class="font-semibold text-gray-900">{{ $match->team1->name ?? 'Team A' }}</p>
                            </div>
                            
                            <div class="text-center mx-4">
                                <div class="text-2xl font-bold text-blue-600 mb-2">VS</div>
                                <div class="venue-badge">
                                    <i class="fas fa-basketball-ball mr-1"></i> 
                                    {{ $match->competition_type ?? 'Basketball' }}
                                </div>
                            </div>
                            
                            <div class="text-center flex-1">
                                <div class="team-badge mb-4">
                                    @if($match->team2 && $match->team2->logo)
                                        <img src="{{ asset('uploads/teams/' . $match->team2->logo) }}" 
                                             alt="{{ $match->team2->name }}" 
                                             class="w-full h-full rounded-lg object-cover">
                                    @else
                                        <div class="text-2xl font-bold text-blue-600">
                                            {{ substr($match->team2->name ?? 'T2', 0, 2) }}
                                        </div>
                                    @endif
                                </div>
                                <p class="font-semibold text-gray-900">{{ $match->team2->name ?? 'Team B' }}</p>
                            </div>
                        </div>
                        
                        <div class="action-buttons">
                            <a href="{{ route('user.schedule.show', $match->id) }}" 
                               class="btn-details">
                                <i class="fas fa-info-circle mr-2"></i> Match Details
                            </a>
                            <button onclick="setReminder({{ $match->id }})" 
                                    class="btn-reminder">
                                <i class="far fa-bell mr-2"></i> Set Reminder
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
            
            {{-- Pagination for Schedule --}}
            @if($upcomingMatches instanceof \Illuminate\Pagination\LengthAwarePaginator && $upcomingMatches->hasPages())
                <div class="pagination-container mt-10">
                    {{ $upcomingMatches->appends(request()->except('page'))->links('vendor.pagination.tailwind') }}
                </div>
            @endif
        @else
            <div class="col-span-full">
                <div class="empty-state">
                    <i class="fas fa-calendar-plus"></i>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">No Upcoming Matches Scheduled</h3>
                    <p class="text-gray-600 mb-6 max-w-md mx-auto">The schedule for upcoming matches is being prepared. Check back soon for exciting basketball action!</p>
                    <button class="btn-details px-6" onclick="loadMatches('schedule')">
                        <i class="fas fa-sync-alt mr-2"></i> Refresh Schedule
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Set active tab from URL parameter or controller
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const activeTab = urlParams.get('tab') || '{{ $activeTab ?? "schedule" }}';
        switchTab(activeTab);
        
        // Initialize filters with current values
        document.getElementById('seriesFilter').addEventListener('change', filterMatches);
        document.getElementById('seasonFilter').addEventListener('change', filterMatches);
        document.getElementById('searchInput').addEventListener('input', debounce(filterMatches, 300));
        
        if (document.getElementById('phaseFilter')) {
            document.getElementById('phaseFilter').addEventListener('change', filterMatches);
        }
    });

    // Tab Switching Function
    function switchTab(tab) {
        // Update active tab button
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        
        if(tab === 'schedule') {
            document.getElementById('scheduleTab').classList.add('active');
            document.getElementById('scheduleContent').classList.remove('hidden');
            
            // Update URL without reload if not initial load
            if (!window.history.state || window.history.state.tab !== tab) {
                history.pushState({tab: tab}, '', `?tab=${tab}`);
            }
        } else {
            // Redirect to results page
            window.location.href = '{{ route("user.results") }}?tab=results';
        }
        
        // Update filters based on active tab
        updateFiltersForTab(tab);
    }
    
    // Debounce function for search input
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    // Filter Matches
    function filterMatches() {
        const seriesFilter = document.getElementById('seriesFilter').value.toLowerCase();
        const seasonFilter = document.getElementById('seasonFilter').value.toLowerCase();
        const searchQuery = document.getElementById('searchInput').value.toLowerCase();
        const phaseFilter = document.getElementById('phaseFilter') ? 
            document.getElementById('phaseFilter').value.toLowerCase() : '';
        
        const matchCards = document.querySelectorAll('#scheduleContent .match-card');
        let visibleCount = 0;
        
        matchCards.forEach(card => {
            const series = card.getAttribute('data-series')?.toLowerCase() || '';
            const season = card.getAttribute('data-season')?.toLowerCase() || '';
            const phase = card.getAttribute('data-phase')?.toLowerCase() || '';
            const textContent = card.textContent.toLowerCase();
            
            const matchesSeries = !seriesFilter || series.includes(seriesFilter);
            const matchesSeason = !seasonFilter || season.includes(seasonFilter);
            const matchesPhase = !phaseFilter || phase.includes(phaseFilter);
            const matchesSearch = !searchQuery || textContent.includes(searchQuery);
            
            if(matchesSeries && matchesSeason && matchesPhase && matchesSearch) {
                card.classList.remove('hidden');
                visibleCount++;
            } else {
                card.classList.add('hidden');
            }
        });
        
        // Show empty state if no matches visible
        const emptyState = document.querySelector('#scheduleContent .empty-state');
        if (emptyState && visibleCount === 0 && matchCards.length > 0) {
            emptyState.classList.remove('hidden');
        } else if (emptyState) {
            emptyState.classList.add('hidden');
        }
    }
    
    // Load Matches via AJAX
    function loadMatches(type) {
        const loadingState = document.getElementById('loadingState');
        const contentDiv = document.getElementById('scheduleContent');
        
        // Show loading
        loadingState.classList.remove('hidden');
        contentDiv.classList.add('hidden');
        
        // Get current filter values
        const series = document.getElementById('seriesFilter').value;
        const season = document.getElementById('seasonFilter').value;
        const search = document.getElementById('searchInput').value;
        const phase = document.getElementById('phaseFilter') ? 
            document.getElementById('phaseFilter').value : '';
        
        // Make AJAX request
        fetch('{{ route("user.schedule.ajax.get") }}' + `?series=${series}&season=${season}&search=${search}&phase=${phase}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    contentDiv.innerHTML = data.html;
                    loadingState.classList.add('hidden');
                    contentDiv.classList.remove('hidden');
                    
                    // Re-attach event listeners to new elements
                    reattachEventListeners();
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated!',
                        text: `Schedule has been refreshed`,
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                loadingState.classList.add('hidden');
                contentDiv.classList.remove('hidden');
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load schedule. Please try again.',
                });
            });
    }
    
    // Set match reminder
    function setReminder(matchId) {
        Swal.fire({
            title: 'Set Match Reminder',
            html: `
                <div class="text-left space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <input type="email" id="reminderEmail" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                               placeholder="your@email.com" value="{{ auth()->user()->email ?? '' }}">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Remind me before match</label>
                        <select id="reminderTime" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="15">15 minutes before</option>
                            <option value="30" selected>30 minutes before</option>
                            <option value="60">1 hour before</option>
                            <option value="120">2 hours before</option>
                            <option value="1440">1 day before</option>
                        </select>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Set Reminder',
            confirmButtonColor: '#3b82f6',
            cancelButtonText: 'Cancel',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                const email = document.getElementById('reminderEmail').value;
                const time = document.getElementById('reminderTime').value;
                
                if (!email) {
                    Swal.showValidationMessage('Please enter your email address');
                    return false;
                }
                
                return fetch(`/user/schedule/${matchId}/reminder`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        email: email,
                        reminder_time: time
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(response.statusText);
                    }
                    return response.json();
                })
                .catch(error => {
                    Swal.showValidationMessage(`Request failed: ${error}`);
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    icon: 'success',
                    title: 'Reminder Set Successfully!',
                    text: 'You will receive an email reminder before the match starts.',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        });
    }
    
    // Re-attach event listeners after AJAX load
    function reattachEventListeners() {
        document.querySelectorAll('.btn-reminder').forEach(button => {
            const onclickAttr = button.getAttribute('onclick');
            if (onclickAttr) {
                const matchId = onclickAttr.match(/setReminder\((\d+)\)/)[1];
                button.onclick = () => setReminder(matchId);
            }
        });
        
        // Re-attach filter listeners to new match cards
        filterMatches();
    }
</script>
@endsection