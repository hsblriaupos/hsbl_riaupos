@extends('admin.layouts.app')
@section('title','Videos - Administrator')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 rounded shadow mt-6">
  <h1 class="text-xl font-semibold mb-6">
    {{ isset($video) ? 'Edit Video' : 'Add Video' }}
  </h1>

  <form
    action="{{ isset($video) ? route('admin.videos.update', $video) : route('admin.videos.store') }}"
    method="POST"
    enctype="multipart/form-data"
  >
    @csrf
    @if(isset($video))
      @method('PUT')
    @endif

    {{-- Title --}}
    <div class="mb-4">
      <label for="title" class="block font-semibold mb-1">Title</label>
      <input
        id="title"
        name="title"
        type="text"
        value="{{ old('title', $video->title ?? '') }}"
        class="w-full border border-gray-300 rounded px-3 py-2"
        placeholder="Enter video title"
        required
      >
      @error('title')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
      @enderror
    </div>

    {{-- YouTube URL --}}
    <div class="mb-4">
      <label for="youtube_link" class="block font-semibold mb-1">YouTube URL</label>
      <input
        id="youtube_link"
        name="youtube_link"
        type="url"
        value="{{ old('youtube_link', $video->youtube_link ?? '') }}"
        class="w-full border border-gray-300 rounded px-3 py-2"
        placeholder="https://www.youtube.com/watch?v=..."
        required
      >
      @error('youtube_link')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
      @enderror
    </div>

    {{-- Thumbnail --}}
    <div class="mb-4">
      <label for="thumbnail" class="block font-semibold mb-1">Thumbnail</label>
      <input
        id="thumbnail"
        name="thumbnail"
        type="file"
        accept="image/*"
        class="w-full border border-gray-300 rounded px-3 py-2"
      >
      @if(isset($video) && $video->thumbnail)
        <img
          src="{{ asset($video->thumbnail) }}"
          class="w-32 h-20 object-cover mt-2 rounded"
          alt="Current thumbnail"
        >
      @endif
      @error('thumbnail')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
      @enderror
    </div>

    {{-- Description --}}
    <div class="mb-4">
      <label for="description" class="block font-semibold mb-1">Description</label>
      <textarea
        id="description"
        name="description"
        rows="4"
        class="w-full border border-gray-300 rounded px-3 py-2"
        placeholder="Enter video description"
      >{{ old('description', $video->description ?? '') }}</textarea>
      @error('description')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
      @enderror
    </div>

    {{-- Type --}}
    <div class="mb-4">
      <label for="type" class="block font-semibold mb-1">Type</label>
      <select
        id="type"
        name="type"
        class="w-full border border-gray-300 rounded px-3 py-2"
        required
      >
        <option value="">-- Select type --</option>
        <option value="video" {{ old('type', $video->type ?? '')=='video' ? 'selected' : '' }}>Video</option>
        <option value="live"  {{ old('type', $video->type ?? '')=='live'  ? 'selected' : '' }}>Live</option>
      </select>
      @error('type')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
      @enderror
    </div>

    {{-- Status --}}
    <div class="mb-6">
      <label for="status" class="block font-semibold mb-1">Status</label>
      <select
        id="status"
        name="status"
        class="w-full border border-gray-300 rounded px-3 py-2"
        required
      >
        <option value="">-- Select status --</option>
        <option value="view"  {{ old('status', $video->status ?? '')=='view'  ? 'selected' : '' }}>View</option>
        <option value="draft" {{ old('status', $video->status ?? '')=='draft' ? 'selected' : '' }}>Draft</option>
      </select>
      @error('status')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
      @enderror
    </div>

    {{-- Submit Button --}}
    <button
      type="submit"
      class="w-full bg-blue-600 text-white font-semibold rounded px-4 py-2 hover:bg-blue-700"
    >
      {{ isset($video) ? 'Update Video' : 'Add Video' }}
    </button>
  </form>
</div>
@endsection
