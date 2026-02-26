@extends('user.layouts.app')

@section('title', 'About - Riau Pos Honda SBL')

@section('styles')
<style>
    .about-hero {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        border-radius: 1.5rem;
        overflow: hidden;
        position: relative;
        box-shadow: 0 10px 25px rgba(59, 130, 246, 0.15);
        min-height: 350px;
        display: flex;
        align-items: center;
        border: 1px solid #93c5fd;
    }
    
    .about-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
            radial-gradient(circle at 20% 80%, rgba(59, 130, 246, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(96, 165, 250, 0.1) 0%, transparent 50%);
        z-index: 1;
    }
    
    .about-hero::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(
            45deg,
            transparent 30%,
            rgba(255, 255, 255, 0.3) 50%,
            transparent 70%
        );
        animation: shine 3s infinite linear;
        z-index: 1;
    }
    
    .hero-content {
        position: relative;
        z-index: 2;
        width: 100%;
    }
    
    .hero-icon-container {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(10px);
        border: 2px solid rgba(59, 130, 246, 0.2);
        border-radius: 1.5rem;
        padding: 2rem;
        transition: all 0.4s ease;
        box-shadow: 0 8px 32px rgba(59, 130, 246, 0.1);
    }
    
    .hero-icon-container:hover {
        background: rgba(255, 255, 255, 0.95);
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(59, 130, 246, 0.2);
        border-color: rgba(59, 130, 246, 0.4);
    }
    
    .hero-badge {
        background: rgba(59, 130, 246, 0.1);
        border: 1px solid rgba(59, 130, 246, 0.3);
        color: #1e40af;
        transition: all 0.3s ease;
    }
    
    .hero-badge:hover {
        background: rgba(59, 130, 246, 0.2);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
    }
    
    @keyframes shine {
        0% {
            transform: translateX(-100%) translateY(-100%) rotate(45deg);
        }
        100% {
            transform: translateX(100%) translateY(100%) rotate(45deg);
        }
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .about-hero {
            min-height: 300px;
            padding: 2rem 1rem !important;
        }
        
        .hero-icon-container {
            padding: 1.5rem;
            margin-top: 1rem;
        }
    }
    
    /* Sisa style tetap sama */
    .icon-card {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 1rem;
        padding: 2rem;
        transition: all 0.3s ease;
        border: 1px solid #e2e8f0;
    }
    
    .icon-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(59, 130, 246, 0.15);
        border-color: #3b82f6;
    }
    
    .timeline {
        position: relative;
        padding-left: 2rem;
    }
    
    .timeline::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 2px;
        background: linear-gradient(to bottom, #3b82f6, #60a5fa, #93c5fd);
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 2rem;
    }
    
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -2.2rem;
        top: 0.5rem;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #3b82f6;
        border: 3px solid white;
        box-shadow: 0 0 0 3px #dbeafe;
    }
    
    .feature-list li {
        position: relative;
        padding-left: 1.75rem;
        margin-bottom: 0.75rem;
    }
    
    .feature-list li::before {
        content: '✓';
        position: absolute;
        left: 0;
        color: #10b981;
        font-weight: bold;
    }
    
    .stat-card {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        text-align: center;
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }
    
    .bounce {
        animation: bounce 2s infinite;
    }
    
    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
    }
    
    .pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.8; }
    }
    
    .float-animation {
        animation: float 3s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
</style>
@endsection

@section('content')
<div class="max-w-6xl mx-auto">
    {{-- Hero Section dengan Latar Belakang Biru Muda --}}
    <div class="about-hero text-gray-800 mb-8 p-8 md:p-12">
        <div class="hero-content">
            <div class="flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="md:w-2/3">
                    <div class="mb-6">
                        <div class="inline-flex items-center gap-2 bg-white/80 backdrop-blur-sm rounded-full px-4 py-2 mb-4 border border-blue-200">
                            <i class="fas fa-star text-blue-500"></i>
                            <span class="text-sm font-medium text-blue-700">Tentang Kami</span>
                        </div>
                        <h1 class="text-4xl md:text-5xl font-bold mb-4 flex items-center gap-3 text-gray-800">
                            <span class="bg-white/90 p-3 rounded-xl shadow-sm">
                                <i class="fas fa-info-circle text-2xl text-blue-500 bounce"></i>
                            </span>
                            <span class="leading-tight">Riau Pos Honda<br><span class="text-blue-600">Student Basketball League</span></span>
                        </h1>
                        <p class="text-xl md:text-2xl text-gray-700 leading-relaxed mb-6">
                            Kompetisi bola basket dan dance tingkat pelajar SMA dan sederajat di Provinsi Riau yang menjadi wadah pembinaan dan pencarian bakat muda.
                        </p>
                    </div>
                    
                    <div class="flex flex-wrap gap-3">
                        <span class="hero-badge text-sm px-4 py-2 rounded-full font-medium inline-flex items-center gap-2">
                            <i class="fas fa-basketball-ball text-blue-600"></i> Basket Competition
                        </span>
                        <span class="hero-badge text-sm px-4 py-2 rounded-full font-medium inline-flex items-center gap-2">
                            <i class="fas fa-music text-blue-600"></i> Dance Competition
                        </span>
                        <span class="hero-badge text-sm px-4 py-2 rounded-full font-medium inline-flex items-center gap-2">
                            <i class="fas fa-school text-blue-600"></i> High School Level
                        </span>
                        <span class="hero-badge text-sm px-4 py-2 rounded-full font-medium inline-flex items-center gap-2">
                            <i class="fas fa-map-marker-alt text-blue-600"></i> Riau Province
                        </span>
                    </div>
                </div>
                
                <div class="md:w-1/3 flex justify-center">
                    <div class="hero-icon-container text-center float-animation">
                        <div class="relative">
                            <div class="absolute inset-0 bg-gradient-to-r from-blue-300 to-blue-400 rounded-full blur-xl opacity-50"></div>
                            <i class="fas fa-basketball-ball text-7xl md:text-8xl text-blue-500 relative z-10"></i>
                        </div>
                        <div class="mt-6">
                            <div class="text-3xl font-bold text-blue-600 mb-1">16+</div>
                            <p class="text-gray-700 font-medium">Tahun Pengalaman</p>
                            <p class="text-xs text-gray-500 mt-1">Membangun Generasi Basket</p>
                        </div>
                        <div class="mt-4 pt-4 border-t border-blue-200">
                            <p class="text-sm text-gray-600">
                                <i class="fas fa-calendar-alt mr-1 text-blue-500"></i>
                                Est. 2008
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Sisa konten tetap sama --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Left Column - Timeline --}}
        <div class="lg:col-span-2">
            {{-- Introduction --}}
            <div class="card mb-8">
                <div class="card-header flex items-center gap-2">
                    <i class="fas fa-book-open text-blue-500"></i>
                    <h2 class="font-bold text-lg text-gray-800">Tentang Kompetisi</h2>
                </div>
                <div class="space-y-4 text-gray-600 leading-relaxed">
                    <p>
                        Riau Pos Honda Student Basketball League (HSBL) merupakan kompetisi bola basket dan dance tingkat pelajar SMA dan sederajat di Provinsi Riau. Ajang ini dirancang sebagai wadah pembinaan dan pencarian bakat muda di bidang olahraga basket dan dance, sekaligus menjadi agenda tahunan yang dinantikan oleh pelajar serta komunitas pencinta olahraga di daerah tersebut.
                    </p>
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg">
                        <p class="text-sm text-gray-700 italic">
                            <i class="fas fa-quote-left text-blue-400 mr-2"></i>
                            HSBL bukan hanya tentang kompetisi, tetapi juga tentang membangun karakter, sportivitas, dan persahabatan antar pelajar se-Provinsi Riau.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Timeline Section --}}
            <div class="card mb-8">
                <div class="card-header flex items-center gap-2">
                    <i class="fas fa-history text-blue-500"></i>
                    <h2 class="font-bold text-lg text-gray-800">Sejarah Perjalanan HSBL</h2>
                </div>
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg">
                            <h3 class="font-bold text-gray-800 mb-1 flex items-center gap-2">
                                <span class="bg-blue-500 text-white text-xs px-2 py-1 rounded">2008</span>
                                <span>Awal Mula</span>
                            </h3>
                            <p class="text-gray-600 text-sm">
                                Kompetisi pertama kali digelar dengan nama <strong>Student Basketball League (SBL)</strong>, menandai dimulainya tradisi basket pelajar di Riau.
                            </p>
                        </div>
                    </div>
                    
                    <div class="timeline-item">
                        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg">
                            <h3 class="font-bold text-gray-800 mb-1 flex items-center gap-2">
                                <span class="bg-blue-500 text-white text-xs px-2 py-1 rounded">2010</span>
                                <span>Transformasi</span>
                            </h3>
                            <p class="text-gray-600 text-sm">
                                Dengan kerja sama Honda, kompetisi resmi berganti nama menjadi <strong>Riau Pos Honda HSBL</strong>, meningkatkan skala dan kualitas penyelenggaraan.
                            </p>
                        </div>
                    </div>
                    
                    <div class="timeline-item">
                        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg">
                            <h3 class="font-bold text-gray-800 mb-1 flex items-center gap-2">
                                <span class="bg-blue-500 text-white text-xs px-2 py-1 rounded">2019</span>
                                <span>Masa Jeda</span>
                            </h3>
                            <p class="text-gray-600 text-sm">
                                Penyelenggaraan event terhenti akibat pandemi COVID-19, menciptakan vakum dalam kompetisi basket pelajar Riau.
                            </p>
                        </div>
                    </div>
                    
                    <div class="timeline-item">
                        <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg">
                            <h3 class="font-bold text-gray-800 mb-1 flex items-center gap-2">
                                <span class="bg-green-500 text-white text-xs px-2 py-1 rounded">2024</span>
                                <span>Kebangkitan Kembali</span>
                            </h3>
                            <p class="text-gray-600 text-sm">
                                <strong>HSBL kembali diselenggarakan</strong> setelah jeda beberapa tahun, menandai kebangkitan kembali kompetisi bola basket pelajar bergengsi di Provinsi Riau.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Website Purpose --}}
            <div class="card">
                <div class="card-header flex items-center gap-2">
                    <i class="fas fa-bullseye text-blue-500"></i>
                    <h2 class="font-bold text-lg text-gray-800">Tujuan Pengembangan Website</h2>
                </div>
                <div class="space-y-4">
                    <p class="text-gray-600 leading-relaxed">
                        Sebagai event berskala besar yang melibatkan banyak sekolah, peserta, dan pemangku kepentingan, pengembangan sistem informasi berbasis web menjadi kebutuhan strategis untuk mendukung operasional dan publikasi HSBL.
                    </p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                        <div class="icon-card">
                            <div class="flex items-start gap-3">
                                <div class="bg-blue-100 text-blue-600 p-2 rounded-lg">
                                    <i class="fas fa-calendar-alt text-lg"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800 mb-2">Informasi Kompetisi</h4>
                                    <p class="text-sm text-gray-600">
                                        Menyediakan jadwal pertandingan, hasil pertandingan, dan klasemen tim secara real-time.
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="icon-card">
                            <div class="flex items-start gap-3">
                                <div class="bg-green-100 text-green-600 p-2 rounded-lg">
                                    <i class="fas fa-newspaper text-lg"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800 mb-2">Penyebaran Berita</h4>
                                    <p class="text-sm text-gray-600">
                                        Menyebarkan berita terbaru seputar event, liputan pertandingan, dan wawancara eksklusif.
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="icon-card">
                            <div class="flex items-start gap-3">
                                <div class="bg-purple-100 text-purple-600 p-2 rounded-lg">
                                    <i class="fas fa-file-alt text-lg"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800 mb-2">Pendaftaran Online</h4>
                                    <p class="text-sm text-gray-600">
                                        Mempermudah proses pendaftaran dan administrasi bagi peserta, tim, dan sekolah.
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="icon-card">
                            <div class="flex items-start gap-3">
                                <div class="bg-yellow-100 text-yellow-600 p-2 rounded-lg">
                                    <i class="fas fa-images text-lg"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800 mb-2">Galeri Media</h4>
                                    <p class="text-sm text-gray-600">
                                        Menyediakan galeri foto dan video dari pertandingan serta momen penting event.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column - Stats & Info --}}
        <div class="space-y-8">
            {{-- Key Features --}}
            <div class="card">
                <div class="card-header flex items-center gap-2">
                    <i class="fas fa-star text-blue-500"></i>
                    <h2 class="font-bold text-lg text-gray-800">Fitur Utama Website</h2>
                </div>
                <ul class="feature-list space-y-2">
                    <li class="text-gray-700">Informasi kompetisi lengkap dan real-time</li>
                    <li class="text-gray-700">Berita dan update terbaru seputar HSBL</li>
                    <li class="text-gray-700">Sistem pendaftaran online terintegrasi</li>
                    <li class="text-gray-700">Galeri foto dan video pertandingan</li>
                    <li class="text-gray-700">Statistik tim dan pemain</li>
                    <li class="text-gray-700">Jadwal pertandingan interaktif</li>
                    <li class="text-gray-700">Live scoring dan hasil pertandingan</li>
                    <li class="text-gray-700">Informasi sponsor dan partner</li>
                </ul>
            </div>

            {{-- Quick Stats --}}
            <div class="card">
                <div class="card-header flex items-center gap-2">
                    <i class="fas fa-chart-line text-blue-500"></i>
                    <h2 class="font-bold text-lg text-gray-800">Fakta Singkat</h2>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="stat-card">
                        <div class="text-3xl font-bold text-blue-600 mb-1 pulse">16+</div>
                        <div class="text-sm text-gray-600">Tahun Legacy</div>
                        <div class="text-xs text-gray-500 mt-1">sejak 2008</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="text-3xl font-bold text-blue-600 mb-1 pulse">2</div>
                        <div class="text-sm text-gray-600">Bidang Kompetisi</div>
                        <div class="text-xs text-gray-500 mt-1">Basket & Dance</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="text-3xl font-bold text-blue-600 mb-1 pulse">100+</div>
                        <div class="text-sm text-gray-600">Sekolah</div>
                        <div class="text-xs text-gray-500 mt-1">terlibat</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="text-3xl font-bold text-blue-600 mb-1 pulse">2024</div>
                        <div class="text-sm text-gray-600">Kebangkitan</div>
                        <div class="text-xs text-gray-500 mt-1">setelah pandemi</div>
                    </div>
                </div>
            </div>

            {{-- Contact/Info --}}
            <div class="card bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200">
                <div class="card-header flex items-center gap-2">
                    <i class="fas fa-handshake text-blue-500"></i>
                    <h2 class="font-bold text-lg text-gray-800">Kolaborasi & Dukungan</h2>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="bg-white p-2 rounded-lg">
                            <i class="fas fa-newspaper text-blue-600"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-800">Riau Pos</h4>
                            <p class="text-sm text-gray-600">Media Partner Utama</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <div class="bg-white p-2 rounded-lg">
                            <i class="fas fa-car text-blue-600"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-800">Honda</h4>
                            <p class="text-sm text-gray-600">Sponsor Utama</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <div class="bg-white p-2 rounded-lg">
                            <i class="fas fa-graduation-cap text-blue-600"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-800">SMA/Sederajat</h4>
                            <p class="text-sm text-gray-600">Seluruh Provinsi Riau</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4 pt-4 border-t border-blue-200">
                    <p class="text-sm text-gray-600 text-center">
                        <i class="fas fa-basketball-ball text-blue-500 mr-1"></i>
                        Membangun Generasi Basket Berprestasi
                    </p>
                </div>
            </div>

            {{-- CTA --}}
            <div class="text-center">
                <a href="{{ url('user/dashboard') }}" 
                   class="btn-primary inline-flex items-center gap-2">
                    <i class="fas fa-home"></i>
                    Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>

    {{-- Mission Statement --}}
    <div class="mt-12 card bg-gradient-to-r from-blue-100 to-blue-200 border border-blue-300 text-gray-800">
        <div class="text-center p-8">
            <i class="fas fa-quote-left text-4xl text-blue-400 mb-4"></i>
            <p class="text-xl md:text-2xl font-medium mb-6 leading-relaxed text-gray-800">
                "HSBL bukan sekadar kompetisi, tapi wadah pembinaan karakter, sportivitas, dan pencarian bakat muda basket Riau untuk prestasi nasional."
            </p>
            <div class="flex items-center justify-center gap-3 text-blue-600">
                <div class="h-px w-12 bg-blue-400"></div>
                <i class="fas fa-bullseye"></i>
                <span class="text-sm font-medium">Misi Riau Pos Honda HSBL</span>
                <div class="h-px w-12 bg-blue-400"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Animasi saat scroll
    document.addEventListener('DOMContentLoaded', function() {
        // Add animation to stats cards
        const stats = document.querySelectorAll('.stat-card');
        stats.forEach((stat, index) => {
            stat.style.animationDelay = `${index * 0.1}s`;
        });
        
        // Smooth reveal for timeline items
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, { threshold: 0.1 });
        
        // Observe timeline items
        document.querySelectorAll('.timeline-item').forEach(item => {
            item.style.opacity = '0';
            item.style.transform = 'translateY(20px)';
            item.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            observer.observe(item);
        });
        
        // Observe icon cards
        document.querySelectorAll('.icon-card').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            observer.observe(card);
        });
    });
</script>
@endsection