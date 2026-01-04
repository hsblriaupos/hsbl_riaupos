@extends('admin.layouts.app')
@section('title', 'Master Data - Administrator')
@section('content')

@php $activeTab = 'event'; @endphp
@include('partials.tabs_pub', compact('activeTab'))

@push('styles')
<link href="{{ asset('css/data.css') }}" rel="stylesheet" />
<style>
    .pagination .active span {
        background-color: #2563eb;
        color: white;
        border-radius: 9999px;
        padding: 0.5rem 1rem;
    }

    #filterForm select {
        width: 160px;
        min-width: 160px;
        max-width: 160px;
        box-sizing: border-box;
    }
</style>
@endpush

@push('scripts')
@include('partials.sweetalert')
<script>
    // Fungsi membuka modal edit dan isi form dengan data event yg dipilih
    // Input waktu sudah dalam format HH:mm waktu Jakarta, jadi langsung masuk ke input time
    function openEditEventModal(id, name, startDate, endDate, startTime, endTime) {
        document.getElementById('editEventId').value = id;
        document.getElementById('edit_event_name').value = name;
        document.getElementById('edit_start_date').value = startDate;
        document.getElementById('edit_end_date').value = endDate;
        document.getElementById('edit_start_time').value = startTime;
        document.getElementById('edit_end_time').value = endTime;
        document.getElementById('editEventModal').classList.remove('hidden');
    }

    // Handler tombol edit klik, ambil data atribut dan panggil modal
    function handleEditEventClick(button) {
        const id = button.getAttribute('data-id');
        const name = button.getAttribute('data-name');
        const startDate = button.getAttribute('data-start-date');
        const endDate = button.getAttribute('data-end-date');
        // startTime dan endTime sudah di-set sebagai waktu Jakarta saat render blade
        const startTime = button.getAttribute('data-start-time') ?? '';
        const endTime = button.getAttribute('data-end-time') ?? '';
        openEditEventModal(id, name, startDate, endDate, startTime, endTime);
    }

    // Tutup modal edit event
    function closeEditEventModal() {
        document.getElementById('editEventModal').classList.add('hidden');
    }
    document.querySelectorAll('#filterForm select').forEach(select => {
        select.addEventListener('change', () => {
            document.getElementById('filterForm').submit();
        });
    });

    var searchInput = document.getElementById('searchInput');
    var searchForm = document.getElementById('searchForm');

    if (searchInput) {
        // Submit kalau tekan enter
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchForm.submit();
            }
        });

        // Submit kalau input kosong langsung reload tanpa search param
        searchInput.addEventListener('input', function() {
            if (this.value === '') {
                searchForm.submit();
            }
        });
    }

    function updateEventStatuses() {
        const now = new Date();

        document.querySelectorAll('.event-status').forEach(el => {
            const start = new Date(el.dataset.start);
            const end = new Date(el.dataset.end);

            if (isNaN(start.getTime()) || isNaN(end.getTime())) {
                console.warn('Invalid date for element:', el);
                return;
            }

            let text = '';
            let classes = '';

            if (now >= start && now <= end) {
                text = '📢 Published';
                classes = 'bg-green-100 text-green-700';
            } else if (now < start) {
                text = '⏳ Scheduled';
                classes = 'bg-yellow-100 text-yellow-700';
            } else {
                text = '🎉 Done';
                classes = 'bg-gray-100 text-gray-600';
            }

            el.textContent = text;
            el.classList.remove('bg-green-100', 'text-green-700', 'bg-yellow-100', 'text-yellow-700', 'bg-gray-100', 'text-gray-600');
            classes.split(' ').forEach(cls => el.classList.add(cls));
        });
    }

    // Jalankan langsung saat halaman dibuka
    updateEventStatuses();

    // Update status setiap 1 menit
    setInterval(updateEventStatuses, 60000);
</script>
@endpush

