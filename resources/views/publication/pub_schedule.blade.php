@extends('admin.layouts.app')
@section('title', 'Master Data - Administrator')
@section('content')

@php $activeTab = 'schedule'; @endphp
@include('partials.tabs_pub', compact('activeTab'))

@push('scripts')
@include('partials.sweetalert')
@endpush

@push('styles')
<link href="{{ asset('css/data.css') }}" rel="stylesheet" />
<style>
    button[disabled] {
        opacity: 0.5;
        cursor: not-allowed;
    }
</style>
@endpush

<div class="py-4">
    <h2 class="text-2xl font-bold mb-6">Match Data</h2>

    {{-- Form Tambah Match --}}
    <div class="bg-white p-6 rounded shadow mb-2">
        <div class="flex justify-between items-center mb-3">
            <h3 class="font-semibold text-md">Add Match</h3>
        </div>
        <form action="{{ route('admin.match.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="upload_date" class="block text-sm font-medium mb-1">Tanggal Upload</label>
                    <input
                        type="date"
                        id="upload_date"
                        name="upload_date"
                        class="w-full border rounded p-2"
                        required
                        value="{{ date('Y-m-d') }}"
                        readonly>
                </div>

                <div>
                    <label for="main_title" class="block text-sm font-medium mb-1">Judul Besar</label>
                    <input type="text" id="main_title" name="main_title" class="w-full border rounded p-2" required>
                </div>

                <div>
                    <label for="layout_image" class="block text-sm font-medium mb-1">Layout Gambar</label>
                    <input type="file" id="layout_image" name="layout_image" accept="image/*" class="w-full border rounded p-2">
                </div>

                <div>
                    <label for="series_name" class="block text-sm font-medium mb-1">Series</label>
                    <select id="series_name" name="series_name" class="w-full border rounded p-2" required>
                        <option value="">-- Pilih Series --</option>
                        @foreach ($allSeries as $series)
                            <option value="{{ $series }}">{{ $series }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium mb-1">Status</label>
                    <select id="status" name="status" class="w-full border rounded p-2" required>
                        <option value="draft">Draft</option>
                        <option value="publish">Publish</option>
                    </select>
                </div>
            </div>
            <div class="mt-6">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded shadow">Submit</button>
            </div>
        </form>
    </div>

    {{-- List Match --}}
    <div class="bg-white p-6 rounded shadow">
        <div class="flex justify-between items-center mb-2">
            <h3 class="text-lg font-semibold">Match List</h3>
        </div>

        {{-- Filter & Search --}}
        <form method="GET" action="{{ url()->current() }}" class="flex flex-wrap justify-between items-center gap-4 mb-4">
            <div class="flex flex-wrap items-center gap-3">
                {{-- Show per page --}}
                <div class="flex items-center gap-2">
                    <label for="per_page" class="text-sm">Show:</label>
                    <select name="per_page" id="per_page" onchange="this.form.submit()" class="border rounded px-2 py-1 text-sm">
                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    </select>
                </div>

                {{-- Filter Year --}}
                <div class="flex items-center gap-2">
                    <select name="filter_year" id="filter_year" class="border rounded px-2 py-1 text-sm" onchange="this.form.submit()">
                        <option value="">Year</option>
                        @foreach($availableYears as $year)
                            <option value="{{ $year }}" {{ request('filter_year') == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Series --}}
                <div class="flex items-center gap-2">
                    <select id="series_name" name="series_name" class="border rounded px-2 py-1 text-sm w-40" onchange="this.form.submit()">
                        <option value="">Series</option>
                        @foreach ($allSeries as $series)
                            <option value="{{ $series }}" {{ request('series_name') == $series ? 'selected' : '' }}>
                                {{ $series }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Status --}}
                <div class="flex items-center gap-2">
                    <select id="status" name="status" class="border rounded px-2 py-1 text-sm w-40" onchange="this.form.submit()">
                        <option value="">Status</option>
                        <option value="draft"   {{ request('status') == 'draft'   ? 'selected' : '' }}>Draft</option>
                        <option value="publish" {{ request('status') == 'publish' ? 'selected' : '' }}>Publish</option>
                        <option value="done"    {{ request('status') == 'done'    ? 'selected' : '' }}>Done</option>
                    </select>
                </div>

                {{-- Reset Filter --}}
                <a href="{{ request()->url() }}" class="bg-gray-500 text-white px-4 py-2 rounded text-sm hover:bg-gray-600 transition">Reset</a>
            </div>

            {{-- Search --}}
            <div>
                <input
                    type="text"
                    name="search"
                    placeholder="Cari judul..."
                    value="{{ request('search') }}"
                    class="border rounded px-3 py-2 text-sm w-56" />
            </div>
        </form>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left border">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 border text-center">No</th>
                        <th class="px-4 py-2 border text-center">Tanggal</th>
                        <th class="px-2 py-2 border text-center">Judul</th>
                        <th class="px-4 py-2 border text-center">Layout</th>
                        <th class="px-4 py-2 border text-center">Status</th>
                        <th class="px-4 py-2 border text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($matches as $index => $match)
                        <tr>
                            <td class="px-4 py-1 border text-center text-sm">
                                {{ ($matches->currentPage() - 1) * $matches->perPage() + $index + 1 }}
                            </td>
                            <td class="px-4 py-1 border text-center text-sm">
                                {{ \Carbon\Carbon::parse($match->upload_date)->format('d M Y') }}
                            </td>
                            <td class="px-2 py-1 border text-sm">{{ $match->main_title }}</td>
                            <td class="px-4 py-1 border text-center text-sm">
                                @if ($match->layout_image)
                                    <img
                                        src="{{ asset($match->layout_image) }}"
                                        onclick="showImage(this.src)"
                                        class="w-20 h-20 object-cover cursor-pointer mx-auto rounded"
                                        alt="Match Layout" />
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-1 border text-center capitalize">{{ $match->status }}</td>
<td class="px-4 py-1 border text-center space-x-2">
    {{-- Tombol Edit --}}
    @if (strtolower($match->status) !== 'done')
        <button
            type="button"
            class="text-blue-500 text-xs"
            data-id="{{ $match->id }}"
            data-date="{{ $match->upload_date }}"
            data-title="{{ $match->main_title }}"
            data-status="{{ $match->status }}"
            onclick="handleEditButtonClick(this)">
            ✏️ Edit
        </button>
    @else
        <button disabled class="text-gray-400 text-xs italic cursor-not-allowed" title="Jadwal sudah selesai">✏️ Edit</button>
    @endif

    {{-- Tombol Hapus --}}
    <form action="" method="POST" style="display: inline;">
        @csrf
        <input type="hidden" name="table" value="match_data">
        <input type="hidden" name="id" value="{{ $match->id }}">
        <button type="submit" class="text-red-500 text-xs btn-delete">🗑️ Hapus</button>
    </form>

    {{-- Tombol Status Publish / Done --}}
    @if ($match->status === 'draft')
        <form action="{{ route('admin.match.publish', $match->id) }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="text-green-500 text-xs">🚀 Publish</button>
        </form>
    @elseif ($match->status === 'publish')
        <form action="{{ route('admin.match.unpublish', $match->id) }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="text-yellow-500 text-xs">🔙 Unpublish</button>
        </form>
        <form action="{{ route('admin.match.done', ['id' => $match->id]) }}" method="POST" class="form-done" style="display:inline;">
            @csrf
            <button type="submit" class="btn-done text-blue-500 text-xs ml-2">✅ Done</button>
        </form>
    @elseif ($match->status === 'done')
        <span class="text-gray-400 text-xs">✔️ Selesai</span>
    @endif
