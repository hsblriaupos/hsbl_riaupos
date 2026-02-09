{{-- Schedule & Results Tabs --}}
<div class="schedule-tabs flex justify-center space-x-2">
    <button id="scheduleTab" class="tab-btn {{ ($activeTab ?? 'schedule') == 'schedule' ? 'active' : '' }}" onclick="switchTab('schedule')">
        <i class="fas fa-calendar-alt mr-2"></i>Schedules
    </button>
    <button id="resultsTab" class="tab-btn {{ ($activeTab ?? 'schedule') == 'results' ? 'active' : '' }}" onclick="switchTab('results')">
        <i class="fas fa-trophy mr-2"></i>Results
    </button>
</div>