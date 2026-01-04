@extends('admin.layouts.app')
@section('title', 'Edit News - Administrator')

@section('content')

@if(session('success'))
  <div class="max-w-4xl mx-auto mt-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
    {{ session('success') }}
  </div>
@endif

<div class="max-w-3xl mx-auto bg-white p-6 rounded shadow mt-6">
  <h1 class="text-2xl font-bold mb-6">Edit News</h1>

  <form method="POST" action="{{ route('admin.news.update', $news->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    {{-- Series --}}
    <div class="mb-4">
      <label class="block font-semibold mb-1">Series</label>
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
      <select name="series" required class="w-full border border-gray-300 rounded px-3 py-2">
        @foreach($seriesOptions as $series)
          <option value="{{ $series }}" {{ old('series', $news->series) == $series ? 'selected' : '' }}>
            {{ $series }}
          </option>
        @endforeach
      </select>
      @error('series') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Title --}}
    <div class="mb-4">
      <label class="block font-semibold mb-1">Title</label>
      <input type="text" name="title"
             value="{{ old('title', $news->title) }}"
             class="w-full border border-gray-300 rounded px-3 py-2"
             placeholder="Enter news title" required>
      @error('title') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Posted By --}}
    <div class="mb-4">
      <label class="block font-semibold mb-1">Posted By</label>
      <input type="text" name="posted_by"
             value="{{ old('posted_by', $news->posted_by) }}"
             class="w-full border border-gray-300 rounded px-3 py-2"
             placeholder="Your name or username" required>
      @error('posted_by') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Current Image --}}
    <div class="mb-4">
      <label class="block font-semibold mb-1">Current Image</label>
      @if($news->image)
        <img src="{{ asset($news->image) }}" alt="Current image"
             class="w-40 h-auto object-cover rounded mb-2 border">
      @else
        <p class="text-sm text-gray-500 italic">No image uploaded.</p>
      @endif
    </div>

    {{-- Change Image --}}
    <div class="mb-4">
      <label class="block font-semibold mb-1">Change Image (optional)</label>
      <input type="file" name="image" accept=".jpg,.jpeg,.png"
             class="w-full text-sm">
      <p class="text-sm text-gray-500 mt-1">Max 1MB, JPG/JPEG/PNG, 16:9 recommended</p>
      @error('image') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Content --}}
    <div class="mb-4">
      <label class="block font-semibold mb-1">Content</label>
      <textarea name="content" rows="6"
                class="w-full border border-gray-300 rounded px-3 py-2"
                placeholder="Write the news content here..." required>{{ old('content', $news->content) }}</textarea>
      @error('content') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Status --}}
    <div class="mb-6">
      <label class="block font-semibold mb-1">Status</label>
      <select name="status" required class="w-full border border-gray-300 rounded px-3 py-2">
        <option value="draft" {{ old('status', $news->status) == 'draft' ? 'selected' : '' }}>Draft</option>
        <option value="view" {{ old('status', $news->status) == 'view' ? 'selected' : '' }}>Published</option>
        <option value="archived" {{ old('status', $news->status) == 'archived' ? 'selected' : '' }}>Archived</option>
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
        Update News
      </button>
    </div>

  </form>
</div>
@endsection
