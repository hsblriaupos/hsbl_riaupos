@extends('admin.layouts.app')
@section('title', 'Add News - Administrator')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 rounded shadow mt-6">
  <h1 class="text-2xl font-bold mb-6">Add News</h1>

  @if ($errors->any())
    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
      <ul class="list-disc pl-5">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('admin.news.store') }}" enctype="multipart/form-data">
    @csrf

    {{-- Series --}}
    <div class="mb-4">
      <label for="series" class="block font-semibold mb-1">Series</label>
      <select id="series" name="series" required class="w-full border border-gray-300 rounded px-3 py-2">
        <option value="">-- Select Series --</option>
        @php
          $seriesOptions = [
            'Bengkalis Series',
            'Indragiri Hilir Series',
            'Indragiri Hulu Series',
            'Kampar Series',
            'Kepulauan Meranti Series',
            'Kuantan Singingi Series',
            'Pelalawan Series',
            'Rokan Hilir Series',
            'Rokan Hulu Series',
            'Siak Series',
            'Dumai Series',
            'Pekanbaru Series'
          ];
        @endphp
        @foreach($seriesOptions as $series)
          <option value="{{ $series }}" {{ old('series') == $series ? 'selected' : '' }}>{{ $series }}</option>
        @endforeach
      </select>
      @error('series') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Title --}}
    <div class="mb-4">
      <label for="title" class="block font-semibold mb-1">Title</label>
      <input id="title" name="title" type="text"
             value="{{ old('title') }}"
             class="w-full border border-gray-300 rounded px-3 py-2"
             placeholder="Enter news title" required>
      @error('title') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Posted By --}}
    <div class="mb-4">
      <label for="posted_by" class="block font-semibold mb-1">Posted By</label>
      <input id="posted_by" name="posted_by" type="text"
             value="{{ old('posted_by') }}"
             class="w-full border border-gray-300 rounded px-3 py-2"
             placeholder="Your name or username" required>
      @error('posted_by') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Image --}}
    <div class="mb-4">
      <label for="image" class="block font-semibold mb-1">Image (optional)</label>
      <input id="image" name="image" type="file" accept=".jpg,.jpeg,.png"
             class="w-full text-sm">
      <p class="text-sm text-gray-500 mt-1">Max 1MB, JPG/JPEG/PNG, 16:9 recommended</p>
      @error('image') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Content --}}
    <div class="mb-4">
      <label for="content" class="block font-semibold mb-1">Content</label>
      <textarea id="content" name="content" rows="6"
                class="w-full border border-gray-300 rounded px-3 py-2"
                placeholder="Write the news content here..." required>{{ old('content') }}</textarea>
      @error('content') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Status --}}
    <div class="mb-6">
      <label for="status" class="block font-semibold mb-1">Status</label>
      <select id="status" name="status" required class="w-full border border-gray-300 rounded px-3 py-2">
        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
        <option value="view" {{ old('status') == 'view' ? 'selected' : '' }}>Published</option>
        <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Archived</option>
      </select>
      @error('status') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Buttons --}}
    <div class="flex items-center justify-between">
      <a href="{{ route('admin.news.index') }}"
         class="inline-block px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 transition">
        ← Back to List
      </a>
      <button type="submit"
              class="px-5 py-2 bg-blue-600 text-white rounded hover:bg-[#2f605e] transition">
        Upload
      </button>
    </div>
  </form>
</div>
@endsection
