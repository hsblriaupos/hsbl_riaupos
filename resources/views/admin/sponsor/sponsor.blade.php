@extends('admin.layouts.app')
@section('title','Sponsors - Administrator')
@section('content')

@if(session('success'))
  <div class="max-w-4xl mx-auto mt-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
    {{ session('success') }}
  </div>
@endif

<div class="max-w-6xl mx-auto mt-10 space-y-8">

  {{-- 1️⃣ Form Tambah --}}
  <div class="bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Add Sponsor</h2>
    @if($errors->any())
      <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
        <ul class="list-disc pl-5">
          @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
          @endforeach
        </ul>
      </div>
    @endif
    <form action="{{ route('admin.sponsor.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block mb-1">Sponsor Name</label>
          <input type="text" name="sponsor_name" value="{{ old('sponsor_name') }}"
                 class="w-full border rounded px-3 py-2" required>
        </div>
        <div>
          <label class="block mb-1">Category</label>
          <select name="category" class="w-full border rounded px-3 py-2" required>
            <option disabled selected>-- Pilih Kategori --</option>
            @foreach($categories as $cat)
              <option value="{{ $cat }}" {{ old('category')==$cat?'selected':'' }}>{{ $cat }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="block mb-1">Logo</label>
          <input type="file" name="logo" accept="image/*" class="w-full">
        </div>
        <div>
          <label class="block mb-1">Sponsor Web</label>
          <input type="url" name="sponsors_web" value="{{ old('sponsors_web') }}"
                 class="w-full border rounded px-3 py-2">
        </div>
      </div>
      <button type="submit" class="mt-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
        Tambah Sponsor
      </button>
    </form>
  </div>

  {{-- 2️⃣ Daftar + Filter + Bulk Delete --}}
  <div class="bg-white p-6 rounded shadow overflow-x-auto">
    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-4 space-y-4 md:space-y-0">
      <h2 class="text-xl font-bold">Sponsors List</h2>
      <form method="GET" action="{{ route('admin.sponsor.sponsor') }}" class="flex gap-2">
        <input type="text" name="search" placeholder="Cari Nama..." value="{{ request('search') }}"
               class="border rounded px-3 py-2">
        <select name="category" class="border rounded px-3 py-2">
          <option value="">Semua Kategori</option>
          @foreach($categories as $cat)
            <option value="{{ $cat }}" {{ request('category')==$cat?'selected':'' }}>{{ $cat }}</option>
          @endforeach
        </select>
        <button type="submit" class="bg-gray-700 text-white px-3 py-2 rounded">Filter</button>
      </form>
    </div>

    <form method="POST" action="{{ route('admin.sponsor.destroySelected') }}">
      @csrf
      <table class="w-full table-auto border text-sm">
        <thead class="bg-gray-100 text-left">
          <tr>
            <th class="p-2"><input type="checkbox" id="selectAll"></th>
            <th class="p-2">ID</th>
            <th class="p-2">Name</th>
            <th class="p-2">Category</th>
            <th class="p-2">Logo</th>
            <th class="p-2">Web</th>
            <th class="p-2">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($sponsors as $sp)
            <tr class="border-t">
              <td class="p-2"><input type="checkbox" name="ids[]" value="{{ $sp->id }}"></td>
              <td class="p-2">{{ $sp->id }}</td>
              <td class="p-2">{{ $sp->sponsor_name }}</td>
              <td class="p-2">{{ $sp->category }}</td>
              <td class="p-2">
                @if($sp->logo)
                  <img src="{{ asset('uploads/sponsors/'.$sp->logo) }}" class="h-10" alt="">
                @endif
              </td>
              <td class="p-2">
                @if($sp->sponsors_web)
                  <a href="{{ $sp->sponsors_web }}" target="_blank" class="underline text-blue-600">Link</a>
                @endif
              </td>
              <td class="p-2 flex space-x-2">
                <button type="button" onclick="openEditModal({{ $sp }})" class="underline text-green-600">
                  Edit
                </button>
                <button type="button"
                        onclick="confirmDelete('{{ route('admin.sponsor.destroy',$sp->id) }}')"
                        class="underline text-red-600">
                  Delete
                </button>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="text-center py-4">Belum ada sponsor.</td>
            </tr>
          @endforelse
        </tbody>
      </table>

      @if($sponsors->count())
        <button type="submit"
                onclick="return confirm('Delete selected sponsors?')"
                class="mt-4 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
          Delete Selected
        </button>
      @endif
    </form>
  </div>
</div>

{{-- 3️⃣ Modal Edit --}}
<div id="editModal"
     class="fixed inset-0 flex bg-black bg-opacity-50 hidden items-center justify-center">
  <div class="bg-white p-6 rounded shadow-lg w-96">
    <h3 class="text-lg font-bold mb-4">Edit Sponsor</h3>
    <form id="editForm" method="POST" enctype="multipart/form-data">
      @csrf @method('PUT')
      <label class="block mb-1">Sponsor Name</label>
      <input type="text" name="sponsor_name" id="edit_name" class="w-full mb-2 border rounded px-2 py-1" required>
      <label class="block mb-1">Category</label>
      <select name="category" id="edit_cat" class="w-full mb-2 border rounded px-2 py-1" required>
        @foreach($categories as $cat)
          <option value="{{ $cat }}">{{ $cat }}</option>
        @endforeach
      </select>
      <label class="block mb-1">Logo (leave blank to keep)</label>
      <input type="file" name="logo" class="w-full mb-2">
      <label class="block mb-1">Sponsor Web</label>
      <input type="url" name="sponsors_web" id="edit_web" class="w-full mb-4 border rounded px-2 py-1">
      <div class="flex justify-end space-x-2">
        <button type="button" onclick="hideModal('editModal')" class="px-4 py-2">Cancel</button>
        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Save</button>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
  // select all
  document.getElementById('selectAll').onclick = function(){
    document.querySelectorAll('input[name="ids[]"]').forEach(cb=>cb.checked=this.checked);
  };

  // show/hide modal
  function showModal(id){ document.getElementById(id).classList.remove('hidden'); }
  function hideModal(id){ document.getElementById(id).classList.add('hidden'); }

  // delete single via fetch
  function confirmDelete(url){
    if(confirm('Delete this sponsor?')){
      fetch(url, {
        method:'DELETE',
        headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}
      }).then(_=>location.reload());
    }
  }

  // populate & open edit modal
  function openEditModal(sp){
    document.getElementById('editForm').action = `/admin/sponsor/${sp.id}`;
    document.getElementById('edit_name').value = sp.sponsor_name;
    document.getElementById('edit_cat').value  = sp.category;
    document.getElementById('edit_web').value  = sp.sponsors_web || '';
    showModal('editModal');
  }
</script>
@endpush

@endsection
