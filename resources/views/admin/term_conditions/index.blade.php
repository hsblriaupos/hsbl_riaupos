@extends('admin.layouts.app')
@section('title', 'Term and Conditions')
@section('content')

<div class="py-4">
  <h2 class="text-2xl font-bold mb-6">Term and Conditions</h2>

  {{-- Form Upload --}}
  <div class="bg-white p-6 rounded shadow mb-6">
    <h3 class="font-semibold mb-4">Upload Syarat & Ketentuan</h3>
    <form action="{{ route('admin.term_conditions.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label for="event_name" class="block text-sm font-medium mb-1">Nama Event</label>
          <input type="text" name="event_name" id="event_name" class="w-full border rounded p-2" required>
        </div>
        <div>
          <label for="year" class="block text-sm font-medium mb-1">Tahun</label>
          <input type="number" name="year" id="year" value="{{ date('Y') }}" class="w-full border rounded p-2" required>
        </div>
        <div>
          <label for="file" class="block text-sm font-medium mb-1">Upload Dokumen (PDF)</label>
          <input type="file" name="file" id="file" accept="application/pdf" class="w-full border rounded p-2" required>
        </div>
      </div>
      <div class="mt-4">
        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded">Upload</button>
      </div>
    </form>
  </div>

  {{-- Tabel Daftar Dokumen --}}
  <form action="{{ route('admin.term_conditions.destroySelected') }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data yang dipilih?')">
    @csrf
    @method('DELETE')
    <div class="bg-white p-6 rounded shadow">
      <div class="flex justify-between items-center mb-4">
        <h3 class="font-semibold">Daftar Dokumen S&K</h3>
        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-1 rounded text-sm">
          Hapus yang dipilih
        </button>
      </div>
      <table class="min-w-full text-sm text-left border">
        <thead class="bg-gray-100">
          <tr>
            <th class="px-3 py-2 border text-center">
              <input type="checkbox" id="check-all">
            </th>
            <th class="px-3 py-2 border">No</th>
            <th class="px-3 py-2 border">Event</th>
            <th class="px-3 py-2 border">Tahun</th>
            <th class="px-3 py-2 border">File</th>
          </tr>
        </thead>
        <tbody>
          @forelse($terms as $term)
          <tr>
            <td class="px-3 py-2 border text-center">
              <input type="checkbox" name="selected_ids[]" value="{{ $term->id }}">
            </td>
            <td class="px-3 py-2 border">{{ $loop->iteration }}</td>
            <td class="px-3 py-2 border">{{ $term->event_name }}</td>
            <td class="px-3 py-2 border">{{ $term->year }}</td>
            <td class="px-3 py-2 border">
              <a href="{{ Storage::url($term->file_path) }}" target="_blank" class="text-blue-600 underline">Download</a>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6" class="px-4 py-2 text-center">Belum ada dokumen.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </form>
</div>

{{-- JS Check All --}}
<script>
  document.getElementById('check-all').addEventListener('click', function () {
    const checkboxes = document.querySelectorAll('input[name="selected_ids[]"]');
    checkboxes.forEach(cb => cb.checked = this.checked);
  });
</script>
@endsection