</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-4 text-center text-gray-400">Belum ada data match.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-6 flex justify-center items-center">
                {{ $matches->onEachSide(1)->links('pagination::tailwind') }}
            </div>
        </div>
    </div>
</div>

{{-- Image Modal --}}
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-transparent border-0 relative">
            <div class="modal-body text-center p-0">
                <img
                    id="modalImage"
                    src=""
                    class="rounded max-w-[500px] max-h-[500px] w-full h-auto mx-auto"
                    alt="Match Image">
            </div>
            <button
                type="button"
                class="absolute top-4 right-4 bg-black/70 hover:bg-black/90 text-white rounded-full p-2 z-50"
                data-bs-dismiss="modal"
                aria-label="Close">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>
</div>


{{-- Modal Edit --}}
<div class="modal fade" id="editMatchModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('admin.data.edit') }}" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf

            {{-- ✅ FIX: tambahkan ini --}}
            <input type="hidden" name="table" value="match_data">
            <input type="hidden" name="id" id="editMatchId">

            <div class="modal-header">
                <h5 class="modal-title">Edit Match</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body space-y-3">
                <div>
                    <label>Tanggal</label>
                    <input type="date" class="form-control" name="upload_date" id="editUploadDate" required>
                </div>
                <div>
                    <label>Judul</label>
                    <input type="text" class="form-control" name="main_title" id="editMainTitle" required>
                </div>
                <div>
                    <label>Status</label>
                    <select class="form-control" name="status" id="editStatus" required>
                        <option value="draft">Draft</option>
                        <option value="publish">Publish</option>
                    </select>
                </div>
                <div>
                    <label>Layout Image (Opsional)</label>
                    <input type="file" class="form-control" name="layout_image">
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Update</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>


@push('scripts')
<script>
    function showImage(src) {
        document.getElementById('modalImage').src = src;
        new bootstrap.Modal(document.getElementById('imageModal')).show();
    }

    function openEditMatchModal(id, date, title, status) {
        document.getElementById('editMatchId').value = id;
        document.getElementById('editUploadDate').value = date;
        document.getElementById('editMainTitle').value = title;
        document.getElementById('editStatus').value = status;
        new bootstrap.Modal(document.getElementById('editMatchModal')).show();
    }

    function handleEditButtonClick(button) {
        const id = button.getAttribute('data-id');
        const date = button.getAttribute('data-date');
        const title = button.getAttribute('data-title');
        const status = button.getAttribute('data-status');

        openEditMatchModal(id, date, title, status);
    }
    document.querySelector('input[name="search"]').addEventListener('input', function() {
        if (this.value === '') {
            // Kalau input search dikosongkan, reload halaman tanpa query search
            window.location.href = window.location.pathname;
        }
    });
</script>
@endpush

@endsection
