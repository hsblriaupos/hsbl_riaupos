@extends('admin.layouts.app')
@section('title','Videos - Administrator')

@section('content')

{{-- Toast Notification with SweetAlert2 --}}
@if(session('success'))
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: '{{ session('success') }}',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
      });
    });
  </script>
@endif

<section class="bg-white rounded-lg shadow p-6 max-w-full mx-auto">
  <div class="flex justify-between items-center mb-4">
    <h3 class="font-semibold text-lg">Videos List</h3>
    <a href="{{ route('admin.videos.create') }}" class="btn btn-primary">Add Video</a>
  </div>

  {{-- Search & Filter --}}
  <form method="GET" class="mb-4 flex justify-end items-center space-x-2">
    <input
      type="text"
      name="search"
      value="{{ request('search') }}"
      placeholder="Search title..."
      class="input input-bordered px-3 py-2 w-64 placeholder-gray-500"
    />
    <select
      name="type"
      class="select select-bordered px-3 py-2"
    >
      <option value="">All Types</option>
      <option value="video" {{ request('type')=='video'?'selected':'' }}>Video</option>
      <option value="live"  {{ request('type')=='live' ?'selected':'' }}>Live</option>
    </select>
    <button type="submit" class="btn btn-primary px-4 py-2">
      Filter
    </button>
  </form>

  {{-- Table --}}
  <div class="overflow-x-auto">
    <table class="table table-zebra w-full">
      <thead>
        <tr>
          <th>No</th>
          <th>Title</th>
          <th>Thumbnail</th>
          <th>Description</th>
          <th>Created At</th>
          <th>Updated At</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
      @foreach($videos as $idx => $video)
        <tr>
          <td>{{ $videos->firstItem() + $idx }}</td>
          <td>{{ $video->title }}</td>
          <td>
            @if($video->thumbnail)
              <img src="{{ asset($video->thumbnail) }}"
                   alt="thumb" class="w-16 h-10 object-cover rounded">
            @endif
          </td>
          <td>{{ Str::limit($video->description, 50) }}</td>
          <td>{{ $video->created_at->format('d M Y H:i') }}</td>
          <td>{{ $video->updated_at->format('d M Y H:i') }}</td>
          <td>
            <span class="badge {{ $video->status=='view'?'badge-success':'badge-warning' }}">
              {{ ucfirst($video->status) }}
            </span>
          </td>
          <td class="space-x-1">
            <a href="{{ route('admin.videos.edit', $video) }}" class="btn btn-sm btn-info">Edit</a>
            <form action="{{ route('admin.videos.destroy', $video) }}" method="POST" class="inline">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-error"
                      onclick="return confirm('Yakin hapus video ini?')">Delete</button>
            </form>
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>

  {{-- Pagination --}}
  <div class="mt-4">
    {{ $videos->withQueryString()->links() }}
  </div>
</section>

@endsection
