@extends('admin.layouts.app')
@section('title','News - Administrator')

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
    <h3 class="font-semibold text-lg">News List</h3>
    <a href="{{ route('admin.news.create') }}"
       class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
      Add News
    </a>
  </div>

  <div class="flex flex-col md:flex-row md:justify-end md:items-center gap-3 mb-4">
    <form method="GET" action="{{ route('admin.news.index') }}" class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
      <input name="search" type="text" value="{{ request('search') }}"
             class="border border-gray-300 rounded-md px-3 py-2 w-full sm:w-56 focus:outline-none focus:ring-2 focus:ring-blue-500"
             placeholder="Cari Judul...">

      <select name="series"
              class="border border-gray-300 rounded-md px-3 py-2 w-full sm:w-56 focus:outline-none focus:ring-2 focus:ring-blue-500">
        <option value="">Semua Series</option>
        @foreach($seriesList as $series)
          <option value="{{ $series }}" @selected(request('series')==$series)>{{ $series }}</option>
        @endforeach
      </select>

      <button type="submit"
              class="bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-900 transition">
        Filter
      </button>
    </form>
  </div>

  <div class="overflow-x-auto">
    <table class="w-full text-xs sm:text-sm text-left text-gray-700 border border-gray-200 rounded-md table-auto">
      <thead class="bg-gray-100 text-gray-700 font-semibold">
        <tr>
          <th class="p-2 border-r border-gray-200 w-6"><input type="checkbox"/></th>
          <th class="p-2 border-r border-gray-200 w-10">No.</th>
          <th class="p-2 border-r border-gray-200 w-20">Series</th>
          <th class="p-2 border-r border-gray-200 min-w-[150px]">Title</th>
          <th class="p-2 border-r border-gray-200 w-24">Posted By</th>
          <th class="p-2 border-r border-gray-200 w-24">Image</th>
          <th class="p-2 border-r border-gray-200 max-w-[200px]">Content</th>
          <th class="p-2 border-r border-gray-200 w-28">Created At</th>
          <th class="p-2 border-r border-gray-200 w-28">Updated At</th>
          <th class="p-2 border-r border-gray-200 w-16">Status</th>
          <th class="p-2 w-24 text-center">Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($news as $index => $item)
          <tr class="border-t border-gray-200 hover:bg-gray-50">
            <td class="p-2 border-r border-gray-200 text-center">
              <input type="checkbox" name="selected[]" value="{{ $item->id }}"/>
            </td>
            {{-- Nomor urut --}}
            <td class="p-2 border-r border-gray-200 font-medium">
              {{ $news->firstItem() + $index }}
            </td>
            <td class="p-2 border-r border-gray-200">{{ $item->series }}</td>
            <td class="p-2 border-r border-gray-200 font-semibold">{{ Str::limit($item->title, 50) }}</td>
            <td class="p-2 border-r border-gray-200">{{ $item->posted_by }}</td>
            <td class="p-2 border-r border-gray-200">
              @if($item->image)
                <img src="{{ asset($item->image) }}"
                     alt="{{ $item->title }}"
                     class="w-16 h-10 object-cover rounded" />
              @endif
            </td>
            <td class="p-2 border-r border-gray-200 truncate-content" title="{{ $item->content }}">
              {{ Str::limit(strip_tags($item->content), 50) }}
            </td>
            <td class="p-2 border-r border-gray-200">{{ $item->created_at->format('Y-m-d') }}</td>
            <td class="p-2 border-r border-gray-200">{{ $item->updated_at->format('Y-m-d') }}</td>
            <td class="p-2 border-r border-gray-200">
              <span class="inline-block px-2 py-1 text-xs font-semibold rounded
                {{ $item->status === 'draft' ? 'bg-yellow-100 text-yellow-800' :
                   ($item->status === 'archived' ? 'bg-gray-200 text-gray-600' :
                   'bg-green-100 text-green-800') }}">
                {{ $item->status }}
              </span>
            </td>
            <td class="p-2 text-center space-x-4">
              {{-- EDIT --}}
              <a href="{{ route('admin.news.edit', $item->id) }}"
                 class="text-blue-600 hover:underline"
                 title="Edit">
                Edit
              </a>
              {{-- DELETE --}}
              <form action="{{ route('admin.news.destroy', $item->id) }}"
                    method="POST"
                    class="inline"
                    onsubmit="return confirm('Yakin ingin menghapus berita ini?');">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="text-red-600 hover:underline"
                        title="Delete">
                  Delete
                </button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div class="mt-4">
    {{ $news->withQueryString()->links() }}
  </div>
</section>

@endsection
