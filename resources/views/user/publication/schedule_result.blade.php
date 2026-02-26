@extends('user.layouts.app')

@section('title', 'Schedules & Results - SBL Riau Pos')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Page Header -->
    <div class="text-center mb-8 md:mb-10">
        <div class="inline-block px-5 py-1.5 bg-gradient-to-r from-blue-600 to-blue-500 rounded-full mb-3 shadow-sm">
            <span class="text-white text-xs font-semibold">TRACK PROGRESS</span>
        </div>
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">Schedules & Results</h1>
        <p class="text-gray-600 max-w-2xl mx-auto text-sm">
            Follow match schedules and view real-time results from Honda Student Basketball League
        </p>
    </div>

    <!-- Tabs Navigation -->
    <div class="flex space-x-1 bg-white rounded-xl shadow-sm p-1 mb-6 border border-gray-200">
        <button id="schedulesTab" 
                class="flex-1 py-2.5 px-4 text-sm font-medium rounded-lg transition-all duration-200 bg-blue-500 text-white"
                onclick="showSection('schedules')">
            <i class="fas fa-calendar-alt mr-2"></i>
            Schedules
        </button>
        <button id="resultsTab" 
                class="flex-1 py-2.5 px-4 text-sm font-medium rounded-lg transition-all duration-200 text-gray-700 hover:text-blue-600"
                onclick="showSection('results')">
            <i class="fas fa-trophy mr-2"></i>
            Results
        </button>
    </div>

    <!-- Schedules Section -->
    <section id="schedulesSection" class="section-content">
        <!-- FILTER BAR UNTUK SCHEDULES -->
        <form id="scheduleFilterForm" method="GET" action="{{ route('user.schedule_result') }}" class="bg-white rounded-xl shadow-sm p-4 mb-6 border border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <!-- Filter Section -->
                <div class="flex flex-wrap gap-3 w-full md:w-auto">
                    <!-- Series Filter -->
                    <select name="series" id="scheduleSeriesFilter" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm bg-white min-w-[120px]">
                        <option value="">All Series</option>
                        @if(isset($seriesList) && count($seriesList) > 0)
                            @foreach($seriesList as $series)
                                <option value="{{ $series }}" {{ request('series') == $series ? 'selected' : '' }}>{{ $series }}</option>
                            @endforeach
                        @endif
                    </select>
                    
                    <!-- Year Filter -->
                    <select name="year" id="scheduleYearFilter" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm bg-white min-w-[100px]">
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
                </div>
                
                <!-- Action Buttons - SELALU TAMPIL -->
                <div class="flex gap-2">
                    <!-- Apply Button -->
                    <button type="submit" 
                            class="px-4 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow-sm hover:shadow text-sm font-medium flex items-center">
                        <i class="fas fa-filter mr-1.5"></i> Apply
                    </button>
                    
                    <!-- Reset Button -->
                    <button type="button" 
                            onclick="resetScheduleFilters()" 
                            class="px-4 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 text-sm font-medium text-gray-700 transition-colors flex items-center">
                        <i class="fas fa-redo mr-1.5"></i> Reset
                    </button>
                </div>
            </div>
            
            <!-- Hidden fields untuk menjaga tab state -->
            <input type="hidden" name="tab" value="schedules">
        </form>

        <div class="bg-white rounded-xl shadow-lg p-5 mb-6 border border-gray-100">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-900 mb-2 md:mb-0">
                    <i class="fas fa-calendar-alt text-blue-500 mr-2"></i>
                    Upcoming Matches
                </h2>
                <div class="text-xs text-blue-600 font-medium">
                    <i class="fas fa-clock mr-1.5"></i>
                    All times in WIB
                </div>
            </div>

            @if(isset($schedules) && count($schedules) > 0)
                <!-- Mobile View - Simple List -->
                <div class="lg:hidden space-y-4">
                    @foreach($schedules as $schedule)
                        @php
                            // FIX: Gunakan path yang benar sesuai controller awal
                            $imageUrl = $schedule->image_url ?? 
                                (isset($schedule->layout_image) ? 
                                    (str_starts_with($schedule->layout_image, 'http') ? 
                                        $schedule->layout_image : 
                                        asset('images/schedule/' . basename($schedule->layout_image))
                                    ) : 
                                    asset('images/default-schedule.jpg')
                                );
                            
                            // Pastikan URL valid
                            if (!filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                                $imageUrl = asset('images/default-schedule.jpg');
                            }
                            
                            // Generate nama file untuk download
                            $fileName = Str::slug($schedule->main_title ?? 'schedule') . '-' . date('Ymd', strtotime($schedule->match_date ?? now())) . '.jpg';
                        @endphp
                        <div class="border border-gray-200 rounded-lg overflow-hidden hover:border-blue-300 transition-colors">
                            <!-- Image Clickable untuk modal -->
                            <div class="relative h-40 cursor-pointer group" onclick="openImageModal('{{ $imageUrl }}', '{{ $schedule->main_title ?? 'Match Schedule' }}', '{{ $schedule->caption ?? '' }}')">
                                <img src="{{ $imageUrl }}" 
                                     alt="{{ $schedule->main_title ?? 'Match Schedule' }}" 
                                     class="w-full h-full object-cover"
                                     onerror="this.src='{{ asset('images/default-schedule.jpg') }}'">
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all duration-300 flex items-center justify-center">
                                    <!-- Download Button Overlay -->
                                    <button onclick="event.stopPropagation(); downloadScheduleImage('{{ $imageUrl }}', '{{ $fileName }}')"
                                            class="absolute bottom-3 right-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-full p-2.5 shadow-lg hover:shadow-xl hover:scale-110 active:scale-95 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-300 focus:ring-offset-2 z-10">
                                        <i class="fas fa-download text-sm"></i>
                                    </button>
                                    <!-- Zoom Icon -->
                                    <div class="bg-white/90 rounded-full p-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <i class="fas fa-expand text-blue-600"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="p-4">
                                <div class="flex items-start justify-between mb-2">
                                    <div>
                                        <h3 class="font-semibold text-gray-900">{{ $schedule->main_title ?? 'Match Schedule' }}</h3>
                                    </div>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-600">
                                        {{ $schedule->series_name ?? 'HSBL' }}
                                    </span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="far fa-clock mr-2 text-blue-500"></i>
                                    {{ $schedule->formatted_date ?? 'Date TBD' }}
                                </div>
                                <!-- Download Button for Mobile -->
                                <div class="mt-3">
                                    <button onclick="downloadScheduleImage('{{ $imageUrl }}', '{{ $fileName }}')"
                                            class="w-full py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-200 shadow-sm hover:shadow text-sm font-medium flex items-center justify-center">
                                        <i class="fas fa-download mr-2"></i> Download Schedule
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Desktop View - Grid 4 Kolom -->
                <div class="hidden lg:grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach($schedules as $schedule)
                        @php
                            // FIX: Gunakan path yang benar sesuai controller awal
                            $imageUrl = $schedule->image_url ?? 
                                (isset($schedule->layout_image) ? 
                                    (str_starts_with($schedule->layout_image, 'http') ? 
                                        $schedule->layout_image : 
                                        asset('images/schedule/' . basename($schedule->layout_image))
                                    ) : 
                                    asset('images/default-schedule.jpg')
                                );
                            
                            // Pastikan URL valid
                            if (!filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                                $imageUrl = asset('images/default-schedule.jpg');
                            }
                            
                            // Generate nama file untuk download
                            $fileName = Str::slug($schedule->main_title ?? 'schedule') . '-' . date('Ymd', strtotime($schedule->match_date ?? now())) . '.jpg';
                        @endphp
                        <div class="bg-gradient-to-br from-white to-blue-50 border border-blue-100 rounded-xl overflow-hidden hover:shadow-md transition-shadow duration-300">
                            <!-- Image Clickable untuk modal -->
                            <div class="relative h-40 cursor-pointer group" onclick="openImageModal('{{ $imageUrl }}', '{{ $schedule->main_title ?? 'Match Schedule' }}', '{{ $schedule->caption ?? '' }}')">
                                <img src="{{ $imageUrl }}" 
                                     alt="{{ $schedule->main_title ?? 'Match Schedule' }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                     onerror="this.src='{{ asset('images/default-schedule.jpg') }}'">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <!-- Download Button -->
                                    <button onclick="event.stopPropagation(); downloadScheduleImage('{{ $imageUrl }}', '{{ $fileName }}')"
                                            class="absolute bottom-3 right-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-full p-2.5 shadow-lg hover:shadow-xl hover:scale-110 active:scale-95 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-300 focus:ring-offset-2 z-10">
                                        <i class="fas fa-download text-sm"></i>
                                    </button>
                                    <!-- Zoom Icon -->
                                    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white rounded-full p-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <i class="fas fa-expand-alt text-blue-600 text-sm"></i>
                                    </div>
                                </div>
                                <div class="absolute top-2 right-2">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-sm">
                                        {{ $schedule->series_name ?? 'HSBL' }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="p-3">
                                <div class="mb-2">
                                    <h3 class="font-bold text-gray-900 text-sm mb-1 line-clamp-2">{{ $schedule->main_title ?? 'Match Schedule' }}</h3>
                                    <div class="flex items-center text-xs text-gray-600 mb-1">
                                        <i class="fas fa-calendar-day mr-1.5 text-blue-500"></i>
                                        {{ $schedule->formatted_date ?? 'Date TBD' }}
                                    </div>
                                </div>
                                
                                <!-- Download Button for Desktop -->
                                <button onclick="downloadScheduleImage('{{ $imageUrl }}', '{{ $fileName }}')"
                                        class="w-full mt-3 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-200 shadow-sm hover:shadow text-xs font-medium flex items-center justify-center">
                                    <i class="fas fa-download mr-1.5"></i> Download Image
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                {{-- Pagination --}}
                @if(isset($schedules) && method_exists($schedules, 'hasPages') && $schedules->hasPages())
                    <div class="mt-8 flex justify-center">
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-2">
                            {{ $schedules->appends(request()->except('page'))->links('vendor.pagination.tailwind') }}
                        </div>
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="text-center py-12 bg-gradient-to-br from-blue-50 to-white rounded-2xl border-2 border-dashed border-blue-200">
                    <div class="max-w-md mx-auto">
                        <div class="w-16 h-16 mx-auto mb-4 text-blue-200">
                            <i class="fas fa-calendar-alt text-4xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-700 mb-2">No Schedules Available</h3>
                        <p class="text-gray-500 mb-4 text-sm">
                            @if(request()->hasAny(['series', 'year']))
                                No schedules found matching your filters. Try different criteria.
                            @else
                                Match schedules will be posted here soon.
                            @endif
                        </p>
                        @if(request()->hasAny(['series', 'year']))
                        <button onclick="resetScheduleFilters()" 
                                class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors text-sm">
                            <i class="fas fa-redo mr-2"></i>
                            Clear Filters
                        </button>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- Results Section -->
    <section id="resultsSection" class="section-content hidden">
        <!-- FILTER BAR UNTUK RESULTS -->
        <form id="resultsFilterForm" method="GET" action="{{ route('user.schedule_result') }}" class="bg-white rounded-xl shadow-sm p-4 mb-6 border border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <!-- Filter Section -->
                <div class="flex flex-wrap gap-3 w-full md:w-auto">
                    <!-- Series Filter -->
                    <select name="results_series" id="resultsSeriesFilter" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm bg-white min-w-[120px]">
                        <option value="">All Series</option>
                        @if(isset($seriesListResults) && count($seriesListResults) > 0)
                            @foreach($seriesListResults as $series)
                                <option value="{{ $series }}" {{ request('results_series') == $series ? 'selected' : '' }}>{{ $series }}</option>
                            @endforeach
                        @endif
                    </select>
                    
                    <!-- Season Filter -->
                    <select name="results_season" id="resultsSeasonFilter" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm bg-white min-w-[100px]">
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
                </div>
                
                <!-- Action Buttons - SELALU TAMPIL -->
                <div class="flex gap-2">
                    <!-- Reset Button -->
                    <button type="button" 
                            onclick="resetResultsFilters()" 
                            class="px-4 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 text-sm font-medium text-gray-700 transition-colors flex items-center">
                        <i class="fas fa-redo mr-1.5"></i> Reset
                    </button>
                </div>
            </div>
            
            <!-- Hidden fields untuk menjaga tab state -->
            <input type="hidden" name="tab" value="results">
        </form>

        <div class="bg-gradient-to-r from-blue-600 to-blue-500 rounded-xl shadow-lg p-5 mb-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-white mb-2 md:mb-0">
                    <i class="fas fa-trophy mr-2"></i>
                    Match Results
                </h2>
                <div class="text-xs text-blue-100 font-medium">
                    <i class="fas fa-sync-alt mr-1.5"></i>
                    Updated in real-time
                </div>
            </div>

            @if(isset($results) && count($results) > 0)
                <div class="space-y-3">
                    @foreach($results as $result)
                        @php
                            // Determine status
                            $statusConfig = [
                                'completed' => ['class' => 'bg-green-100 text-green-800', 'icon' => 'fas fa-check-circle', 'text' => 'Completed'],
                                'upcoming' => ['class' => 'bg-yellow-100 text-yellow-800', 'icon' => 'fas fa-clock', 'text' => 'Upcoming'],
                                'live' => ['class' => 'bg-red-100 text-red-800', 'icon' => 'fas fa-play-circle', 'text' => 'Live'],
                                'publish' => ['class' => 'bg-purple-100 text-purple-800', 'icon' => 'fas fa-upload', 'text' => 'Published'],
                                'scheduled' => ['class' => 'bg-blue-100 text-blue-800', 'icon' => 'fas fa-calendar-check', 'text' => 'Scheduled']
                            ];
                            
                            $status = $result->status ?? 'scheduled';
                            $statusInfo = $statusConfig[$status] ?? $statusConfig['scheduled'];
                            
                            // Filter out unwanted data
                            $matchTime = !empty($result->match_time) && $result->match_time !== '00:00' ? $result->match_time . ' WIB' : null;
                            $showStatus = !in_array($status, ['publish']) && (!isset($result->status_text) || $result->status_text !== 'Published');
                            
                            // Format match date jika ada
                            $matchDate = null;
                            if (isset($result->match_date) && $result->match_date) {
                                try {
                                    $matchDate = \Carbon\Carbon::parse($result->match_date)->format('d M Y');
                                } catch (Exception $e) {
                                    $matchDate = $result->match_date;
                                }
                            }
                            
                            // FIXED: Path logo yang benar - gunakan path dari controller awal
                            $team1Logo = null;
                            $team2Logo = null;
                            
                            if (isset($result->team1_logo) && $result->team1_logo) {
                                $team1Logo = str_starts_with($result->team1_logo, 'http') 
                                    ? $result->team1_logo 
                                    : (str_contains($result->team1_logo, 'storage/') 
                                        ? asset($result->team1_logo) 
                                        : asset('storage/school_logos/' . basename($result->team1_logo))
                                    );
                            }
                            
                            if (isset($result->team2_logo) && $result->team2_logo) {
                                $team2Logo = str_starts_with($result->team2_logo, 'http') 
                                    ? $result->team2_logo 
                                    : (str_contains($result->team2_logo, 'storage/') 
                                        ? asset($result->team2_logo) 
                                        : asset('storage/school_logos/' . basename($result->team2_logo))
                                    );
                            }
                            
                            // Pastikan URL logo valid
                            if ($team1Logo && !filter_var($team1Logo, FILTER_VALIDATE_URL)) {
                                $team1Logo = null;
                            }
                            
                            if ($team2Logo && !filter_var($team2Logo, FILTER_VALIDATE_URL)) {
                                $team2Logo = null;
                            }
                        @endphp
                        
                        <div class="bg-white/95 backdrop-blur-sm rounded-lg p-3 shadow-md hover:shadow-lg transition-all duration-300">
                            <!-- Match Header - Compact -->
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-3 pb-2 border-b border-gray-200">
                                <div class="mb-2 sm:mb-0">
                                    <div class="flex items-center text-xs text-gray-600 mb-1">
                                        <i class="fas fa-basketball-ball mr-1.5 text-blue-500"></i>
                                        <span class="font-medium truncate">{{ $result->competition ?? 'HSBL Honda' }}</span>
                                    </div>
                                    <div class="flex flex-wrap items-center gap-1">
                                        @if(isset($result->series) && $result->series)
                                        <div class="flex items-center bg-blue-50 text-blue-700 px-2 py-1 rounded text-xs font-semibold">
                                            <i class="fas fa-layer-group mr-1 text-blue-500 text-xs"></i>
                                            {{ $result->series }}
                                        </div>
                                        @endif
                                        
                                        @if(isset($result->season) && $result->season)
                                        <div class="flex items-center bg-amber-50 text-amber-700 px-2 py-1 rounded text-xs font-semibold">
                                            <i class="fas fa-calendar-star mr-1 text-amber-500 text-xs"></i>
                                            {{ $result->season }}
                                        </div>
                                        @endif
                                        
                                        @if(isset($result->phase) && $result->phase)
                                        <div class="flex items-center bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs font-semibold">
                                            <i class="fas fa-flag mr-1 text-gray-500 text-xs"></i>
                                            {{ $result->phase }}
                                        </div>
                                        @endif
                                        
                                        @if(isset($result->competition_type) && $result->competition_type)
                                        <div class="flex items-center bg-gradient-to-r from-green-500 to-green-600 text-white px-2 py-1 rounded text-xs font-semibold">
                                            <i class="fas fa-tag mr-1.5 text-xs"></i>
                                            {{ $result->competition_type }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-right text-xs">
                                    @if($matchDate)
                                        <div class="flex items-center justify-end text-gray-600 mb-1">
                                            <i class="far fa-calendar-alt mr-1.5 text-blue-500"></i>
                                            <span>{{ $matchDate }}</span>
                                        </div>
                                    @endif
                                    @if($matchTime)
                                        <div class="flex items-center justify-end text-gray-600">
                                            <i class="far fa-clock mr-1.5 text-blue-500"></i>
                                            <span>{{ $matchTime }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Teams and Score - Compact -->
                            <div class="grid grid-cols-3 gap-3 mb-3">
                                <!-- Team 1 -->
                                <div class="flex flex-col items-center text-center">
                                    <div class="w-12 h-12 mb-2 flex items-center justify-center bg-white shadow-sm border border-gray-200 p-1">
                                        @if($team1Logo)
                                            <img src="{{ $team1Logo }}" 
                                                 alt="{{ $result->team1_name ?? 'Team 1' }}"
                                                 class="max-w-full max-h-full object-contain"
                                                 onerror="this.onerror=null;this.src='{{ asset('images/default-team-logo.png') }}'">
                                            <div class="w-full h-full bg-gradient-to-br from-blue-500 to-blue-600 hidden items-center justify-center p-1">
                                                <i class="fas fa-school text-white text-sm"></i>
                                            </div>
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center p-1">
                                                <i class="fas fa-school text-white text-sm"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-900 text-xs mb-0.5 truncate">{{ $result->team1_name ?? 'SEKOLAH CITRA KASIH' }}</div>
                                    </div>
                                </div>
                                
                                <!-- VS and Score -->
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center mb-1 shadow-sm">
                                        <span class="text-white font-bold text-xs">VS</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-xl font-bold text-gray-900">{{ $result->score_1 ?? '0' }}</span>
                                        <span class="text-lg text-gray-500 font-bold">:</span>
                                        <span class="text-xl font-bold text-gray-900">{{ $result->score_2 ?? '0' }}</span>
                                    </div>
                                </div>
                                
                                <!-- Team 2 -->
                                <div class="flex flex-col items-center text-center">
                                    <div class="w-12 h-12 mb-2 flex items-center justify-center bg-white shadow-sm border border-gray-200 p-1">
                                        @if($team2Logo)
                                            <img src="{{ $team2Logo }}" 
                                                 alt="{{ $result->team2_name ?? 'Team 2' }}"
                                                 class="max-w-full max-h-full object-contain"
                                                 onerror="this.onerror=null;this.src='{{ asset('images/default-team-logo.png') }}'">
                                            <div class="w-full h-full bg-gradient-to-br from-blue-500 to-blue-600 hidden items-center justify-center p-1">
                                                <i class="fas fa-school text-white text-sm"></i>
                                            </div>
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center p-1">
                                                <i class="fas fa-school text-white text-sm"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-900 text-xs mb-0.5 truncate">{{ $result->team2_name ?? 'SMA PATRA DHARMA' }}</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Match Footer - Compact -->
                            <div class="flex flex-col items-center justify-center pt-2 border-t border-gray-200">
                                @if(isset($result->has_scoresheet) && $result->has_scoresheet)
                                    <a href="{{ route('user.results.download.scoresheet', ['id' => $result->id]) }}" 
                                       class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-200 shadow-sm hover:shadow text-xs mb-2"
                                       target="_blank"
                                       download>
                                        <i class="fas fa-download mr-1.5 text-xs"></i>
                                        Scoresheet
                                    </a>
                                @else
                                    <div class="text-center mb-2">
                                        <span class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-500 rounded text-xs">
                                            <i class="fas fa-times-circle mr-1 text-xs"></i>
                                            No Scoresheet
                                        </span>
                                    </div>
                                @endif
                                
                                @if($showStatus)
                                    <div class="flex items-center">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $statusInfo['class'] }}">
                                            <i class="{{ $statusInfo['icon'] }} mr-1 text-xs"></i>
                                            {{ $result->status_text ?? $statusInfo['text'] }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                
                {{-- Pagination --}}
                @if(isset($results) && method_exists($results, 'hasPages') && $results->hasPages())
                    <div class="mt-6 flex justify-center">
                        <div class="bg-white/95 backdrop-blur-sm rounded-lg shadow-sm border border-gray-200 p-2">
                            {{ $results->appends(request()->except('page'))->links('vendor.pagination.tailwind') }}
                        </div>
                    </div>
                @endif
            @else
                <!-- Empty State for Results -->
                <div class="bg-white/95 backdrop-blur-sm rounded-xl p-6 text-center">
                    <div class="w-12 h-12 mx-auto mb-3 text-blue-400">
                        <i class="fas fa-trophy text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">No Results Available</h3>
                    <p class="text-gray-600 mb-3 text-sm">
                        @if(request()->hasAny(['results_series', 'results_season']))
                            No results found matching your filters. Try different criteria.
                        @else
                            Match results will be displayed here after the games.
                        @endif
                    </p>
                    @if(request()->hasAny(['results_series', 'results_season']))
                    <button onclick="resetResultsFilters()" 
                            class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors text-sm">
                        <i class="fas fa-redo mr-2"></i>
                        Clear Filters
                    </button>
                    @endif
                </div>
            @endif
        </div>
    </section>
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 z-50 hidden bg-black/90 flex items-center justify-center p-4">
    <div class="relative max-w-4xl max-h-[90vh] w-full">
        <!-- Close Button -->
        <button onclick="closeImageModal()" 
                class="absolute -top-12 right-0 text-white hover:text-blue-300 text-2xl transition-colors focus:outline-none">
            <i class="fas fa-times-circle bg-black/50 rounded-full p-1"></i>
        </button>
        
        <!-- Image Container -->
        <div class="bg-white rounded-lg overflow-hidden shadow-2xl">
            <!-- Image -->
            <div class="max-h-[70vh] overflow-auto">
                <img id="modalImage" src="" alt="" class="w-full h-auto object-contain">
            </div>
            
            <!-- Caption Section -->
            <div id="modalContent" class="p-4 bg-white">
                <div id="modalTitle" class="font-bold text-gray-900 text-lg mb-2"></div>
                <div id="modalCaption" class="text-gray-600 text-sm"></div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-submit saat filter diubah
document.addEventListener('DOMContentLoaded', function() {
    console.log('Schedule Result Page Loaded');
    
    // Check if there's a tab parameter in URL
    const urlParams = new URLSearchParams(window.location.search);
    const tabParam = urlParams.get('tab');
    
    // Initialize with correct tab based on URL or default to schedules
    if (tabParam === 'results') {
        showSection('results');
    } else {
        showSection('schedules');
    }
    
    // Auto-submit saat filter diubah
    const scheduleSeriesFilter = document.getElementById('scheduleSeriesFilter');
    const scheduleYearFilter = document.getElementById('scheduleYearFilter');
    const resultsSeriesFilter = document.getElementById('resultsSeriesFilter');
    const resultsSeasonFilter = document.getElementById('resultsSeasonFilter');
    
    if (scheduleSeriesFilter) {
        scheduleSeriesFilter.addEventListener('change', function() {
            // Submit form jika ada nilai yang dipilih
            if (this.value !== '') {
                document.getElementById('scheduleFilterForm').submit();
            }
        });
    }
    
    if (scheduleYearFilter) {
        scheduleYearFilter.addEventListener('change', function() {
            // Submit form jika ada nilai yang dipilih
            if (this.value !== '') {
                document.getElementById('scheduleFilterForm').submit();
            }
        });
    }
    
    if (resultsSeriesFilter) {
        resultsSeriesFilter.addEventListener('change', function() {
            // Submit form jika ada nilai yang dipilih
            if (this.value !== '') {
                document.getElementById('resultsFilterForm').submit();
            }
        });
    }
    
    if (resultsSeasonFilter) {
        resultsSeasonFilter.addEventListener('change', function() {
            // Submit form jika ada nilai yang dipilih
            if (this.value !== '') {
                document.getElementById('resultsFilterForm').submit();
            }
        });
    }
    
    // Clean up any problematic intervals
    clearAllIntervals();
});

function clearAllIntervals() {
    // Clear any existing intervals
    const highestIntervalId = setInterval(() => {}, 9999);
    for (let i = 1; i < highestIntervalId; i++) {
        clearInterval(i);
    }
}

function resetScheduleFilters() {
    // Reset form values
    document.getElementById('scheduleSeriesFilter').value = '';
    document.getElementById('scheduleYearFilter').value = '';
    
    // Submit the form to reload page with empty filters
    document.getElementById('scheduleFilterForm').submit();
}

function resetResultsFilters() {
    // Reset form values
    document.getElementById('resultsSeriesFilter').value = '';
    document.getElementById('resultsSeasonFilter').value = '';
    
    // Submit the form to reload page with empty filters
    document.getElementById('resultsFilterForm').submit();
}

function showSection(section) {
    console.log('Showing section:', section);
    
    // Hide all sections
    document.querySelectorAll('.section-content').forEach(el => {
        el.classList.add('hidden');
    });
    
    // Show selected section
    const targetSection = document.getElementById(section + 'Section');
    if (targetSection) {
        targetSection.classList.remove('hidden');
    }
    
    // Update tab styles
    const tabs = ['schedules', 'results'];
    tabs.forEach(tab => {
        const tabBtn = document.getElementById(tab + 'Tab');
        if (tabBtn) {
            if (tab === section) {
                tabBtn.classList.add('bg-blue-500', 'text-white');
                tabBtn.classList.remove('text-gray-700', 'hover:text-blue-600');
            } else {
                tabBtn.classList.remove('bg-blue-500', 'text-white');
                tabBtn.classList.add('text-gray-700', 'hover:text-blue-600');
            }
        }
    });
    
    // Update URL without page reload (for bookmarking)
    const url = new URL(window.location);
    url.searchParams.set('tab', section);
    window.history.pushState({}, '', url);
}

// ✅ Fungsi untuk download Schedule Image
function downloadScheduleImage(imageUrl, fileName) {
    console.log('Downloading schedule image:', imageUrl);
    
    // Tampilkan loading indicator pada button yang diklik
    const button = event.currentTarget;
    const originalContent = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    button.disabled = true;
    
    // Buat elemen anchor untuk download
    const a = document.createElement('a');
    a.href = imageUrl;
    a.download = fileName || 'schedule.jpg';
    
    // Trigger download
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    
    // Reset button setelah 2 detik
    setTimeout(() => {
        button.innerHTML = originalContent;
        button.disabled = false;
    }, 2000);
}

// Image Modal Functions
function openImageModal(imageUrl, title, caption) {
    console.log('Opening image modal:', imageUrl);
    
    const modal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    const modalTitle = document.getElementById('modalTitle');
    const modalCaption = document.getElementById('modalCaption');
    
    if (!modal || !modalImage) {
        console.error('Image modal elements not found');
        return;
    }
    
    // Set image source
    modalImage.src = imageUrl;
    
    // Set title
    if (modalTitle) {
        modalTitle.textContent = title || 'Schedule Image';
    }
    
    // Set caption (jika ada)
    if (modalCaption) {
        if (caption && caption.trim() !== '') {
            modalCaption.textContent = caption;
            modalCaption.style.display = 'block';
        } else {
            modalCaption.textContent = 'No caption available';
            modalCaption.style.display = 'block';
        }
    }
    
    // Show modal
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Focus on close button for accessibility
    setTimeout(() => {
        const closeBtn = modal.querySelector('button');
        if (closeBtn) closeBtn.focus();
    }, 100);
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        
        // Clear modal content
        const modalImage = document.getElementById('modalImage');
        const modalTitle = document.getElementById('modalTitle');
        const modalCaption = document.getElementById('modalCaption');
        
        if (modalImage) modalImage.src = '';
        if (modalTitle) modalTitle.textContent = '';
        if (modalCaption) modalCaption.textContent = '';
    }
}

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});

