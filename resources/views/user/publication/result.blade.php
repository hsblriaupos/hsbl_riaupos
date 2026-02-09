@extends('user.layouts.app')

@section('title', 'Match Results - HSBL Riau Pos')

@section('styles')
<style>
    /* Result Card Specific Styles */
    .result-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border-radius: 1.25rem;
        padding: 1.75rem;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        border: 1px solid #f1f5f9;
        height: 100%;
        position: relative;
        overflow: hidden;
    }
    
    .result-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #10b981 0%, #34d399 100%);
    }
    
    .result-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px -10px rgba(16, 185, 129, 0.15);
    }
    
    /* Winner Badge */
    .winner-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        z-index: 2;
    }
    
    /* Score Display Enhanced */
    .score-display {
        font-size: 3rem;
        font-weight: 800;
        color: #1f2937;
        line-height: 1;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    .winner-score {
        color: #10b981;
        position: relative;
    }
    
    .winner-score::after {
        content: '🏆';
        position: absolute;
        top: -10px;
        right: -15px;
        font-size: 1rem;
    }
    
    .loser-score {
        color: #6b7280;
    }
    
    .score-divider {
        font-size: 2rem;
        color: #9ca3af;
        margin: 0 0.5rem;
        font-weight: 300;
    }
    
    /* Stats Buttons */
    .stats-buttons {
        display: flex;
        gap: 0.5rem;
        margin-top: 1rem;
    }
    
    .btn-stats {
        flex: 1;
        background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
        color: white;
        padding: 0.5rem;
        border-radius: 0.75rem;
        font-size: 0.75rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    
    .btn-stats:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3);
    }
    
    .btn-scoresheet {
        background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
    }
    
    .btn-scoresheet:hover {
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
    }
