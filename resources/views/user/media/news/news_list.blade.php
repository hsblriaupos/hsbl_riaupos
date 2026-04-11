@extends('user.layouts.app')

@section('title', 'News | Riau Pos - SBL')

@php
use Illuminate\Support\Str;
@endphp

@section('content')

{{-- Header Section - Responsive margin --}}
<div class="md:-mt-10 -mt-20 mb-8 text-center">
  <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-blue-50 rounded-full mb-4">
    <span class="relative flex h-2 w-2">
      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
      <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
    </span>
    <span class="text-xs font-semibold text-blue-600 tracking-wide">LATEST UPDATES</span>
  </div>
  <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">
    News & <span class="text-blue-600">Articles</span>
  </h1>
  <p class="text-gray-500 text-sm max-w-md mx-auto">Latest news and updates from Riau Pos Student Basketball League</p>
</div>

{{-- Search & Filter - BORDER BAWAH TAPI LEBIH JELAS --}}
<div class="mb-8">
  <form method="GET" action="{{ route('user.news.index') }}" class="flex flex-wrap items-end gap-3">
    {{-- Search Input --}}
    <div class="flex-1 min-w-[200px]">
      <div class="relative group">
        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-500 text-sm transition-colors"></i>
        <input type="text"
          name="search"
          value="{{ request('search') }}"
          placeholder="Search news by title or content..."
          class="w-full pl-9 pr-3 py-2.5 text-sm border-b-2 border-gray-200 focus:border-blue-500 focus:outline-none bg-black-50/30 focus:bg-gray-50 transition-all rounded-t-lg">
      </div>
    </div>

    {{-- Series Filter --}}
    <div class="w-48">
      <div class="relative group">
        <select name="series"
          class="w-full px-3 py-2.5 text-sm border-b-2 border-gray-200 focus:border-blue-500 focus:outline-none appearance-none bg-black-50/30 focus:bg-gray-50 cursor-pointer transition-all rounded-t-lg">
          <option value="">All Series</option>
          @foreach($seriesList as $series)
          <option value="{{ $series }}" @selected(request('series')===$series)>{{ $series }}</option>
          @endforeach
        </select>
        <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-500 text-xs transition-colors pointer-events-none"></i>
      </div>
    </div>

    {{-- Action Buttons --}}
    <div class="flex gap-2">
      <button type="submit"
        class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
        <i class="fas fa-search mr-1.5 text-xs"></i>
        Search
      </button>

      @if(request()->has('search') || request()->has('series'))
      <a href="{{ route('user.news.index') }}"
        class="px-5 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-800 border border-gray-200 hover:border-gray-300 rounded-lg transition-all duration-200 bg-white">
        <i class="fas fa-times mr-1.5 text-xs"></i>
        Reset
      </a>
      @endif
    </div>
  </form>

  {{-- Active Filters Badges - Minimalis --}}
  @if(request()->has('search') || request()->has('series'))
  <div class="flex flex-wrap gap-2 mt-4">
    @if(request('search'))
    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs rounded-full bg-gray-100 text-gray-700 border border-gray-200">
      <i class="fas fa-search text-gray-400 text-[10px]"></i>
      "{{ request('search') }}"
      <a href="{{ route('user.news.index', array_merge(request()->except('search'), ['page' => 1])) }}"
        class="ml-1 text-gray-400 hover:text-red-500 transition-colors">
        <i class="fas fa-times-circle text-xs"></i>
      </a>
    </span>
    @endif
    @if(request('series'))
    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs rounded-full bg-gray-100 text-gray-700 border border-gray-200">
      <i class="fas fa-layer-group text-gray-400 text-[10px]"></i>
      {{ request('series') }}
      <a href="{{ route('user.news.index', array_merge(request()->except('series'), ['page' => 1])) }}"
        class="ml-1 text-gray-400 hover:text-red-500 transition-colors">
        <i class="fas fa-times-circle text-xs"></i>
      </a>
    </span>
    @endif
  </div>
  @endif
</div>