// Close modal when clicking outside image
document.getElementById('imageModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageModal();
    }
});

// Handle browser back/forward buttons for tab state
window.addEventListener('popstate', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const tabParam = urlParams.get('tab');
    
    if (tabParam === 'results') {
        showSection('results');
    } else {
        showSection('schedules');
    }
});
</script>

<style>
/* Smooth transitions */
.section-content {
    animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Image modal animations */
#imageModal {
    opacity: 0;
    transition: opacity 0.3s ease;
}

#imageModal:not(.hidden) {
    opacity: 1;
}

#imageModal img {
    transform: scale(0.95);
    transition: transform 0.3s ease;
}

#imageModal:not(.hidden) img {
    transform: scale(1);
}

/* Image modal styling */
#imageModal .bg-white {
    border-radius: 12px;
    max-height: 85vh;
}

#modalContent {
    border-top: 1px solid #e5e7eb;
    max-width: 100%;
}

#modalTitle {
    font-size: 1.125rem;
    line-height: 1.5rem;
    font-weight: 600;
}

#modalCaption {
    font-size: 0.875rem;
    line-height: 1.25rem;
    color: #6b7280;
}

/* Fix for image display */
img {
    max-width: 100%;
    height: auto;
    display: block;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    #schedulesFloatingBtn {
        bottom: 5rem;
        right: 1rem;
    }
    
    #schedulesFloatingBtn button {
        width: 3.5rem;
        height: 3.5rem;
    }
    
    /* Image modal untuk mobile */
    #imageModal {
        padding: 1rem;
    }
    
    #imageModal .max-w-4xl {
        max-width: 100%;
    }
    
    #modalContent {
        padding: 1rem;
    }
    
    #modalTitle {
        font-size: 1rem;
    }
    
    #modalCaption {
        font-size: 0.8125rem;
    }
    
    /* Better mobile layout for filter buttons */
    .flex-wrap {
        gap: 0.5rem;
    }
    
    .flex-wrap > * {
        flex: 1 1 calc(50% - 0.5rem);
        min-width: auto;
    }
    
    .flex-wrap .flex.gap-2 {
        width: 100%;
        justify-content: space-between;
    }
    
    .flex-wrap .flex.gap-2 button {
        flex: 1;
    }
}

/* Grid untuk 4 kolom */
@media (min-width: 1024px) {
    .lg\:grid-cols-4 {
        grid-template-columns: repeat(4, minmax(0, 1fr));
    }
}

/* Button styles */
button[type="submit"] {
    transition: all 0.2s ease;
}

button[type="submit"]:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

button[type="submit"]:active {
    transform: translateY(0);
}

/* Form styles */
form input:focus, form select:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Download button overlay styles */
.group:hover .group-hover\:opacity-100 {
    opacity: 1;
}

/* Cursor pointer untuk elemen yang bisa diklik */
.cursor-pointer {
    cursor: pointer;
}

.cursor-pointer:hover {
    opacity: 0.9;
}

/* Animasi hover untuk image container */
.group:hover .group-hover\:opacity-100 {
    opacity: 1;
}

.group:hover .group-hover\:scale-105 {
    transform: scale(1.05);
}

/* Smooth scrolling */
html {
    scroll-behavior: smooth;
}
</style>
@endsection