</style>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Page Header --}}
    <div class="mb-8 text-center">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">Match Results</h1>
        <p class="text-gray-600 max-w-2xl mx-auto">View detailed results and statistics from completed Honda Student Basketball League matches</p>
    </div>

    {{-- Include Tabs Partial --}}
    @include('partials.tabs-schresult')

    {{-- Filters & Search --}}
    <div class="mb-8 bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex flex-wrap gap-3">
                <select id="seriesFilter" class="px-5 py-3 border border-gray-300 rounded-xl focus:ring-3 focus:ring-green-500/30 focus:border-green-500 outline-none text-sm transition-all duration-200 hover:border-green-400">
                    <option value="">All Series</option>
                    @if(isset($seriesList) && count($seriesList) > 0)
                        @foreach($seriesList as $series)
                            <option value="{{ $series }}" {{ request('series') == $series ? 'selected' : '' }}>
                                {{ $series }}
                            </option>
                        @endforeach
                    @endif
                </select>
                
                <select id="seasonFilter" class="px-5 py-3 border border-gray-300 rounded-xl focus:ring-3 focus:ring-green-500/30 focus:border-green-500 outline-none text-sm transition-all duration-200 hover:border-green-400">
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
                <select id="phaseFilter" class="px-5 py-3 border border-gray-300 rounded-xl focus:ring-3 focus:ring-green-500/30 focus:border-green-500 outline-none text-sm transition-all duration-200 hover:border-green-400">
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
                       placeholder="Search results, teams, scores..." 
                       value="{{ request('search') }}"
                       class="w-full px-5 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-3 focus:ring-green-500/30 focus:border-green-500 outline-none text-sm transition-all duration-200 hover:border-green-400"
                >
                <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
        </div>
    </div>

    {{-- Loading State --}}
    <div id="loadingState" class="hidden">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @for($i = 0; $i < 6; $i++)
                <div class="result-card">
                    <div class="loading-skeleton h-6 w-40 rounded-lg mb-4"></div>
                    <div class="loading-skeleton h-4 w-32 rounded mb-6"></div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="loading-skeleton h-20 w-20 rounded-2xl"></div>
                        <div class="loading-skeleton h-12 w-32 rounded-lg"></div>
                        <div class="loading-skeleton h-20 w-20 rounded-2xl"></div>
                    </div>
                    <div class="loading-skeleton h-20 w-full rounded-lg"></div>
                </div>
            @endfor
        </div>
    </div>

    {{-- Results Content --}}
    <div id="resultsContent" class="tab-content">
        @if($completedMatches->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($completedMatches as $match)
                    @php
                        $winner = null;
                        $loser = null;
                        if($match->score_1 > $match->score_2) {
                            $winner = $match->team1;
                            $loser = $match->team2;
                            $winnerScore = $match->score_1;
                            $loserScore = $match->score_2;
                        } elseif($match->score_2 > $match->score_1) {
                            $winner = $match->team2;
                            $loser = $match->team1;
                            $winnerScore = $match->score_2;
                            $loserScore = $match->score_1;
                        }
                    @endphp
                    
                    <div class="result-card" 
                         data-series="{{ $match->series ?? '' }}" 
                         data-season="{{ $match->season ?? '' }}"
                         data-phase="{{ $match->phase ?? '' }}">
                        
                        @if($winner)
                            <span class="winner-badge">Winner</span>
                        @endif
                        
                        <div class="flex items-center justify-between mb-4">
                            <span class="series-badge" style="background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); color: #065f46; border-color: #a7f3d0;">
                                {{ $match->series ?? 'Regular Series' }}
                            </span>
                            <span class="status-badge" style="background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); color: #065f46;">
                                <i class="fas fa-check-circle mr-2"></i>
                                COMPLETED
                            </span>
                        </div>
                        
                        <div class="text-center mb-6">
                            <h3 class="font-bold text-gray-900 text-lg mb-2">{{ $match->competition ?? 'HSBL Match' }}</h3>
                            <div class="flex items-center justify-center space-x-3 text-sm text-gray-600">
                                <span><i class="far fa-calendar mr-1"></i> {{ \Carbon\Carbon::parse($match->match_date)->format('d M Y') }}</span>
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
                                <div class="team-badge mb-4 {{ $match->score_1 > $match->score_2 ? 'bg-green-100 border-green-200' : '' }}" 
                                     style="{{ $match->score_1 > $match->score_2 ? 'background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); border-color: #a7f3d0;' : '' }}">
                                    @if($match->team1 && $match->team1->logo)
                                        <img src="{{ asset('uploads/teams/' . $match->team1->logo) }}" 
                                             alt="{{ $match->team1->name }}" 
                                             class="w-full h-full rounded-lg object-cover">
                                    @else
                                        <div class="text-2xl font-bold {{ $match->score_1 > $match->score_2 ? 'text-green-600' : 'text-gray-600' }}">
                                            {{ substr($match->team1->name ?? 'T1', 0, 2) }}
                                        </div>
                                    @endif
                                </div>
                                <p class="font-semibold text-gray-900">{{ $match->team1->name ?? 'Team A' }}</p>
                                @if($match->score_1 > $match->score_2)
                                    <span class="text-xs text-green-600 font-medium">WINNER</span>
                                @endif
                            </div>
                            
                            <div class="text-center mx-4">
                                <div class="score-display">
                                    <span class="{{ $match->score_1 > $match->score_2 ? 'winner-score' : 'loser-score' }}">
                                        {{ $match->score_1 ?? '0' }}
                                    </span>
                                    <span class="score-divider">-</span>
                                    <span class="{{ $match->score_2 > $match->score_1 ? 'winner-score' : 'loser-score' }}">
                                        {{ $match->score_2 ?? '0' }}
                                    </span>
                                </div>
                                <div class="text-xs font-medium text-gray-500 mt-2">FINAL SCORE</div>
                            </div>
                            
                            <div class="text-center flex-1">
                                <div class="team-badge mb-4 {{ $match->score_2 > $match->score_1 ? 'bg-green-100 border-green-200' : '' }}" 
                                     style="{{ $match->score_2 > $match->score_1 ? 'background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); border-color: #a7f3d0;' : '' }}">
                                    @if($match->team2 && $match->team2->logo)
                                        <img src="{{ asset('uploads/teams/' . $match->team2->logo) }}" 
                                             alt="{{ $match->team2->name }}" 
                                             class="w-full h-full rounded-lg object-cover">
                                    @else
                                        <div class="text-2xl font-bold {{ $match->score_2 > $match->score_1 ? 'text-green-600' : 'text-gray-600' }}">
                                            {{ substr($match->team2->name ?? 'T2', 0, 2) }}
                                        </div>
                                    @endif
                                </div>
                                <p class="font-semibold text-gray-900">{{ $match->team2->name ?? 'Team B' }}</p>
                                @if($match->score_2 > $match->score_1)
                                    <span class="text-xs text-green-600 font-medium">WINNER</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="stats-buttons">
                            <a href="{{ route('user.results.show', $match->id) }}" 
                               class="btn-stats">
                                <i class="fas fa-info-circle"></i> Details
                            </a>
                            
                            @if($match->scoresheet)
                                <a href="{{ route('user.results.download.scoresheet', $match->id) }}" 
                                   class="btn-stats btn-scoresheet">
                                    <i class="fas fa-file-alt"></i> Scoresheet
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            
            {{-- Pagination for Results --}}
            @if($completedMatches instanceof \Illuminate\Pagination\LengthAwarePaginator && $completedMatches->hasPages())
                <div class="pagination-container mt-10">
                    {{ $completedMatches->appends(request()->except('page'))->links('vendor.pagination.tailwind') }}
                </div>
            @endif
        @else
            <div class="col-span-full">
                <div class="empty-state">
                    <i class="fas fa-trophy"></i>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">No Match Results Available</h3>
                    <p class="text-gray-600 mb-6 max-w-md mx-auto">Match results will be displayed here once games are completed and results are published.</p>
                    <button class="btn-details px-6" onclick="loadResults()">
                        <i class="fas fa-sync-alt mr-2"></i> Refresh Results
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set active tab to results
        document.getElementById('resultsTab').classList.add('active');
        
        // Initialize filters
        document.getElementById('seriesFilter').addEventListener('change', filterResults);
        document.getElementById('seasonFilter').addEventListener('change', filterResults);
        document.getElementById('searchInput').addEventListener('input', debounce(filterResults, 300));
        
        if (document.getElementById('phaseFilter')) {
            document.getElementById('phaseFilter').addEventListener('change', filterResults);
        }
    });
    
    function switchTab(tab) {
        if(tab === 'schedule') {
            window.location.href = '{{ route("user.schedule") }}?tab=schedule';
        } else {
            document.getElementById('resultsTab').classList.add('active');
            document.getElementById('scheduleTab').classList.remove('active');
        }
    }
    
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
    
    function filterResults() {
        const seriesFilter = document.getElementById('seriesFilter').value.toLowerCase();
        const seasonFilter = document.getElementById('seasonFilter').value.toLowerCase();
        const searchQuery = document.getElementById('searchInput').value.toLowerCase();
        const phaseFilter = document.getElementById('phaseFilter') ? 
            document.getElementById('phaseFilter').value.toLowerCase() : '';
        
        const resultCards = document.querySelectorAll('#resultsContent .result-card');
        let visibleCount = 0;
        
        resultCards.forEach(card => {
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
        
        const emptyState = document.querySelector('#resultsContent .empty-state');
        if (emptyState && visibleCount === 0 && resultCards.length > 0) {
            emptyState.classList.remove('hidden');
        } else if (emptyState) {
            emptyState.classList.add('hidden');
        }
    }
    
    function loadResults() {
        const loadingState = document.getElementById('loadingState');
        const contentDiv = document.getElementById('resultsContent');
        
        loadingState.classList.remove('hidden');
        contentDiv.classList.add('hidden');
        
        const series = document.getElementById('seriesFilter').value;
        const season = document.getElementById('seasonFilter').value;
        const search = document.getElementById('searchInput').value;
        const phase = document.getElementById('phaseFilter') ? 
            document.getElementById('phaseFilter').value : '';
        
        fetch('{{ route("user.results.ajax.get") }}' + `?series=${series}&season=${season}&search=${search}&phase=${phase}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    contentDiv.innerHTML = data.html;
                    loadingState.classList.add('hidden');
                    contentDiv.classList.remove('hidden');
                    reattachResultListeners();
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated!',
                        text: 'Results have been refreshed',
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
                    text: 'Failed to load results. Please try again.',
                });
            });
    }
    
    function reattachResultListeners() {
        filterResults();
    }
</script>
@endsection