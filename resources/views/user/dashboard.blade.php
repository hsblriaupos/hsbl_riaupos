@extends('user.layouts.app')

@section('title', 'Dashboard - HSBL Riau Pos')

@section('content')
<div class="max-w-7xl mx-auto">
    {{-- Hero Welcome Section --}}
    <div class="mb-8 md:mb-12">
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-blue-600 via-blue-500 to-cyan-500 shadow-xl">
            <div class="absolute inset-0 bg-black/5"></div>
            <div class="relative px-6 py-8 md:px-10 md:py-12">
                <div class="flex flex-col md:flex-row items-center justify-between">
                    <div class="md:w-2/3 mb-6 md:mb-0">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="bg-white/20 p-2 rounded-lg backdrop-blur-sm">
                                <i class="fas fa-basketball-ball text-white text-xl"></i>
                            </div>
                            <span class="text-white/90 text-sm font-medium tracking-wider">HONDA STUDENT BASKETBALL LEAGUE</span>
                        </div>
                        <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-3 leading-tight">
                            Welcome to<br>
                            <span class="text-yellow-300">HSBL Riau Pos</span>
                        </h1>
                        <p class="text-lg text-blue-100 mb-6 max-w-2xl">
                            The biggest student basketball competition in Riau, where young talents showcase their skills and fighting spirit.
                        </p>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('user.schedule_result') }}" 
                               class="btn-primary inline-flex items-center space-x-2 bg-gradient-to-r from-yellow-400 to-yellow-500 hover:from-yellow-500 hover:to-yellow-600 text-gray-900 px-4 py-2 rounded-lg font-medium transition-all duration-300">
                                <i class="fas fa-calendar-alt"></i>
                                <span>Schedule & Results</span>
                            </a>
                            <a href="{{ route('user.media.gallery.photos') }}" 
                               class="btn-primary inline-flex items-center space-x-2 bg-gradient-to-r from-blue-400 to-blue-500 hover:from-blue-500 hover:to-blue-600 text-white px-4 py-2 rounded-lg font-medium transition-all duration-300">
                                <i class="fas fa-images"></i>
                                <span>Photo Gallery</span>
                            </a>
                        </div>
                    </div>
                    <div class="md:w-1/3 flex justify-center">
                        <div class="relative">
                            <div class="w-40 h-40 md:w-48 md:h-48 bg-white/10 rounded-full flex items-center justify-center backdrop-blur-sm border-4 border-white/20">
                                <div class="w-32 h-32 md:w-40 md:h-40 bg-white/20 rounded-full flex items-center justify-center">
                                    <img src="{{ asset('uploads/logo/hsbl.png') }}" 
                                         alt="HSBL Logo" 
                                         class="w-24 h-24 md:w-32 md:h-32 animate-pulse-slow">
                                </div>
                            </div>
                            <div class="absolute -top-2 -right-2 w-16 h-16 bg-yellow-400 rounded-full flex items-center justify-center shadow-lg">
                                <i class="fas fa-trophy text-white text-xl"></i>
                            </div>
                            <div class="absolute -bottom-4 -left-4 w-12 h-12 bg-red-500 rounded-full flex items-center justify-center shadow-lg">
                                <i class="fas fa-basketball-ball text-white text-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Access Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <a href="{{ route('user.news.index') }}" class="group">
            <div class="bg-white rounded-xl p-6 shadow hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 border border-blue-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition-colors duration-300">
                        <i class="fas fa-newspaper text-blue-600 text-xl"></i>
                    </div>
                    <i class="fas fa-arrow-right text-blue-400 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></i>
                </div>
                <h3 class="font-semibold text-gray-800 mb-2">Latest News</h3>
                <p class="text-sm text-gray-600">Stay updated with HSBL events and announcements</p>
            </div>
        </a>

        <a href="{{ route('user.schedule_result') }}" class="group">
            <div class="bg-white rounded-xl p-6 shadow hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 border border-green-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200 transition-colors duration-300">
                        <i class="fas fa-calendar-check text-green-600 text-xl"></i>
                    </div>
                    <i class="fas fa-arrow-right text-green-400 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></i>
                </div>
                <h3 class="font-semibold text-gray-800 mb-2">Schedule & Results</h3>
                <p class="text-sm text-gray-600">View match schedules and latest results</p>
            </div>
        </a>

        <a href="{{ route('user.statistics') }}" class="group">
            <div class="bg-white rounded-xl p-6 shadow hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 border border-purple-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center group-hover:bg-purple-200 transition-colors duration-300">
                        <i class="fas fa-chart-bar text-purple-600 text-xl"></i>
                    </div>
                    <i class="fas fa-arrow-right text-purple-400 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></i>
                </div>
                <h3 class="font-semibold text-gray-800 mb-2">Statistics</h3>
                <p class="text-sm text-gray-600">Team and player performance statistics</p>
            </div>
        </a>

        <a href="{{ route('user.media.gallery.photos') }}" class="group">
            <div class="bg-white rounded-xl p-6 shadow hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 border border-red-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center group-hover:bg-red-200 transition-colors duration-300">
                        <i class="fas fa-images text-red-600 text-xl"></i>
                    </div>
                    <i class="fas fa-arrow-right text-red-400 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></i>
                </div>
                <h3 class="font-semibold text-gray-800 mb-2">Media Gallery</h3>
                <p class="text-sm text-gray-600">Photos and videos from HSBL events</p>
            </div>
        </a>
    </div>

    {{-- About HSBL Section --}}
    <div class="bg-white rounded-xl shadow mb-8">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="font-semibold text-lg text-gray-800 flex items-center space-x-2">
                <i class="fas fa-landmark text-indigo-500"></i>
                <span>About HSBL</span>
            </h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <h3 class="font-semibold text-gray-800 text-lg mb-3">History</h3>
                    <div class="prose max-w-none">
                        <p class="text-gray-700 mb-3">
                            <strong>Riau Pos Honda Student Basketball League (HSBL)</strong> is a basketball and dance competition for high school students and equivalent levels in Riau Province, focusing on youth development and talent scouting.
                        </p>
                        <p class="text-gray-700">
                            First organized in 2008 under the name Student Basketball League (SBL) and renamed to Riau Pos Honda HSBL since 2010, this annual event was temporarily halted in 2019 due to the COVID-19 pandemic and resumed in 2024.
                        </p>
                    </div>
                </div>
                
                <div>
                    <h3 class="font-semibold text-gray-800 text-lg mb-3">Objectives</h3>
                    <ul class="space-y-2">
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                            <span class="text-gray-700">Develop basketball talents among high school students</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                            <span class="text-gray-700">Promote healthy competition and sportsmanship</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                            <span class="text-gray-700">Provide platform for youth development</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                            <span class="text-gray-700">Strengthen sports culture in Riau Province</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                            <span class="text-gray-700">Discover and nurture future basketball stars</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Links Section --}}
    <div class="bg-white rounded-xl shadow mb-8">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="font-semibold text-lg text-gray-800 flex items-center space-x-2">
                <i class="fas fa-link text-blue-500"></i>
                <span>Quick Links</span>
            </h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <a href="{{ route('user.media.gallery.videos') }}" class="flex items-center p-3 rounded-lg border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-colors duration-200">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-video text-blue-600"></i>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-800 text-sm">Video Gallery</h4>
                        <p class="text-xs text-gray-500">Watch highlights and live streams</p>
                    </div>
                </a>
                
                <a href="{{ route('user.media.about') }}" class="flex items-center p-3 rounded-lg border border-gray-200 hover:border-green-300 hover:bg-green-50 transition-colors duration-200">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-info-circle text-green-600"></i>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-800 text-sm">About Us</h4>
                        <p class="text-xs text-gray-500">Learn more about HSBL</p>
                    </div>
                </a>
                
                <a href="{{ route('user.media.developer') }}" class="flex items-center p-3 rounded-lg border border-gray-200 hover:border-purple-300 hover:bg-purple-50 transition-colors duration-200">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-code text-purple-600"></i>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-800 text-sm">Developer</h4>
                        <p class="text-xs text-gray-500">Meet the development team</p>
                    </div>
                </a>
                
                <a href="{{ route('form.team.choice') }}" class="flex items-center p-3 rounded-lg border border-gray-200 hover:border-yellow-300 hover:bg-yellow-50 transition-colors duration-200">
                    <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-user-plus text-yellow-600"></i>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-800 text-sm">Team Registration</h4>
                        <p class="text-xs text-gray-500">Register your team for HSBL</p>
                    </div>
                </a>
                
                <a href="{{ route('login.form') }}" class="flex items-center p-3 rounded-lg border border-gray-200 hover:border-red-300 hover:bg-red-50 transition-colors duration-200">
                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-sign-in-alt text-red-600"></i>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-800 text-sm">Login</h4>
                        <p class="text-xs text-gray-500">Access admin/student panel</p>
                    </div>
                </a>
                
                <a href="{{ route('user.download_terms') }}" class="flex items-center p-3 rounded-lg border border-gray-200 hover:border-indigo-300 hover:bg-indigo-50 transition-colors duration-200">
                    <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-file-download text-indigo-600"></i>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-800 text-sm">Terms & Conditions</h4>
                        <p class="text-xs text-gray-500">Download competition rules</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    {{-- Developer Team Section --}}
    <div class="bg-white rounded-xl shadow">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="font-semibold text-lg text-gray-800 flex items-center space-x-2">
                <i class="fas fa-users text-blue-500"></i>
                <span>Development Team</span>
            </h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Developer 1 --}}
                <div class="p-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-100">
                    <div class="flex flex-col md:flex-row items-start md:items-center gap-4">
                        <div class="flex-shrink-0">
                            <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-white shadow-lg">
                                <img src="{{ asset('images/Developer/Mutia Rizki.jpeg') }}" 
                                     alt="Mutia Rizkianti" 
                                     class="w-full h-full object-cover"
                                     onerror="this.src='https://ui-avatars.com/api/?name=Mutia+Rizkianti&background=3b82f6&color=fff&size=128'">
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-bold text-gray-800 text-lg mb-1">Mutia Rizkianti</h3>
                            <p class="text-sm text-gray-600 mb-3">Computer Science Graduate 2025</p>
                            <div class="mb-3">
                                <span class="inline-block bg-blue-600 text-white text-xs px-2 py-1 rounded-full font-medium">
                                    Full-Stack Developer
                                </span>
                            </div>
                            <p class="text-sm text-gray-700 mb-2">
                                Experienced as a programmer at Digital Innovation Hub research, HSBL website development, internship at Riau Pos Event Organizer Division, and freelance layout designer and editor.
                            </p>
                            <p class="text-sm text-gray-700">
                                Specializes in integrated system development with focus on structured architecture and clean UI design.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Developer 2 --}}
                <div class="p-6 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl border border-purple-100">
                    <div class="flex flex-col md:flex-row items-start md:items-center gap-4">
                        <div class="flex-shrink-0">
                            <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-white shadow-lg">
                                <img src="{{ asset('images/Developer/Wafiq WW.jpeg') }}" 
                                     alt="Wafiq Wardatul Khairani" 
                                     class="w-full h-full object-cover"
                                     onerror="this.src='https://ui-avatars.com/api/?name=Wafiq+Wardatul+Khairani&background=8b5cf6&color=fff&size=128'">
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-bold text-gray-800 text-lg mb-1">Wafiq Wardatul Khairani</h3>
                            <p class="text-sm text-gray-600 mb-3">Information Systems Graduate 2025</p>
                            <div class="mb-3">
                                <span class="inline-block bg-purple-600 text-white text-xs px-2 py-1 rounded-full font-medium">
                                    Full-Stack Developer
                                </span>
                            </div>
                            <p class="text-sm text-gray-700 mb-2">
                                Experienced in HSBL website development, programmer at Digital Innovation Hub research, and internship at Riau Pos Event Organizer Division.
                            </p>
                            <p class="text-sm text-gray-700">
                                Specializes in integrated backend and frontend development with comprehensive system architecture.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .animate-pulse-slow {
        animation: pulse 3s infinite;
    }

    @keyframes pulse {
        0%, 100% {
            opacity: 1;
            transform: scale(1);
        }
        50% {
            opacity: 0.9;
            transform: scale(1.05);
        }
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
</style>
@endsection