<div class="py-4">
    <h2 class="text-2xl font-bold mb-6">Set Public Event</h2>

    <div class="bg-white p-6 rounded shadow mt-2">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Set Event</h3>
        </div>

        <form action="{{ route('admin.event.store') }}" method="POST" class="mb-6">
            @csrf
            <div class="grid grid-cols-3 gap-6 mb-4">
                <div>
                    <label for="event_name" class="block font-medium mb-1">Event Name</label>
                    <input type="text" name="event_name" id="event_name" class="w-full border rounded px-3 py-2" required>
                </div>
                <div>
                    <label for="start_date" class="block font-medium mb-1">Start Date</label>
                    <input type="date" name="start_date" id="start_date" class="w-full border rounded px-3 py-2" required>
                </div>
                <div>
                    <label for="end_date" class="block font-medium mb-1">End Date</label>
                    <input type="date" name="end_date" id="end_date" class="w-full border rounded px-3 py-2" required>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="start_time" class="block font-medium mb-1">Start Time</label>
                    <input type="time" name="start_time" id="start_time" class="w-full border rounded px-3 py-2" required>
                </div>
                <div>
                    <label for="end_time" class="block font-medium mb-1">End Time</label>
                    <input type="time" name="end_time" id="end_time" class="w-full border rounded px-3 py-2" required>
                </div>
            </div>

            <div class="text-center">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">Save Event</button>
            </div>
        </form>
    </div>

    <div class="bg-white p-6 rounded shadow mt-6">
        <!-- Filter + Reset Button (pojok kanan atas) -->
        <form method="GET" action="{{ url()->current() }}" id="filterForm" class="mb-4 flex justify-end space-x-4 items-end">
            <!-- Filter Bulan -->
            <div>
                <select name="filter_month" id="filter_month" class="border rounded px-2 py-1">
                    <option value="">Month</option>
                    @foreach(range(1,12) as $month)
                    <option value="{{ $month }}" {{ request('filter_month') == $month ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($month)->format('F') }}
                    </option>
                    @endforeach
                </select>
            </div>
            <!-- Filter Tahun -->
            <div>
                <select name="filter_year" id="filter_year" class="border rounded px-2 py-1 w-[150px]">
                    <option value="">Year</option>
                    @foreach($availableYears as $year)
                    <option value="{{ $year }}" {{ request('filter_year') == $year ? 'selected' : '' }}>
                        {{ $year }}
                    </option>
                    @endforeach
                </select>
            </div>
            <!-- Filter Event -->
            <div>
                <select name="filter_event" id="filter_event" class="border rounded px-2 py-1">
                    <option value="">Event</option>
                    @foreach($allEvents as $event)
                    <option value="{{ $event->id }}" {{ request('filter_event') == $event->id ? 'selected' : '' }}>
                        {{ $event->event_name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Reset Filter Button -->
            <div>
                <a href="{{ url()->current() }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">
                    Reset Filter
                </a>
            </div>
        </form>

        <!-- Judul + Baris bawah dengan show, urutkan, status + search bar -->
        <div class="flex flex-col space-y-2">
            <div>
                <h3 class="text-lg font-semibold">Existing Events</h3>
            </div>

            <div class="flex justify-between items-center mb-3">
                <div class="flex items-center space-x-4">
                    {{-- Show Per Page --}}
                    <form method="GET" class="text-xs flex items-center space-x-2">
                        <label for="per_page">Show:</label>
                        <select name="per_page" id="per_page" onchange="this.form.submit()" class="border border-gray-300 rounded px-2 py-1">
                            <option value="3" {{ request('per_page') == 3 ? 'selected' : '' }}>3</option>
                            <option value="6" {{ request('per_page') == 6 ? 'selected' : '' }}>6</option>
                            <option value="9" {{ request('per_page') == 9 ? 'selected' : '' }}>9</option>
                        </select>
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    </form>

                    {{-- Sort --}}
                    <form method="GET" class="text-xs flex items-center space-x-2">
                        <label for="sort" class="text-sm">Sort:</label>
                        <select name="sort" id="sort" onchange="this.form.submit()" class="border px-2 py-1 rounded text-sm">
                            <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>Terbaru</option>
                            <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Terlama</option>
                        </select>
                    </form>

                    {{-- Filter Status --}}
                    <form method="GET" class="text-xs flex items-center space-x-2">
                        <label for="status_filter" class="text-sm">Status:</label>
                        <select name="status" id="status_filter" onchange="this.form.submit()" class="border px-2 py-1 rounded text-sm">
                            <option value="">Semua</option>
                            <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>⏳ Scheduled</option>
                            <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>📢 Published</option>
                            <option value="done" {{ request('status') == 'done' ? 'selected' : '' }}>🎉 Done</option>
                        </select>
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <input type="hidden" name="per_page" value="{{ request('per_page') }}">
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                    </form>
                </div>

                {{-- Search Bar (di kanan banget) --}}
                <form method="GET" id="searchForm" class="text-xs">
                    <input
                        type="text"
                        name="search"
                        id="searchInput"
                        placeholder="Cari event..."
                        value="{{ request('search') }}"
                        class="border border-gray-300 rounded px-3 py-1 w-48 text-sm"
                        autocomplete="off" />
                    <input type="hidden" name="per_page" value="{{ request('per_page', 25) }}">
                    <input type="hidden" name="sort" value="{{ request('sort', 'desc') }}">
                </form>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left border">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 border text-center">No</th>
                        <th class="px-4 py-1 border text-center">Created</th>
                        <th class="px-4 py-2 border text-center">Event</th>
                        <th class="px-4 py-2 border text-center">Start Date</th>
                        <th class="px-4 py-2 border text-center">End Date</th>
                        <th class="px-2 py-2 border text-center">Start Time</th>
                        <th class="px-2 py-2 border text-center">End Time</th>
                        <th class="px-5 py-2 border text-center">Status</th>
                        <th class="px-4 py-2 border text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($allEvents as $index => $event)
                    @php
                    $now = \Carbon\Carbon::now('Asia/Jakarta');
                    $start = \Carbon\Carbon::parse($event->start_date . ' ' . ($event->start_time ?? '00:00:00'), 'UTC')->setTimezone('Asia/Jakarta');
                    $end = \Carbon\Carbon::parse($event->end_date . ' ' . ($event->end_time ?? '23:59:59'), 'UTC')->setTimezone('Asia/Jakarta');

                    if ($now->between($start, $end)) {
                    $status = '📢 Published';
                    $statusColor = 'bg-green-100 text-green-700';
                    } elseif ($now->lt($start)) {
                    $status = '⏳ Scheduled';
                    $statusColor = 'bg-yellow-100 text-yellow-700';
                    } else {
                    $status = '🎉 Done';
                    $statusColor = 'bg-gray-100 text-gray-600';
                    }
                    @endphp

                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="px-4 py-2 border text-center text-sm">
                            {{ ($allEvents->currentPage() - 1) * $allEvents->perPage() + $index + 1 }}
                        </td>
                        <td class="px-2 py-1 border text-black-500 text-sm">
                            {{ \Carbon\Carbon::parse($event->created_at)->setTimezone('Asia/Jakarta')->format('d M Y H:i') }}
                        </td>
                        <td class="px-2 py-1 border text-sm">{{ $event->event_name }}</td>
                        <td class="px-2 py-2 border text-sm">{{ \Carbon\Carbon::parse($event->start_date)->format('d M Y') }}</td>
                        <td class="px-2 py-2 border text-sm">{{ \Carbon\Carbon::parse($event->end_date)->format('d M Y') }}</td>
                        <td class="px-2 py-2 border text-sm text-center">
                            {{ \Carbon\Carbon::parse($event->start_time, 'UTC')->setTimezone('Asia/Jakarta')->format('H:i') }}
                        </td>
                        <td class="px-2 py-2 border text-sm text-center">
                            {{ \Carbon\Carbon::parse($event->end_time, 'UTC')->setTimezone('Asia/Jakarta')->format('H:i') }}
                        </td>
                        <td class="px-2 py-2 border text-sm text-center">
                            <span
                                class="inline-block px-2 py-0.5 rounded-full text-[10px] font-medium event-status"
                                data-start="{{ $start->toIso8601String() }}"
                                data-end="{{ $end->toIso8601String() }}">
                                {{ $status }}
                            </span>
                        </td>
                        <td class="px-4 py-2 border text-center space-x-1">
                            <button
                                type="button"
                                class="text-blue-500 hover:underline text-xs"
                                onclick="handleEditEventClick(this)"
                                data-id="{{ $event->id }}"
                                data-name="{{ $event->event_name }}"
                                data-start-date="{{ $event->start_date }}"
                                data-end-date="{{ $event->end_date }}"
                                data-start-time="{{ $event->start_time ? \Carbon\Carbon::parse($event->start_time, 'UTC')->setTimezone('Asia/Jakarta')->format('H:i') : '' }}"
                                data-end-time="{{ $event->end_time ? \Carbon\Carbon::parse($event->end_time, 'UTC')->setTimezone('Asia/Jakarta')->format('H:i') : '' }}">
                                ✏️ Edit
                            </button>

                            <form action="{{ route('admin.data.delete') }}" method="POST" class="inline-block">
                                @csrf
                                <input type="hidden" name="table" value="events_data">
                                <input type="hidden" name="id" value="{{ $event->id }}">
                                <button type="submit" class="text-red-500 hover:underline text-xs btn-delete">
                                    🗑️ Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-2 py-2 text-center text-gray-400 italic">Belum ada event.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination --}}
            <div class="mt-6 flex justify-center items-center text-center">
                {{ $allEvents->appends(request()->except('page'))->onEachSide(1)->links('pagination::tailwind') }}
            </div>

        </div>
    </div>
</div>

<!-- Modal Edit Event -->
<div id="editEventModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center transition duration-300 ease-out">
    <div class="bg-white p-6 rounded shadow-md w-full max-w-md">
        <h2 class="text-lg font-semibold mb-4">Edit Event</h2>
        <form method="POST" id="editEventForm" action="{{ route('admin.data.edit') }}">
            @csrf
            <input type="hidden" name="table" value="event">
            <input type="hidden" name="id" id="editEventId">

            <div class="mb-4">
                <label for="edit_event_name" class="block font-medium mb-1">Event Name</label>
                <input type="text" name="event_name" id="edit_event_name" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label for="edit_start_date" class="block font-medium mb-1">Start Date</label>
                <input type="date" name="start_date" id="edit_start_date" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label for="edit_end_date" class="block font-medium mb-1">End Date</label>
                <input type="date" name="end_date" id="edit_end_date" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label for="edit_start_time" class="block font-medium mb-1">Start Time</label>
                <input type="time" name="start_time" id="edit_start_time" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label for="edit_end_time" class="block font-medium mb-1">End Time</label>
                <input type="time" name="end_time" id="edit_end_time" class="w-full border rounded px-3 py-2" required>
            </div>

            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeEditEventModal()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 transition">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Save</button>
            </div>
        </form>
    </div>
</div>

@endsection