{{-- Results Info --}}
@if($news->count() > 0)
<div class="flex justify-between items-center mb-5 text-sm">
  <div class="text-gray-500">
    <i class="fas fa-chart-line text-blue-400 mr-1.5 text-xs"></i>
    {{ $news->firstItem() }}-{{ $news->lastItem() }} of {{ $news->total() }} articles
  </div>
  <div class="flex items-center gap-1 text-gray-400 text-xs">
    <i class="fas fa-clock"></i>
    <span>Latest first</span>
  </div>
</div>
@endif

{{-- Grid Berita - Minimalis Elegan --}}
@if($news->count() > 0)
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
  @foreach($news as $item)
  <article class="group">
    <a href="{{ route('user.news.show', $item->id) }}" class="block">
      {{-- Image --}}
      <div class="relative overflow-hidden rounded-xl mb-3 bg-gray-100 aspect-[4/3]">
        @if($item->image && file_exists(public_path($item->image)))
        <img src="{{ asset($item->image) }}" alt="{{ $item->title }}"
          class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
          loading="lazy"
          onerror="this.src='{{ asset('images/default_news.jpg') }}'">
        @else
        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-50 to-gray-100">
          <i class="fas fa-newspaper text-4xl text-gray-300"></i>
        </div>
        @endif

        {{-- Series Badge --}}
        @if($item->series)
        <span class="absolute top-2 left-2 px-2 py-0.5 text-[10px] font-semibold rounded-md bg-white/90 backdrop-blur-sm text-blue-600 shadow-sm">
          {{ $item->series }}
        </span>
        @endif

        {{-- Hover overlay --}}
        <div class="absolute inset-0 bg-blue-600/70 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
          <span class="px-4 py-1.5 text-xs font-semibold text-white bg-white/20 rounded-full backdrop-blur-sm">
            Read Article
          </span>
        </div>
      </div>

      {{-- Content --}}
      <div>
        <div class="flex items-center gap-2 text-xs text-gray-400 mb-1.5">
          <i class="far fa-calendar-alt text-[11px]"></i>
          <span>{{ $item->created_at->format('d M Y') }}</span>
        </div>
        <h2 class="font-semibold text-gray-800 mb-1.5 line-clamp-2 group-hover:text-blue-600 transition-colors">
          {{ $item->title }}
        </h2>
        <p class="text-gray-500 text-sm line-clamp-2">
          {{ Str::limit(strip_tags($item->content), 80) }}
        </p>
        <div class="mt-3 flex items-center text-blue-500 text-xs font-medium group-hover:translate-x-1 transition-transform">
          Read more <i class="fas fa-arrow-right ml-1 text-[10px]"></i>
        </div>
      </div>
    </a>
  </article>
  @endforeach
</div>
@else
{{-- Empty State - Dashed Border (Style awal kamu) --}}
<div class="text-center py-12 px-6 bg-gradient-to-br from-blue-50 to-white rounded-2xl border-2 border-dashed border-blue-200">
    <div class="w-20 h-20 mx-auto mb-4 bg-white rounded-full flex items-center justify-center shadow-sm">
        <i class="fas fa-newspaper text-blue-300 text-3xl"></i>
    </div>
    <h3 class="text-xl font-bold text-gray-700 mb-2">No News Found</h3>
    <p class="text-gray-500 text-sm mb-5">
        @if(request()->has('search') || request()->has('series'))
            Try adjusting your search or filter to find what you're looking for.
        @else
            Check back soon for new articles and updates.
        @endif
    </p>
    @if(request()->has('search') || request()->has('series'))
        <a href="{{ route('user.news.index') }}" 
           class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 rounded-xl transition-all duration-200 shadow-md hover:shadow-lg">
            <i class="fas fa-eye text-xs"></i>
            View All News
        </a>
    @endif
</div>
@endif

{{-- Pagination --}}
@if($news->hasPages())
<div class="mt-10 flex justify-center">
  {{ $news->links('vendor.pagination.tailwind') }}
</div>
@endif

@push('styles')
<style>
  .line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }

  .line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }

  /* Smooth hover transitions */
  .group-hover\:translate-x-1 {
    transition: transform 0.2s ease;
  }

  /* Placeholder styling */
  input::placeholder {
    color: #9ca3af;
    font-weight: 400;
  }

  input:focus::placeholder {
    color: #cbd5e1;
  }
</style>
@endpush

@endsection