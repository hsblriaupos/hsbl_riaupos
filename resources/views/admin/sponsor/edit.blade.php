@extends('admin.layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-10 p-6 bg-white rounded shadow">
    <h2 class="text-xl font-bold mb-4">Edit Sponsor</h2>

    <form action="{{ route('admin.sponsor.update', $sponsor->id) }}"
          method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')

        <!-- Sponsor Name -->
        <div class="mb-4">
            <label class="block font-semibold mb-1">Sponsor Name</label>
            <input type="text" name="sponsor_name"
                   value="{{ old('sponsor_name', $sponsor->sponsor_name) }}"
                   class="w-full border rounded p-2 @error('sponsor_name') border-red-500 @enderror">
            @error('sponsor_name')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
        </div>

        <!-- Category -->
        <div class="mb-4">
            <label class="block font-semibold mb-1">Category</label>
            <select name="category" class="w-full border rounded p-2 @error('category') border-red-500 @enderror">
                @foreach(['Presented by','Official Partners','Official Suppliers','Supporting Partners','Managed by'] as $cat)
                    <option value="{{ $cat }}"
                        {{ $sponsor->category == $cat ? 'selected' : '' }}>
                        {{ $cat }}
                    </option>
                @endforeach
            </select>
            @error('category')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
        </div>

        <!-- Logo -->
        <div class="mb-4">
            <label class="block font-semibold mb-1">Logo (Opsional)</label>
            <input type="file" name="logo"
                   class="w-full @error('logo') border-red-500 @enderror">
            @error('logo')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
            @if($sponsor->logo)
                <img src="{{ asset('images/sponsors/' . $sponsor->logo) }}"
                     class="h-20 mt-2" alt="Logo lama">
            @endif
        </div>

        <!-- Sponsors Web -->
        <div class="mb-4">
            <label class="block font-semibold mb-1">Sponsor Web</label>
            <input type="url" name="sponsors_web"
                   value="{{ old('sponsors_web', $sponsor->sponsors_web) }}"
                   class="w-full border rounded p-2 @error('sponsors_web') border-red-500 @enderror">
            @error('sponsors_web')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
        </div>

        <!-- Buttons -->
        <div class="flex items-center">
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                Simpan Perubahan
            </button>
            <a href="{{ route('admin.sponsor.index') }}"
               class="ml-4 text-gray-600 hover:underline">Batal</a>
        </div>
    </form>
</div>
@endsection
