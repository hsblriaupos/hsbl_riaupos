@extends('user.layouts.app')

@section('title', 'News | Riau Pos - SBL')

@php
    use Illuminate\Support\Str;
@endphp

@section('content')

  {{-- Header Section --}}
  <div class="mb-4 md:mb-6 text-center">  
    <div class="inline-block px-5 py-1.5 bg-gradient-to-r from-blue-600 to-blue-500 rounded-full mb-3 shadow-sm">
      <span class="text-white text-xs font-semibold">LATEST UPDATES</span>
    </div>
    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">News & Articles</h1>
    <p class="text-gray-600 max-w-2xl mx-auto text-sm">Stay updated with the latest news, announcements, and insights from our community</p>
  </div>

  {{-- Search & Filter Section --}}
  <div class="bg-white rounded-xl shadow-lg p-5 mb-6 border border-gray-100">
    <form method="GET" action="{{ route('user.news.index') }}" class="space-y-4 md:space-y-0 md:flex md:items-end md:space-x-4">
      <div class="flex-1">
        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search News</label>
        <div class="relative">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </div>
          <input
            type="text"
            id="search"
            name="search"
            value="{{ request('search') }}"
            placeholder="Search by title or content..."
            class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition"
          >
        </div>
      </div>

      <div class="flex-1">
        <label for="series" class="block text-sm font-medium text-gray-700 mb-1">Filter by Series</label>
        <div class="relative">
          <select
            id="series"
            name="series"
            class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none appearance-none bg-white"
          >
            <option value="">All Series</option>
            @foreach($seriesList as $series)
              <option value="{{ $series }}" @selected(request('series') === $series)>{{ $series }}</option>
            @endforeach
          </select>
          <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
          </div>
        </div>
      </div>

      <div class="flex space-x-3">
        <button
          type="submit"
          class="inline-flex items-center justify-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition shadow-md hover:shadow-lg"
        >
          <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
          Search
        </button>
        
        @if(request()->has('search') || request()->has('series'))
          <a 
            href="{{ route('user.news.index') }}" 
            class="inline-flex items-center justify-center px-4 py-2.5 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition"
          >
            Clear Filters
          </a>
        @endif
      </div>
    </form>
    
    {{-- Active filters --}}
    @if(request()->has('search') || request()->has('series'))
      <div class="mt-3 flex flex-wrap gap-2">
        @if(request('search'))
          <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
            Search: "{{ request('search') }}"
            <a href="{{ route('user.news.index', array_merge(request()->except('search'), ['page' => 1])) }}" class="ml-1.5 text-blue-600 hover:text-blue-800">
              ×
            </a>
          </span>
        @endif
        @if(request('series'))
          <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
            Series: {{ request('series') }}
            <a href="{{ route('user.news.index', array_merge(request()->except('series'), ['page' => 1])) }}" class="ml-1.5 text-indigo-600 hover:text-indigo-800">
              ×
            </a>
          </span>
        @endif
      </div>
    @endif
  </div>

  {{-- Results Count --}}
  @if($news->count() > 0)
    <div class="mb-4 flex justify-between items-center">
      <p class="text-gray-600 text-sm">
        Showing <span class="font-semibold">{{ $news->firstItem() }}-{{ $news->lastItem() }}</span> 
        of <span class="font-semibold">{{ $news->total() }}</span> articles
      </p>
      <div class="text-xs text-blue-600 font-medium">
        <i class="fas fa-newspaper mr-1.5"></i>
        Latest First
      </div>
    </div>
  @endif

  {{-- Grid Berita --}}
  @if($news->count() > 0)
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 lg:gap-6">
      @foreach($news as $item)
        <article class="group bg-white rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 border border-gray-100 hover:border-blue-200">
          <a href="{{ route('user.news.show', $item->id) }}" class="block h-full">
            {{-- Gambar berita dengan fallback default --}}
            <div class="relative overflow-hidden h-48 bg-gradient-to-br from-blue-50 to-gray-100">
              @if($item->image && file_exists(public_path($item->image)))
                <img
                  src="{{ asset($item->image) }}"
                  alt="{{ $item->title }}"
                  class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                  loading="lazy"
                  onerror="this.onerror=null; this.src='{{ asset('images/default_news.jpg') }}';"
                >
              @else
                {{-- Default News Image dengan overlay --}}
                <div class="relative w-full h-full flex items-center justify-center">
                  <div class="absolute inset-0 bg-gradient-to-br from-blue-500/10 to-blue-600/10"></div>
                  <div class="relative z-10 text-center p-4">
                    <div class="w-12 h-12 mx-auto mb-2 text-blue-400">
                      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                      </svg>
                    </div>
                    <p class="text-xs font-medium text-blue-600">Riau Pos - SBL</p>
                    <p class="text-[10px] text-gray-500 mt-1">News Article</p>
                  </div>
                  {{-- Overlay untuk efek hover --}}
                  <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </div>
              @endif
              
              <div class="absolute top-3 left-3">
                @if($item->series)
                  <span class="px-2 py-1 text-xs font-semibold rounded-full bg-white/95 text-blue-600 shadow-sm">
                    {{ $item->series }}
                  </span>
                @endif
              </div>
              <div class="absolute bottom-3 right-3">
                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-md">
                  Read More
                </span>
              </div>
            </div>

            <div class="p-4">
              {{-- Date --}}
              <div class="flex items-center text-xs text-gray-500 mb-2">
                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                {{ $item->created_at->format('d M Y') }}
              </div>

              {{-- Title --}}
              <h2 class="font-bold text-lg text-gray-900 mb-2 group-hover:text-blue-600 transition-colors line-clamp-2">
                {{ $item->title }}
              </h2>

              {{-- Excerpt --}}
              <p class="text-gray-600 mb-3 text-sm line-clamp-3">
                {{ Str::limit(strip_tags($item->content), 100) }}
              </p>

              {{-- Read More Link --}}
              <div class="flex items-center text-blue-600 font-semibold text-xs group-hover:underline">
                <span>Continue Reading</span>
                <svg class="w-3.5 h-3.5 ml-1.5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
              </div>
            </div>
          </a>
        </article>
      @endforeach
    </section>
  @else
    {{-- Empty State --}}
    <div class="text-center py-12 bg-gradient-to-br from-blue-50 to-white rounded-2xl border-2 border-dashed border-blue-200">
      <div class="max-w-md mx-auto">
        <div class="w-20 h-20 mx-auto mb-4 text-blue-200">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-700 mb-2">No News Found</h3>
        <p class="text-gray-500 mb-4 text-sm">
          @if(request()->has('search') || request()->has('series'))
            Try adjusting your search or filter to find what you're looking for.
          @else
            Check back soon for new articles and updates.
          @endif
        </p>
        @if(request()->has('search') || request()->has('series'))
          <a 
            href="{{ route('user.news.index') }}" 
            class="inline-flex items-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition shadow-md"
          >
            <i class="fas fa-newspaper mr-2 text-xs"></i>
            View All News
          </a>
        @endif
      </div>
    </div>
  @endif

  {{-- Pagination --}}
  @if($news->hasPages())
    <div class="mt-8 md:mt-10 flex justify-center">
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-2">
        {{-- Panggil pagination view custom --}}
        {{ $news->links('vendor.pagination.tailwind') }}
      </div>
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
</style>
@endpush
@endsection