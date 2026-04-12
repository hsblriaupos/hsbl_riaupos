@extends('user.layouts.app')

@section('title', 'Schedules & Results - SBL Riau Pos')

@section('content')

{{-- Header Section - SAMA KAYAK NEWS --}}
<div class="md:-mt-10 -mt-20 mb-8 text-center">
    <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-blue-50 rounded-full mb-4">
        <span class="relative flex h-2 w-2">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
        </span>
        <span class="text-xs font-semibold text-blue-600 tracking-wide">TRACK PROGRESS</span>
    </div>
    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">
        Schedules & <span class="text-blue-600">Results</span>
    </h1>
    <p class="text-gray-500 text-sm max-w-md mx-auto">Follow match schedules and view real-time results from Honda Student Basketball League</p>
</div>

{{-- Tabs Navigation - Minimalis --}}
<div class="flex justify-center mb-8">
    <div class="inline-flex bg-gray-100 rounded-full p-1">
        <button id="schedulesTab" 
                class="px-6 py-2 text-sm font-medium rounded-full transition-all duration-200 bg-blue-500 text-white shadow-sm"
                onclick="showSection('schedules')">
            <i class="fas fa-calendar-alt mr-2"></i> Schedules
        </button>
        <button id="resultsTab" 
                class="px-6 py-2 text-sm font-medium rounded-full transition-all duration-200 text-gray-600 hover:text-gray-800"
                onclick="showSection('results')">
            <i class="fas fa-trophy mr-2"></i> Results
        </button>
    </div>
</div>

