@extends('user.layouts.app')

@section('title', $item->title)

@section('content')
<div class="max-w-7xl mx-auto">
  {{-- Breadcrumb --}}
  <nav class="mb-6">
    <ol class="flex items-center space-x-2 text-sm text-gray-600">
      <li>
        <a href="{{ route('user.news.index') }}" class="hover:text-blue-600 transition">News</a>
      </li>
      <li class="flex items-center">
        <svg class="w-4 h-4 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </li>
      <li class="font-medium text-blue-600 truncate max-w-xs">
        {{ Str::limit($item->title, 60) }}
      </li>
    </ol>
  </nav>

  {{-- SATU CARD UTAMA UNTUK SEMUA KONTEN --}}
  <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
    {{-- Grid untuk konten utama dan sidebar --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-0 lg:gap-0">
      {{-- Konten Utama (Artikel) --}}
      <div class="lg:col-span-2 border-r border-gray-100">
        {{-- Featured Image dengan Fallback --}}
        <div class="relative h-64 md:h-80 lg:h-96 overflow-hidden bg-gradient-to-br from-blue-50 to-gray-100">
          @if($item->image && file_exists(public_path($item->image)))
            <img src="{{ asset($item->image) }}"
                 alt="{{ $item->title }}"
                 class="w-full h-full object-cover transition-transform duration-500 hover:scale-105"
                 onerror="this.onerror=null; this.src='{{ asset('images/default_news.jpg') }}';">
          @else
            {{-- Default News Image dengan Desain Menarik --}}
            <div class="relative w-full h-full flex items-center justify-center">
              <div class="absolute inset-0 bg-gradient-to-br from-blue-500/20 to-blue-600/20"></div>
              <div class="relative z-10 text-center p-6">
                <div class="w-16 h-16 mx-auto mb-3 text-blue-500">
                  <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                  </svg>
                </div>
                <p class="text-sm font-semibold text-blue-700">Riau Pos - Honda HSBL</p>
                <p class="text-xs text-gray-600 mt-1">{{ $item->series }}</p>
              </div>
              {{-- Overlay gradient --}}
              <div class="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent"></div>
            </div>
          @endif
          <div class="absolute top-4 left-4">
            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-white/90 text-blue-600 shadow-sm">
              {{ $item->series }}
            </span>
          </div>
        </div>

        {{-- Article Content --}}
        <div class="p-6 md:p-8">
          <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-4">
              <div class="flex items-center text-sm text-gray-500">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                {{ $item->created_at->format('d F Y') }}
              </div>
              <div class="flex items-center text-sm text-gray-500">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ $item->created_at->format('H:i') }}
              </div>
            </div>
            <div class="text-xs text-gray-400">
              Posted by: {{ $item->posted_by ?? 'Admin' }}
            </div>
          </div>

          <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6 leading-tight">
            {{ $item->title }}
          </h1>

          <div class="prose prose-lg max-w-none text-gray-700">
            {!! nl2br(e($item->content)) !!}
          </div>

          {{-- Tags --}}
          @if($item->series)
          <div class="mt-8 pt-6 border-t border-gray-100">
            <div class="flex items-center space-x-2">
              <span class="text-sm font-medium text-gray-600">Category:</span>
              <span class="px-3 py-1 bg-blue-50 text-blue-600 text-sm font-medium rounded-full">
                {{ $item->series }}
              </span>
            </div>
          </div>
          @endif

          {{-- Navigation --}}
          <div class="mt-8 flex justify-between">
            <a href="{{ route('user.news.index') }}" 
               class="inline-flex items-center px-4 py-2.5 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 hover:border-blue-300 transition">
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
              </svg>
              Back to News
            </a>
            <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" 
                    class="inline-flex items-center px-4 py-2.5 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 hover:border-blue-300 transition">
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
              </svg>
              Back to Top
            </button>
          </div>
        </div>
      </div>

      {{-- Sidebar dalam Card yang Sama --}}
      <div class="border-t lg:border-t-0 lg:border-l border-gray-100">
        <div class="p-6 md:p-8">
          {{-- Latest News Section --}}
          <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
              <h2 class="text-lg font-bold text-gray-900">Latest News</h2>
              <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full font-medium">
                8 Latest
              </span>
            </div>
            <p class="text-sm text-gray-500 mb-4">Latest updates from HSBL</p>
            
            <div class="space-y-4">
              @php
                // Ambil 8 berita terbaru selain yang sedang dilihat
                $latestNews = App\Models\News::where('id', '!=', $item->id)
                    ->where('status', 'view')
                    ->latest()
                    ->take(8)
                    ->get();
              @endphp
              
              @foreach($latestNews as $latestItem)
              <a href="{{ route('user.news.show', $latestItem->id) }}" 
                 class="group flex items-start p-3 hover:bg-blue-50 rounded-lg transition">
                <div class="flex-shrink-0 w-12 h-12 rounded-lg overflow-hidden bg-gradient-to-br from-gray-50 to-gray-100">
                  @if($latestItem->image && file_exists(public_path($latestItem->image)))
                    <img src="{{ asset($latestItem->image) }}"
                         alt="{{ $latestItem->title }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                         onerror="this.onerror=null; this.src='{{ asset('images/default_news.jpg') }}';">
                  @else
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-100 to-blue-50">
                      <div class="text-center">
                        <div class="w-5 h-5 mx-auto text-blue-500">
                          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                          </svg>
                        </div>
                      </div>
                    </div>
                  @endif
                </div>
                <div class="ml-3 flex-1 min-w-0">
                  <div class="flex items-start justify-between">
                    <h3 class="text-sm font-semibold text-gray-900 group-hover:text-blue-600 transition line-clamp-2 leading-tight">
                      {{ Str::limit($latestItem->title, 50) }}
                    </h3>
                    <span class="ml-2 px-1.5 py-0.5 text-[9px] font-semibold rounded bg-blue-50 text-blue-600 whitespace-nowrap">
                      {{ Str::limit($latestItem->series, 8) }}
                    </span>
                  </div>
                  <div class="mt-1 flex items-center text-xs text-gray-500">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    {{ $latestItem->created_at->format('d M Y') }}
                  </div>
                </div>
              </a>
              @endforeach
            </div>
          </div>

          {{-- Divider --}}
          <div class="border-t border-gray-200 my-6"></div>

          {{-- Featured News Slider --}}
          <div>
            <div class="flex items-center justify-between mb-4">
              <h2 class="text-lg font-bold text-gray-900">Featured News</h2>
              <div class="flex space-x-1">
                <button onclick="prevSlide()" class="p-1.5 rounded-full bg-gray-100 hover:bg-gray-200 transition">
                  <svg class="w-3.5 h-3.5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                  </svg>
                </button>
                <button onclick="nextSlide()" class="p-1.5 rounded-full bg-gray-100 hover:bg-gray-200 transition">
                  <svg class="w-3.5 h-3.5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                  </svg>
                </button>
              </div>
            </div>
            <p class="text-sm text-gray-500 mb-4">Slide to see more news</p>
            
            {{-- Slider Container --}}
            <div class="relative">
              <div id="newsSlider" class="overflow-hidden rounded-lg">
                <div class="flex transition-transform duration-500" id="sliderTrack">
                  @php
                    $sliderNews = App\Models\News::where('id', '!=', $item->id)
                        ->where('status', 'view')
                        ->inRandomOrder()
                        ->take(5)
                        ->get();
                  @endphp
                  
                  @foreach($sliderNews as $sliderItem)
                  <div class="w-full flex-shrink-0">
                    <a href="{{ route('user.news.show', $sliderItem->id) }}" class="group block">
                      <div class="relative aspect-[4/3] overflow-hidden bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg">
                        @if($sliderItem->image && file_exists(public_path($sliderItem->image)))
                          <img src="{{ asset($sliderItem->image) }}"
                               alt="{{ $sliderItem->title }}"
                               class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                               onerror="this.onerror=null; this.src='{{ asset('images/default_news.jpg') }}';">
                        @else
                          <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-200 to-blue-100">
                            <div class="text-center p-4">
                              <div class="w-10 h-10 mx-auto mb-2 text-blue-700">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                </svg>
                              </div>
                              <p class="text-xs font-semibold text-blue-900">Riau Pos HSBL</p>
                            </div>
                          </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-3">
                          <span class="inline-block px-1.5 py-0.5 text-xs font-semibold rounded bg-white/90 text-blue-600 mb-1">
                            {{ Str::limit($sliderItem->series, 10) }}
                          </span>
                          <h3 class="text-xs font-semibold text-white group-hover:text-blue-200 transition line-clamp-2">
                            {{ Str::limit($sliderItem->title, 45) }}
                          </h3>
                          <p class="text-[11px] text-white/80 mt-1">
                            {{ $sliderItem->created_at->format('d M Y') }}
                          </p>
                        </div>
                      </div>
                    </a>
                  </div>
                  @endforeach
                </div>
              </div>

              {{-- Slide Indicators --}}
              <div class="absolute bottom-3 left-1/2 transform -translate-x-1/2 flex space-x-1.5">
                @foreach($sliderNews as $index => $sliderItem)
                <button onclick="goToSlide({{ $index }})" 
                        class="slide-indicator w-1.5 h-1.5 rounded-full bg-white/50 hover:bg-white transition"
                        data-slide="{{ $index }}">
                </button>
                @endforeach
              </div>
            </div>

            {{-- Auto-play Controls --}}
            <div class="mt-4 pt-4 border-t border-gray-200">
              <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                  <span class="text-xs text-gray-600">Auto-slide:</span>
                  <button onclick="toggleAutoSlide()" 
                          id="autoSlideToggle" 
                          class="relative inline-flex h-5 w-9 items-center rounded-full bg-blue-600 transition">
                    <span id="autoSlideThumb" class="inline-block h-3 w-3 transform translate-x-5 rounded-full bg-white transition"></span>
                  </button>
                </div>
                <span id="currentSlideInfo" class="text-xs text-gray-500">
                  Slide 1 of {{ $sliderNews->count() }}
                </span>
              </div>
            </div>
          </div>

          {{-- View All Button --}}
          <div class="mt-8">
            <a href="{{ route('user.news.index') }}" 
               class="block w-full text-center px-4 py-2.5 text-sm font-medium rounded-lg text-white bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 transition shadow-sm">
              View All News
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@push('styles')
<style>
  .prose {
    color: #374151;
    line-height: 1.75;
  }
  .prose p {
    margin-bottom: 1.25em;
  }
  .prose img {
    border-radius: 0.5rem;
    margin: 1.5em 0;
  }
  .prose a {
    color: #3b82f6;
    text-decoration: underline;
    font-weight: 500;
  }
  .prose a:hover {
    color: #2563eb;
  }
  .line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }
