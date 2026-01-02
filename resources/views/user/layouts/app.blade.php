<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>@yield('title', 'HSBL Riau Pos')</title>

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('uploads/logo/hsbl.png') }}" type="image/png" />

    {{-- Tailwind via CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet" />
    <link href="{{ asset('css/layoutWeb.css') }}" rel="stylesheet" />
    <style>
        
    </style>

    @stack('styles')
</head>
<body class="bg-gray-200" id="user-layout">

    {{-- ==================== HEADER ==================== --}}
    <header class="fixed top-0 left-0 right-0 z-50 bg-white rounded-b-[40px] shadow-lg px-6 py-4 max-w-7xl mx-auto"
        x-data="{ openMenu: null, mobileOpen: false }" @click.away="openMenu = null">
        <div class="flex items-center justify-between">
            {{-- Logo --}}
            <a href="{{ url('user/dashboard') }}">
                <img src="{{ asset('uploads/logo/hsbl.png') }}" alt="HSBL Riau Pos Logo" class="h-14 w-14 rounded-md" />
            </a>

            {{-- Toggle Button (mobile) --}}
            <div class="md:hidden">
                <button @click="mobileOpen = !mobileOpen" class="relative w-8 h-8 focus:outline-none">
                    {{-- Hamburger icon --}}
                    <svg x-show="!mobileOpen" x-cloak class="w-8 h-8 absolute right-0 top-0" fill="none"
                        stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    {{-- Close (X) icon --}}
                    <svg x-show="mobileOpen" x-cloak class="w-8 h-8 absolute right-0 top-0" fill="none"
                        stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Navigation --}}
            <nav :class="mobileOpen ? 'block' : 'hidden'"
                class="w-full md:flex md:items-center md:space-x-6 text-sm font-normal md:w-auto mt-4 md:mt-0">
                <ul class="flex flex-col md:flex-row gap-y-2 md:gap-y-0 md:gap-x-6">
                    @php
                    $menu = [
                        ['label' => 'Home', 'url' => 'user/dashboard'],
                        ['label' => 'News', 'url' => 'user/news'],
                        ['label' => 'Schedules & Results', 'url' => route('user.schedule_result')],
                        ['label' => 'Statistics', 'url' => '/statistics_0301'],
                        ['label' => 'Gallery', 'url' => '#', 'submenu' => [
                            ['label' => 'Videos', 'url' => 'user/videos'],
                            ['label' => 'Photos', 'url' => '/photos_0501'],
                        ]],
                        ['label' => 'Riau Pos - Honda HSBL History', 'url' => '/Riau-Pos-Honda-HSBL-History_0601'],
                        ['label' => 'Developer', 'url' => '/Developer_0601'],
                    ];
                    @endphp

                    @foreach($menu as $index => $item)
                        @if(isset($item['submenu']))
                            <li class="relative">
                                <button @click="openMenu === {{ $index }} ? openMenu = null : openMenu = {{ $index }}" type="button"
                                    class="hover:underline hover:text-[#71BBB2] flex items-center gap-1 w-full md:w-auto"
                                    :class="openMenu === {{ $index }} ? 'font-bold text-teal-600' : ''">
                                    {{ $item['label'] }}
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <ul x-show="openMenu === {{ $index }}" x-transition x-cloak
                                    class="bg-white md:absolute md:left-0 md:mt-2 w-max rounded shadow-md z-50">
                                    @foreach($item['submenu'] as $sub)
                                        <li>
                                            <a href="{{ url($sub['url']) }}"
                                                class="block px-4 py-2 text-xs whitespace-nowrap hover:bg-gray-100 hover:text-[#71BBB2]">
                                                {{ $sub['label'] }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @else
                            <li>
                                <a href="{{ url($item['url']) }}"
                                    class="hover:underline hover:text-[#71BBB2] @if(request()->is(ltrim($item['url'], '/'))) font-bold text-teal-600 @endif">
                                    {{ $item['label'] }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </nav>
        </div>
    </header>

    {{-- ==================== MAIN CONTENT ==================== --}}
    <main class="flex-grow w-full px-4">
        <div class="max-w-7xl mx-auto w-full">
            @yield('content')
        </div>
    </main>

    {{-- ==================== FOOTER ==================== --}}
    <footer class="w-full bg-white rounded-t-[40px] shadow-inner py-8 px-6 mt-12">
        <div class="max-w-7xl mx-auto">
            <div class="mb-6">
                {{-- Pastikan variable $groupedSponsors ada --}}
                @php
                    $groupedSponsors = $groupedSponsors ?? collect();
                @endphp

                @if($groupedSponsors->count() > 0)
                    @foreach($groupedSponsors as $category => $sponsors)
                        @if($sponsors->count() > 0)
                            <div class="mb-4">
                                <h4 class="font-semibold text-sm uppercase mb-4 text-center">
                                    {{ $category }}
                                </h4>
                                {{-- Urutan kanan ke kiri, tapi tampil dari lama ke baru --}}
                                <div class="flex flex-row justify-center gap-6">
                                    @foreach($sponsors->sortBy('created_at') as $sponsor)
                                        @php
                                            $imgClass = match($category) {
                                                'Presented by' => 'h-16',
                                                'Official Partners', 'Official Suppliers', 'Supporting Partners' => 'h-12',
                                                'Managed by' => 'h-16',
                                                default => 'h-12',
                                            };
                                        @endphp
                                        <a href="{{ $sponsor->sponsors_web ?? '#' }}" target="_blank" class="flex items-center">
                                            @if($sponsor->logo)
                                                <img
                                                    src="{{ asset('uploads/sponsors/' . $sponsor->logo) }}"
                                                    alt="{{ $sponsor->sponsor_name }}"
                                                    class="{{ $imgClass }} object-contain"
                                                />
                                            @else
                                                <div class="{{ $imgClass }} w-auto flex items-center justify-center px-4 bg-gray-100 rounded">
                                                    <span class="text-sm font-medium">{{ $sponsor->sponsor_name }}</span>
                                                </div>
                                            @endif
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach
                @else
                    {{-- Tampilkan pesan jika tidak ada sponsor --}}
                    <div class="text-center text-gray-500 py-4">
                        <p>Sponsor information will be available soon.</p>
                    </div>
                @endif
            </div>

            <div class="text-center text-xs text-gray-600">
                &copy; 2025 Riau Pos - Honda HSBL All Rights Reserved<br>
                Developed with ❤️ by : Mutia Rizkianti | Wafiq Wardatul Khairani
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>