{{-- Schedules Section --}}
<section id="schedulesSection" class="section-content">
    {{-- Filter - SAMA KAYAK NEWS --}}
    <form id="scheduleFilterForm" method="GET" action="{{ route('user.schedule_result') }}" class="mb-6">
        <div class="flex flex-wrap items-end gap-3">
            {{-- Series Filter --}}
            <div class="flex-1 min-w-[150px]">
                <div class="relative group">
                    <i class="fas fa-layer-group absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-500 text-sm transition-colors"></i>
                    <select name="series" id="scheduleSeriesFilter" 
                            class="w-full pl-9 pr-3 py-2.5 text-sm border-b-2 border-gray-200 focus:border-blue-500 focus:outline-none appearance-none bg-black-50/30 focus:bg-gray-50 cursor-pointer transition-all rounded-t-lg">
                        <option value="">All Series</option>
                        @if(isset($seriesList) && count($seriesList) > 0)
                            @foreach($seriesList as $series)
                                <option value="{{ $series }}" {{ request('series') == $series ? 'selected' : '' }}>{{ $series }}</option>
                            @endforeach
                        @endif
                    </select>
                    <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                </div>
            </div>

            {{-- Year Filter --}}
            <div class="w-36">
                <div class="relative group">
                    <i class="fas fa-calendar-alt absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-500 text-sm transition-colors"></i>
                    <select name="year" id="scheduleYearFilter" 
                            class="w-full pl-9 pr-3 py-2.5 text-sm border-b-2 border-gray-200 focus:border-blue-500 focus:outline-none appearance-none bg-black-50/30 focus:bg-gray-50 cursor-pointer transition-all rounded-t-lg">
                        <option value="">All Years</option>
                        @if(isset($years) && count($years) > 0)
                            @foreach($years as $year)
                                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        @else
                            @for($year = date('Y'); $year >= 2020; $year--)
                                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endfor
                        @endif
                    </select>
                    <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex gap-2">
                <button type="submit" 
                        class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                    <i class="fas fa-filter mr-1.5 text-xs"></i> Apply
                </button>
                <button type="button" 
                        onclick="resetScheduleFilters()" 
                        class="px-5 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-800 border border-gray-200 hover:border-gray-300 rounded-lg transition-all duration-200 bg-white">
                    <i class="fas fa-redo mr-1.5 text-xs"></i> Reset
                </button>
            </div>
        </div>
        <input type="hidden" name="tab" value="schedules">
    </form>

    {{-- Schedules Content --}}
    @if(isset($schedules) && count($schedules) > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach($schedules as $schedule)
                @php
                    $imageUrl = $schedule->image_url ?? 
                        (isset($schedule->layout_image) ? 
                            (str_starts_with($schedule->layout_image, 'http') ? 
                                $schedule->layout_image : 
                                asset('images/schedule/' . basename($schedule->layout_image))
                            ) : 
                            asset('images/default-schedule.jpg'));
                    $fileName = Str::slug($schedule->main_title ?? 'schedule') . '-' . date('Ymd', strtotime($schedule->match_date ?? now())) . '.jpg';
                @endphp
                <article class="group">
                    <div class="relative overflow-hidden rounded-xl mb-3 bg-gray-100 aspect-[4/3] cursor-pointer" onclick="openImageModal('{{ $imageUrl }}', '{{ $schedule->main_title ?? 'Match Schedule' }}')">
                        <img src="{{ $imageUrl }}" 
                             alt="{{ $schedule->main_title ?? 'Match Schedule' }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                             onerror="this.src='{{ asset('images/default-schedule.jpg') }}'">
                        
                        {{-- Series Badge --}}
                        @if($schedule->series_name)
                            <span class="absolute top-2 left-2 px-2 py-0.5 text-[10px] font-semibold rounded-md bg-white/90 backdrop-blur-sm text-blue-600 shadow-sm">
                                {{ $schedule->series_name }}
                            </span>
                        @endif
                        
                        {{-- Download Button Overlay --}}
                        <div class="absolute inset-0 bg-blue-600/70 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
                            <button onclick="event.stopPropagation(); downloadScheduleImage('{{ $imageUrl }}', '{{ $fileName }}')"
                                    class="px-4 py-1.5 text-xs font-semibold text-white bg-white/20 rounded-full backdrop-blur-sm hover:bg-white/30 transition-colors">
                                <i class="fas fa-download mr-1 text-xs"></i> Download
                            </button>
                        </div>
                    </div>
                    
                    <div>
                        <div class="flex items-center gap-2 text-xs text-gray-400 mb-1.5">
                            <i class="far fa-calendar-alt text-[11px]"></i>
                            <span>{{ $schedule->formatted_date ?? 'Date TBD' }}</span>
                        </div>
                        <h3 class="font-semibold text-gray-800 mb-1 line-clamp-2 group-hover:text-blue-600 transition-colors text-sm">
                            {{ $schedule->main_title ?? 'Match Schedule' }}
                        </h3>
                        <div class="mt-2 flex items-center text-blue-500 text-xs font-medium">
                            <i class="far fa-clock mr-1 text-[10px]"></i>
                            <span>{{ $schedule->match_time ?? 'Time TBD' }}</span>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
        
        {{-- Pagination --}}
        @if(isset($schedules) && method_exists($schedules, 'hasPages') && $schedules->hasPages())
            <div class="mt-10 flex justify-center">
                {{ $schedules->appends(request()->except('page'))->links('vendor.pagination.tailwind') }}
            </div>
        @endif
    @else
        {{-- Empty State - SAMA KAYAK NEWS --}}
        <div class="text-center py-12 px-6 bg-gradient-to-br from-blue-50 to-white rounded-2xl border-2 border-dashed border-blue-200">
            <div class="w-20 h-20 mx-auto mb-4 bg-white rounded-full flex items-center justify-center shadow-sm">
                <i class="fas fa-calendar-alt text-blue-300 text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-700 mb-2">No Schedules Available</h3>
            <p class="text-gray-500 text-sm mb-5">
                @if(request()->hasAny(['series', 'year']))
                    Try adjusting your filter to find what you're looking for.
                @else
                    Match schedules will be posted here soon.
                @endif
            </p>
            @if(request()->hasAny(['series', 'year']))
                <button onclick="resetScheduleFilters()" 
                        class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 rounded-xl transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-eye text-xs"></i> Clear Filters
                </button>
            @endif
        </div>
    @endif
</section>

{{-- Results Section --}}
<section id="resultsSection" class="section-content hidden">
    {{-- Filter - SAMA KAYAK NEWS --}}
    <form id="resultsFilterForm" method="GET" action="{{ route('user.schedule_result') }}" class="mb-6">
        <div class="flex flex-wrap items-end gap-3">
            {{-- Series Filter --}}
            <div class="flex-1 min-w-[150px]">
                <div class="relative group">
                    <i class="fas fa-layer-group absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-500 text-sm transition-colors"></i>
                    <select name="results_series" id="resultsSeriesFilter" 
                            class="w-full pl-9 pr-3 py-2.5 text-sm border-b-2 border-gray-200 focus:border-blue-500 focus:outline-none appearance-none bg-black-50/30 focus:bg-gray-50 cursor-pointer transition-all rounded-t-lg">
                        <option value="">All Series</option>
                        @if(isset($seriesListResults) && count($seriesListResults) > 0)
                            @foreach($seriesListResults as $series)
                                <option value="{{ $series }}" {{ request('results_series') == $series ? 'selected' : '' }}>{{ $series }}</option>
                            @endforeach
                        @endif
                    </select>
                    <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                </div>
            </div>

            {{-- Season Filter --}}
            <div class="w-36">
                <div class="relative group">
                    <i class="fas fa-calendar-alt absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-500 text-sm transition-colors"></i>
                    <select name="results_season" id="resultsSeasonFilter" 
                            class="w-full pl-9 pr-3 py-2.5 text-sm border-b-2 border-gray-200 focus:border-blue-500 focus:outline-none appearance-none bg-black-50/30 focus:bg-gray-50 cursor-pointer transition-all rounded-t-lg">
                        <option value="">All Seasons</option>
                        @if(isset($seasons) && count($seasons) > 0)
                            @foreach($seasons as $season)
                                <option value="{{ $season }}" {{ request('results_season') == $season ? 'selected' : '' }}>{{ $season }}</option>
                            @endforeach
                        @else
                            @for($year = date('Y'); $year >= 2020; $year--)
                                <option value="{{ $year }}" {{ request('results_season') == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endfor
                        @endif
                    </select>
                    <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex gap-2">
                <button type="button" 
                        onclick="resetResultsFilters()" 
                        class="px-5 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-800 border border-gray-200 hover:border-gray-300 rounded-lg transition-all duration-200 bg-white">
                    <i class="fas fa-redo mr-1.5 text-xs"></i> Reset
                </button>
            </div>
        </div>
        <input type="hidden" name="tab" value="results">
    </form>

    {{-- Results Content --}}
    @if(isset($results) && count($results) > 0)
        <div class="space-y-3">
            @foreach($results as $result)
                @php
                    $statusConfig = [
                        'completed' => ['class' => 'bg-green-100 text-green-700', 'icon' => 'fas fa-check-circle', 'text' => 'Completed'],
                        'upcoming' => ['class' => 'bg-yellow-100 text-yellow-700', 'icon' => 'fas fa-clock', 'text' => 'Upcoming'],
                        'live' => ['class' => 'bg-red-100 text-red-700', 'icon' => 'fas fa-play-circle', 'text' => 'Live'],
                        'publish' => ['class' => 'bg-purple-100 text-purple-700', 'icon' => 'fas fa-upload', 'text' => 'Published'],
                        'scheduled' => ['class' => 'bg-blue-100 text-blue-700', 'icon' => 'fas fa-calendar-check', 'text' => 'Scheduled']
                    ];
                    $status = $result->status ?? 'scheduled';
                    $statusInfo = $statusConfig[$status] ?? $statusConfig['scheduled'];
                    $matchDate = isset($result->match_date) && $result->match_date ? \Carbon\Carbon::parse($result->match_date)->format('d M Y') : null;
                @endphp
                
                <div class="bg-white rounded-xl border border-gray-100 hover:border-blue-200 transition-all duration-300 overflow-hidden">
                    <div class="p-4">
                        {{-- Header --}}
                        <div class="flex flex-wrap items-center justify-between gap-2 mb-3">
                            <div class="flex flex-wrap items-center gap-2">
                                @if(isset($result->series) && $result->series)
                                    <span class="px-2 py-0.5 text-xs font-semibold rounded-md bg-blue-50 text-blue-600">
                                        {{ $result->series }}
                                    </span>
                                @endif
                                @if(isset($result->season) && $result->season)
                                    <span class="px-2 py-0.5 text-xs font-semibold rounded-md bg-gray-100 text-gray-600">
                                        {{ $result->season }}
                                    </span>
                                @endif
                                @if(isset($result->phase) && $result->phase)
                                    <span class="px-2 py-0.5 text-xs font-semibold rounded-md bg-orange-50 text-orange-600">
                                        {{ $result->phase }}
                                    </span>
                                @endif
                                @if(isset($result->competition_type) && $result->competition_type)
                                    <span class="px-2 py-0.5 text-xs font-semibold rounded-md bg-purple-50 text-purple-600">
                                        {{ $result->competition_type }}
                                    </span>
                                @endif
                            </div>
                            <div class="flex items-center gap-3 text-xs text-gray-500">
                                @if($matchDate)
                                    <span><i class="far fa-calendar-alt mr-1 text-blue-400"></i>{{ $matchDate }}</span>
                                @endif
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full {{ $statusInfo['class'] }}">
                                    <i class="{{ $statusInfo['icon'] }} text-[10px]"></i>
                                    <span class="text-[10px] font-medium">{{ $statusInfo['text'] }}</span>
                                </span>
                            </div>
                        </div>
                        
                        {{-- Teams & Score --}}
                        <div class="flex items-center justify-between gap-4">
                            {{-- Team 1 --}}
                            <div class="flex-1 text-center">
                                <div class="w-12 h-12 mx-auto mb-2 flex items-center justify-center bg-gray-50 rounded-full border border-gray-200">
                                    <i class="fas fa-school text-blue-500 text-xl"></i>
                                </div>
                                <div class="font-semibold text-gray-800 text-sm truncate">{{ $result->team1_name ?? 'Team A' }}</div>
                            </div>
                            
                            {{-- Score --}}
                            <div class="text-center">
                                <div class="text-2xl font-bold text-gray-900">{{ $result->score_1 ?? '0' }} - {{ $result->score_2 ?? '0' }}</div>
                                <div class="text-xs text-gray-400 mt-1">Final</div>
                            </div>
                            
                            {{-- Team 2 --}}
                            <div class="flex-1 text-center">
                                <div class="w-12 h-12 mx-auto mb-2 flex items-center justify-center bg-gray-50 rounded-full border border-gray-200">
                                    <i class="fas fa-school text-blue-500 text-xl"></i>
                                </div>
                                <div class="font-semibold text-gray-800 text-sm truncate">{{ $result->team2_name ?? 'Team B' }}</div>
                            </div>
                        </div>
                        
                        {{-- Footer --}}
                        @if(isset($result->has_scoresheet) && $result->has_scoresheet)
                            <div class="mt-3 pt-2 border-t border-gray-100 text-center">
                                <a href="{{ route('user.results.download.scoresheet', ['id' => $result->id]) }}" 
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-green-600 hover:text-white bg-green-50 hover:bg-green-600 rounded-lg transition-all duration-200">
                                    <i class="fas fa-download text-xs"></i>
                                    Download Scoresheet
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        
        {{-- Pagination --}}
        @if(isset($results) && method_exists($results, 'hasPages') && $results->hasPages())
            <div class="mt-10 flex justify-center">
                {{ $results->appends(request()->except('page'))->links('vendor.pagination.tailwind') }}
            </div>
        @endif
    @else
        {{-- Empty State - SAMA KAYAK NEWS --}}
        <div class="text-center py-12 px-6 bg-gradient-to-br from-blue-50 to-white rounded-2xl border-2 border-dashed border-blue-200">
            <div class="w-20 h-20 mx-auto mb-4 bg-white rounded-full flex items-center justify-center shadow-sm">
                <i class="fas fa-trophy text-blue-300 text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-700 mb-2">No Results Available</h3>
            <p class="text-gray-500 text-sm mb-5">
                @if(request()->hasAny(['results_series', 'results_season']))
                    Try adjusting your filter to find what you're looking for.
                @else
                    Match results will be displayed here after the games.
                @endif
            </p>
            @if(request()->hasAny(['results_series', 'results_season']))
                <button onclick="resetResultsFilters()" 
                        class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 rounded-xl transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-eye text-xs"></i> Clear Filters
                </button>
            @endif
        </div>
    @endif
</section>

{{-- Image Modal --}}
<div id="imageModal" class="fixed inset-0 z-50 hidden bg-black/90 flex items-center justify-center p-4">
    <div class="relative max-w-4xl w-full max-h-[90vh]">
        <button onclick="closeImageModal()" 
                class="absolute -top-12 right-0 text-white hover:text-blue-300 text-2xl transition-colors">
            <i class="fas fa-times-circle"></i>
        </button>
        <div class="bg-white rounded-xl overflow-hidden shadow-2xl">
            <div class="max-h-[70vh] overflow-auto">
                <img id="modalImage" src="" alt="" class="w-full h-auto object-contain">
            </div>
            <div class="p-4 bg-white border-t border-gray-100">
                <h3 id="modalTitle" class="font-semibold text-gray-900 text-center"></h3>
            </div>
        </div>
    </div>
</div>

<script>
let activeTab = 'schedules';

function showSection(section) {
    activeTab = section;
    
    // Hide all sections
    document.querySelectorAll('.section-content').forEach(el => {
        el.classList.add('hidden');
    });
    
    // Show selected section
    const targetSection = document.getElementById(section + 'Section');
    if (targetSection) targetSection.classList.remove('hidden');
    
    // Update tab styles
    const tabs = ['schedules', 'results'];
    tabs.forEach(tab => {
        const tabBtn = document.getElementById(tab + 'Tab');
        if (tabBtn) {
            if (tab === section) {
                tabBtn.classList.remove('text-gray-600', 'hover:text-gray-800');
                tabBtn.classList.add('bg-blue-500', 'text-white');
            } else {
                tabBtn.classList.remove('bg-blue-500', 'text-white');
                tabBtn.classList.add('text-gray-600', 'hover:text-gray-800');
            }
        }
    });
    
    // Update URL
    const url = new URL(window.location);
    url.searchParams.set('tab', section);
    window.history.pushState({}, '', url);
}

function resetScheduleFilters() {
    document.getElementById('scheduleSeriesFilter').value = '';
    document.getElementById('scheduleYearFilter').value = '';
    document.getElementById('scheduleFilterForm').submit();
}

function resetResultsFilters() {
    document.getElementById('resultsSeriesFilter').value = '';
    document.getElementById('resultsSeasonFilter').value = '';
    document.getElementById('resultsFilterForm').submit();
}

function downloadScheduleImage(imageUrl, fileName) {
    const a = document.createElement('a');
    a.href = imageUrl;
    a.download = fileName || 'schedule.jpg';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
}

function openImageModal(imageUrl, title) {
    const modal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    const modalTitle = document.getElementById('modalTitle');
    
    modalImage.src = imageUrl;
    modalTitle.textContent = title || 'Schedule Image';
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
    document.getElementById('modalImage').src = '';
}

document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const tabParam = urlParams.get('tab');
    
    if (tabParam === 'results') {
        showSection('results');
    } else {
        showSection('schedules');
    }
    
    // Auto-submit untuk filter
    const scheduleSeries = document.getElementById('scheduleSeriesFilter');
    const scheduleYear = document.getElementById('scheduleYearFilter');
    const resultsSeries = document.getElementById('resultsSeriesFilter');
    const resultsSeason = document.getElementById('resultsSeasonFilter');
    
    if (scheduleSeries) scheduleSeries.addEventListener('change', () => document.getElementById('scheduleFilterForm').submit());
    if (scheduleYear) scheduleYear.addEventListener('change', () => document.getElementById('scheduleFilterForm').submit());
    if (resultsSeries) resultsSeries.addEventListener('change', () => document.getElementById('resultsFilterForm').submit());
    if (resultsSeason) resultsSeason.addEventListener('change', () => document.getElementById('resultsFilterForm').submit());
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeImageModal();
});

document.getElementById('imageModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeImageModal();
});
</script>

@push('styles')
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .section-content {
        animation: fadeIn 0.3s ease-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .group-hover\:translate-x-1 {
        transition: transform 0.2s ease;
    }
    
    input::placeholder {
        color: #9ca3af;
        font-weight: 400;
    }
    
    input:focus::placeholder {
        color: #cbd5e1;
    }
</style>
@endpush

@endsection