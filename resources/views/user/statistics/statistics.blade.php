@extends('user.layouts.app')

@section('title', 'SBL Statistics - Coming Soon')

@section('content')
<div class="w-full animate-fadeInUp">
    {{-- Hero Section --}}
    <div class="relative bg-gradient-to-r from-blue-600 via-blue-500 to-blue-400 rounded-2xl shadow-lg overflow-hidden mb-8">
        <div class="absolute inset-0 bg-grid-white/10 bg-grid-8"></div>
        <div class="relative px-8 py-12 md:py-16">
            <div class="max-w-4xl mx-auto text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 md:w-24 md:h-24 bg-white/20 backdrop-blur-sm rounded-full mb-6">
                    <i class="fas fa-chart-line text-white text-3xl md:text-4xl"></i>
                </div>
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">
                    Statistics & Analytics
                </h1>
                <p class="text-blue-100 text-lg md:text-xl mb-8">
                    Comprehensive data insights for Honda Student Basketball League
                </p>
                <div class="inline-flex items-center space-x-2 bg-white/20 backdrop-blur-sm px-6 py-3 rounded-full">
                    <i class="fas fa-clock text-blue-200 animate-pulse"></i>
                    <span class="text-white font-medium">Coming Soon</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="max-w-4xl mx-auto">
        {{-- Coming Soon Message --}}
        <div class="card text-center mb-8">
            <div class="mb-6">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                    <i class="fas fa-tools text-blue-500 text-2xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Page Under Development</h2>
                <p class="text-gray-600 mb-6">
                    We're currently working on creating a comprehensive statistics and analytics dashboard 
                    for the Honda Student Basketball League. This section will provide detailed insights 
                    into team performances, player statistics, match analytics, and more.
                </p>
                <div class="bg-blue-50 border border-blue-100 rounded-xl p-6 mb-6">
                    <h3 class="text-lg font-semibold text-blue-700 mb-3 flex items-center justify-center">
                        <i class="fas fa-lightbulb mr-2"></i> Planned Features
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-left">
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-check-circle text-green-500 mt-1"></i>
                            <div>
                                <div class="font-medium text-gray-800">Team Standings</div>
                                <div class="text-sm text-gray-600">Real-time rankings and win-loss records</div>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-check-circle text-green-500 mt-1"></i>
                            <div>
                                <div class="font-medium text-gray-800">Player Statistics</div>
                                <div class="text-sm text-gray-600">Individual performance metrics and rankings</div>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-check-circle text-green-500 mt-1"></i>
                            <div>
                                <div class="font-medium text-gray-800">Match Analytics</div>
                                <div class="text-sm text-gray-600">Detailed game statistics and trends</div>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-check-circle text-green-500 mt-1"></i>
                            <div>
                                <div class="font-medium text-gray-800">Season Overview</div>
                                <div class="text-sm text-gray-600">Complete season statistics and records</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Progress Status --}}
        <div class="card">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="text-center md:text-left">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Development Progress</h3>
                    <p class="text-gray-600 text-sm mb-4">We're working hard to bring you the best statistics experience</p>
                    <div class="flex items-center space-x-2 text-sm text-gray-500">
                        <i class="fas fa-code-branch"></i>
                        <span>Estimated launch: Q2 2024</span>
                    </div>
                </div>
                
                <div class="w-full md:w-64">
                    <div class="flex justify-between mb-1">
                        <span class="text-sm font-medium text-blue-700">Development Progress</span>
                        <span class="text-sm font-medium text-blue-700">65%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-blue-600 h-2.5 rounded-full w-3/4"></div>
                    </div>
                    <div class="mt-2 text-xs text-gray-500 text-center">
                        Database integration in progress
                    </div>
                </div>
            </div>
        </div>

        {{-- Temporary Statistics Links --}}
        <div class="mt-8 text-center">
            <p class="text-gray-600 mb-4">In the meantime, you can check:</p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('user.schedule_result') }}" 
                   class="inline-flex items-center space-x-2 px-4 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg transition-colors duration-200">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Schedules & Results</span>
                </a>
                <a href="{{ route('user.news.index') }}" 
                   class="inline-flex items-center space-x-2 px-4 py-2 bg-green-100 hover:bg-green-200 text-green-700 rounded-lg transition-colors duration-200">
                    <i class="fas fa-newspaper"></i>
                    <span>Latest News</span>
                </a>
                {{-- PERBAIKAN: Ganti route('user.videos') dengan route('user.videos') --}}
                <a href="{{ route('user.videos') }}" 
                   class="inline-flex items-center space-x-2 px-4 py-2 bg-purple-100 hover:bg-purple-200 text-purple-700 rounded-lg transition-colors duration-200">
                    <i class="fas fa-video"></i>
                    <span>Match Videos</span>
                </a>
            </div>
        </div>

        {{-- Contact Section --}}
        <div class="mt-12 text-center">
            <div class="bg-gradient-to-r from-gray-50 to-white rounded-xl p-8 border border-gray-200">
                <i class="fas fa-envelope-open-text text-3xl text-blue-500 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Stay Updated</h3>
                <p class="text-gray-600 mb-6 max-w-2xl mx-auto">
                    Want to be notified when the statistics section launches? 
                    Follow our social media channels for the latest updates and announcements.
                </p>
                <div class="flex justify-center space-x-4">
                    <a href="#" class="p-3 bg-blue-100 hover:bg-blue-200 text-blue-600 rounded-full transition-colors duration-200">
                        <i class="fab fa-instagram text-lg"></i>
                    </a>
                    <a href="#" class="p-3 bg-blue-100 hover:bg-blue-200 text-blue-600 rounded-full transition-colors duration-200">
                        <i class="fab fa-facebook-f text-lg"></i>
                    </a>
                    <a href="#" class="p-3 bg-blue-100 hover:bg-blue-200 text-blue-600 rounded-full transition-colors duration-200">
                        <i class="fab fa-twitter text-lg"></i>
                    </a>
                    <a href="#" class="p-3 bg-blue-100 hover:bg-blue-200 text-blue-600 rounded-full transition-colors duration-200">
                        <i class="fab fa-youtube text-lg"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    .floating-icon {
        animation: float 3s ease-in-out infinite;
    }
</style>
@endsection