</style>
@endpush

@push('scripts')
<script>
  // Slider Variables
  let currentSlide = 0;
  let autoSlideInterval;
  let isAutoSlideEnabled = true;
  const totalSlides = {{ $sliderNews->count() }};
  const slideDuration = 5000; // 5 seconds

  // Initialize Slider
  function initSlider() {
    updateSlideIndicators();
    updateSlideInfo();
    if (isAutoSlideEnabled) {
      startAutoSlide();
    }
  }

  // Next Slide
  function nextSlide() {
    currentSlide = (currentSlide + 1) % totalSlides;
    updateSlider();
  }

  // Previous Slide
  function prevSlide() {
    currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
    updateSlider();
  }

  // Go to Specific Slide
  function goToSlide(index) {
    currentSlide = index;
    updateSlider();
  }

  // Update Slider Position
  function updateSlider() {
    const sliderTrack = document.getElementById('sliderTrack');
    if (!sliderTrack) return;
    
    const slideWidth = 100; // 100% per slide
    sliderTrack.style.transform = `translateX(-${currentSlide * slideWidth}%)`;
    
    updateSlideIndicators();
    updateSlideInfo();
  }

  // Update Slide Indicators
  function updateSlideIndicators() {
    document.querySelectorAll('.slide-indicator').forEach((indicator, index) => {
      if (index === currentSlide) {
        indicator.classList.remove('bg-white/50');
        indicator.classList.add('bg-white', 'w-3', 'h-3');
      } else {
        indicator.classList.remove('bg-white', 'w-3', 'h-3');
        indicator.classList.add('bg-white/50', 'w-1.5', 'h-1.5');
      }
    });
  }

  // Update Slide Info
  function updateSlideInfo() {
    const slideInfo = document.getElementById('currentSlideInfo');
    if (slideInfo) {
      slideInfo.textContent = `Slide ${currentSlide + 1} of ${totalSlides}`;
    }
  }

  // Auto Slide Functions
  function startAutoSlide() {
    if (autoSlideInterval) clearInterval(autoSlideInterval);
    autoSlideInterval = setInterval(nextSlide, slideDuration);
  }

  function stopAutoSlide() {
    if (autoSlideInterval) clearInterval(autoSlideInterval);
  }

  function toggleAutoSlide() {
    isAutoSlideEnabled = !isAutoSlideEnabled;
    const thumb = document.getElementById('autoSlideThumb');
    const toggle = document.getElementById('autoSlideToggle');
    
    if (isAutoSlideEnabled) {
      thumb.style.transform = 'translateX(5px)';
      toggle.classList.add('bg-blue-600');
      toggle.classList.remove('bg-gray-300');
      startAutoSlide();
    } else {
      thumb.style.transform = 'translateX(1px)';
      toggle.classList.remove('bg-blue-600');
      toggle.classList.add('bg-gray-300');
      stopAutoSlide();
    }
  }

  // Pause auto-slide on hover
  const newsSlider = document.getElementById('newsSlider');
  if (newsSlider) {
    newsSlider.addEventListener('mouseenter', () => {
      if (isAutoSlideEnabled) {
        stopAutoSlide();
      }
    });

    newsSlider.addEventListener('mouseleave', () => {
      if (isAutoSlideEnabled) {
        startAutoSlide();
      }
    });
  }

  // Touch/Swipe Support
  let touchStartX = 0;
  let touchEndX = 0;

  const sliderTrack = document.getElementById('sliderTrack');
  if (sliderTrack) {
    sliderTrack.addEventListener('touchstart', (e) => {
      touchStartX = e.changedTouches[0].screenX;
    });

    sliderTrack.addEventListener('touchend', (e) => {
      touchEndX = e.changedTouches[0].screenX;
      handleSwipe();
    });
  }

  function handleSwipe() {
    const swipeThreshold = 50;
    const diff = touchStartX - touchEndX;
    
    if (Math.abs(diff) > swipeThreshold) {
      if (diff > 0) {
        // Swipe left - next slide
        nextSlide();
      } else {
        // Swipe right - previous slide
        prevSlide();
      }
    }
  }

  // Initialize on page load
  document.addEventListener('DOMContentLoaded', () => {
    initSlider();
  });
</script>
@endpush
@endsection