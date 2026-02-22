@extends('user.layouts.app')

@section('title', 'Developer Team - HSBL Riau Pos')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-8 md:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Hero Section --}}
        <div class="text-center mb-12 animate-fadeInUp">
            <div class="inline-flex items-center justify-center p-3 bg-gradient-to-r from-blue-500 to-purple-500 rounded-2xl mb-6 shadow-lg">
                <i class="fas fa-code text-3xl text-white"></i>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-purple-600 mb-4">
                Developer Team
            </h1>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                The brilliant minds behind HSBL Riau Pos platform. Combining technical expertise with creative vision to deliver exceptional digital experiences.
            </p>
            <div class="mt-6 flex items-center justify-center space-x-4">
                <div class="flex items-center text-sm text-gray-500">
                    <i class="fas fa-graduation-cap text-blue-500 mr-2"></i>
                    <span>Universitas Riau - Ilmu Komputer</span>
                </div>
                <div class="flex items-center text-sm text-gray-500">
                    <i class="fas fa-bolt text-yellow-500 mr-2"></i>
                    <span>Full-Stack Development</span>
                </div>
                <div class="flex items-center text-sm text-gray-500">
                    <i class="fas fa-palette text-purple-500 mr-2"></i>
                    <span>UI/UX Design</span>
                </div>
            </div>
        </div>

        {{-- Tech Stack Banner --}}
        <div class="mb-12 bg-white rounded-2xl p-6 shadow-lg border border-gray-100 animate-fadeInUp">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center">
                    <i class="fas fa-cogs text-2xl text-blue-500 mr-3"></i>
                    <div>
                        <h3 class="font-bold text-gray-800">Tech Stack</h3>
                        <p class="text-sm text-gray-600">Modern technologies powering HSBL</p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-3">
                    <span class="px-3 py-1.5 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">Laravel</span>
                    <span class="px-3 py-1.5 bg-yellow-100 text-yellow-700 rounded-full text-sm font-medium">JavaScript</span>
                    <span class="px-3 py-1.5 bg-green-100 text-green-700 rounded-full text-sm font-medium">Tailwind CSS</span>
                    <span class="px-3 py-1.5 bg-red-100 text-red-700 rounded-full text-sm font-medium">MySQL</span>
                    <span class="px-3 py-1.5 bg-purple-100 text-purple-700 rounded-full text-sm font-medium">PHP</span>
                    <span class="px-3 py-1.5 bg-pink-100 text-pink-700 rounded-full text-sm font-medium">Git</span>
                </div>
            </div>
        </div>

        {{-- Developers Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-16">
            {{-- Developer 1: Mutia Rizkianti, S.Kom --}}
            <div class="bg-white rounded-2xl overflow-hidden shadow-xl transform transition-all duration-300 hover:-translate-y-2 animate-fadeInUp">
                <div class="relative h-48 bg-gradient-to-r from-blue-500 to-cyan-400">
                    <div class="absolute inset-0 bg-black bg-opacity-20"></div>
                    <div class="absolute bottom-4 left-6">
                        <div class="flex items-end">
                            <div class="w-32 h-32 rounded-full border-4 border-white overflow-hidden bg-white shadow-xl">
                                <img 
                                    src="{{ asset('images/Developer/Mutia Rizki.jpeg') }}" 
                                    alt="Mutia Rizkianti, S.Kom"
                                    class="w-full h-full object-cover"
                                    onerror="this.src='https://ui-avatars.com/api/?name=Mutia+Rizkianti&background=3b82f6&color=fff&size=128'"
                                >
                            </div>
                            <div class="ml-4 mb-2">
                                <div class="inline-flex items-center px-3 py-1 bg-gradient-to-r from-blue-600 to-purple-600 rounded-full text-white text-xs font-bold mb-2">
                                    <i class="fas fa-code mr-1"></i>
                                    <span>INTJ</span>
                                </div>
                                <h3 class="text-2xl font-bold text-white">Mutia Rizkianti, S.Kom</h3>
                                <p class="text-blue-100 font-medium">Full-Stack Developer</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="p-6 pt-20">
                    {{-- Education & Quick Stats --}}
                    <div class="mb-6">
                        <div class="flex items-center text-sm text-gray-600 mb-3">
                            <i class="fas fa-university text-blue-500 mr-2"></i>
                            <span>Universitas Riau - Ilmu Komputer (2025)</span>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-center p-3 bg-blue-50 rounded-lg">
                                <div class="text-blue-600 font-bold text-xl">Full</div>
                                <div class="text-xs text-gray-600">Stack</div>
                            </div>
                            <div class="text-center p-3 bg-purple-50 rounded-lg">
                                <div class="text-purple-600 font-bold text-xl">5+</div>
                                <div class="text-xs text-gray-600">Certifications</div>
                            </div>
                            <div class="text-center p-3 bg-green-50 rounded-lg">
                                <div class="text-green-600 font-bold text-xl">UI/UX</div>
                                <div class="text-xs text-gray-600">Design</div>
                            </div>
                        </div>
                    </div>

                    {{-- Bio --}}
                    <div class="mb-6">
                        <h4 class="font-bold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-user-circle text-blue-500 mr-2"></i>
                            Personality & Work Style
                        </h4>
                        <div class="space-y-3">
                            <p class="text-gray-700 leading-relaxed">
                                Mutia Rizkianti, S.Kom adalah lulusan Ilmu Komputer Universitas Riau tahun 2025 yang lahir di Melai, 3 April 2004, dan berperan sebagai Full-Stack Developer. Secara tampilan memang kalem dan terkesan "aku aman, semua terkendali", khas INTJ. Tapi jangan terkecoh.
                            </p>
                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-3 rounded-r">
                                <p class="text-sm text-gray-700">
                                    <i class="fas fa-exclamation-circle text-yellow-500 mr-2"></i>
                                    <span class="font-medium">Di balik ketenangan:</span> Di balik ketenangan itu, ia bisa mendadak panik saat error muncul tanpa aba-aba, terutama kalau kejadiannya pas deadline sudah senyum-senyum di depan mata.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Strategic Thinker Section --}}
                    <div class="mb-6 bg-gradient-to-r from-gray-50 to-blue-50 p-4 rounded-xl border border-gray-200">
                        <h4 class="font-bold text-gray-800 mb-2 flex items-center">
                            <i class="fas fa-brain text-blue-500 mr-2"></i>
                            Strategic Thinker & Visual Designer
                        </h4>
                        <p class="text-sm text-gray-700">
                            Sebagai fresh graduate, Mutia, S.Kom memiliki kemampuan unik dalam menggabungkan logika sistem dengan estetika visual. 
                            Mulut bilang "ini bentar doang", tapi isi kepala sudah lari ke mana-mana mencari solusi optimal. 
                            Untungnya, setelah fase panik selesai, biasanya solusi ketemu juga dengan pendekatan yang terstruktur.
                        </p>
                    </div>

                    {{-- Experience --}}
                    <div class="mb-6">
                        <h4 class="font-bold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-briefcase text-purple-500 mr-2"></i>
                            Experience & Expertise
                        </h4>
                        <ul class="space-y-2">
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-500 mt-1 mr-2 text-sm"></i>
                                <span class="text-gray-700">Full-Stack Developer for Honda Student Basketball League website</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-500 mt-1 mr-2 text-sm"></i>
                                <span class="text-gray-700">Programmer in Digital Innovation Hub research team</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-500 mt-1 mr-2 text-sm"></i>
                                <span class="text-gray-700">Internship at Riau Pos Event Organizer Division</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-500 mt-1 mr-2 text-sm"></i>
                                <span class="text-gray-700">Freelance layout designer & editor for publishers</span>
                            </li>
                        </ul>
                    </div>

                    {{-- Certifications --}}
                    <div class="mb-6">
                        <h4 class="font-bold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-certificate text-green-500 mr-2"></i>
                            Certifications
                        </h4>
                        <div class="flex flex-wrap gap-2">
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">Java Fundamental</span>
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">Java Programming</span>
                            <span class="px-3 py-1 bg-orange-100 text-orange-700 rounded-full text-xs font-medium">Oracle Cloud Infrastructure</span>
                            <span class="px-3 py-1 bg-orange-100 text-orange-700 rounded-full text-xs font-medium">Oracle Cloud Database</span>
                            <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-medium">Cyber Security</span>
                        </div>
                    </div>

                    {{-- Contact & Social Media --}}
                    <div class="pt-4 border-t border-gray-100 space-y-4">
                        {{-- Email --}}
                        <div class="flex items-center">
                            <i class="fas fa-envelope text-gray-400 mr-3 w-5"></i>
                            <a href="mailto:mutiarizkianti04@gmail.com" class="text-sm text-gray-700 hover:text-blue-600 transition-colors">
                                mutiarizkianti04@gmail.com
                            </a>
                        </div>
                        
                        {{-- Instagram --}}
                        <div class="flex items-center">
                            <i class="fab fa-instagram text-gray-400 mr-3 w-5"></i>
                            <a href="https://www.instagram.com/mutia.rizkii_?igsh=MXNmeW1idG12YjJldA==" 
                               target="_blank" 
                               class="text-sm text-gray-700 hover:text-pink-600 transition-colors">
                                @mutia.rizkii_
                            </a>
                        </div>
                        
                        {{-- LinkedIn --}}
                        <div class="flex items-center">
                            <i class="fab fa-linkedin-in text-gray-400 mr-3 w-5"></i>
                            <a href="https://www.linkedin.com/in/mutia-rizkianti-ruslan-3978532b0?utm_source=share_via&utm_content=profile&utm_medium=member_android" 
                               target="_blank"
                               class="text-sm text-gray-700 hover:text-blue-700 transition-colors">
                                Mutia Rizkianti Ruslan
                            </a>
                        </div>
                        
                        {{-- Hobbies (Separate Section) --}}
                        <div class="pt-2">
                            <div class="flex items-center">
                                <span class="text-sm font-medium text-gray-700 mr-3">Hobbies:</span>
                                <div class="flex flex-wrap gap-1">
                                    <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs">Design</span>
                                    <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs">Movies</span>
                                    <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs">Culinary</span>
                                    <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs">Writing</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Developer 2: Wafiq Wardatul Khairani, S.Kom --}}
            <div class="bg-white rounded-2xl overflow-hidden shadow-xl transform transition-all duration-300 hover:-translate-y-2 animate-fadeInUp" style="animation-delay: 0.1s">
                <div class="relative h-48 bg-gradient-to-r from-purple-500 to-pink-400">
                    <div class="absolute inset-0 bg-black bg-opacity-20"></div>
                    <div class="absolute bottom-4 left-6">
                        <div class="flex items-end">
                            <div class="w-32 h-32 rounded-full border-4 border-white overflow-hidden bg-white shadow-xl">
                                <img 
                                    src="{{ asset('images/Developer/Wafiq WW.jpeg') }}" 
                                    alt="Wafiq Wardatul Khairani, S.Kom"
                                    class="w-full h-full object-cover"
                                    onerror="this.src='https://ui-avatars.com/api/?name=Wafiq+Khairani&background=8b5cf6&color=fff&size=128'"
                                >
                            </div>
                            <div class="ml-4 mb-2">
                                <div class="inline-flex items-center px-3 py-1 bg-gradient-to-r from-purple-600 to-pink-600 rounded-full text-white text-xs font-bold mb-2">
                                    <i class="fas fa-brain mr-1"></i>
                                    <span>INFJ</span>
                                </div>
                                <h3 class="text-2xl font-bold text-white">Wafiq Wardatul Khairani, S.Kom</h3>
                                <p class="text-purple-100 font-medium">Full-Stack Developer</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="p-6 pt-20">
                    {{-- Education & Quick Stats --}}
                    <div class="mb-6">
                        <div class="flex items-center text-sm text-gray-600 mb-3">
                            <i class="fas fa-university text-purple-500 mr-2"></i>
                            <span>Universitas Riau - Ilmu Komputer (2025)</span>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-center p-3 bg-purple-50 rounded-lg">
                                <div class="text-purple-600 font-bold text-xl">Full</div>
                                <div class="text-xs text-gray-600">Stack</div>
                            </div>
                            <div class="text-center p-3 bg-pink-50 rounded-lg">
                                <div class="text-pink-600 font-bold text-xl">4+</div>
                                <div class="text-xs text-gray-600">Certifications</div>
                            </div>
                            <div class="text-center p-3 bg-indigo-50 rounded-lg">
                                <div class="text-indigo-600 font-bold text-xl">UI/UX</div>
                                <div class="text-xs text-gray-600">Design</div>
                            </div>
                        </div>
                    </div>

                    {{-- Bio --}}
                    <div class="mb-6">
                        <h4 class="font-bold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-user-circle text-purple-500 mr-2"></i>
                            Personality & Work Style
                        </h4>
                        <div class="space-y-3">
                            <p class="text-gray-700 leading-relaxed">
                                Wafiq Wardatul Khairani, S.Kom adalah lulusan Ilmu Komputer Universitas Riau tahun 2025 asal Pekanbaru yang berperan sebagai Full-Stack Developer. Gayanya terkesan cuek, bicara seperlunya, tapi begitu sudah pegang kode, fokusnya galak dan serius.
                            </p>
                            <div class="bg-blue-50 border-l-4 border-blue-400 p-3 rounded-r">
                                <p class="text-sm text-gray-700">
                                    <i class="fas fa-search text-blue-500 mr-2"></i>
                                    <span class="font-medium">Problem-Solver Approach:</span> Ia terbiasa mengerjakan sistem dari ujung backend sampai frontend tanpa banyak drama. Kalau ada sistem bermasalah, Wafiq, S.Kom bukan banyak tanya, tapi langsung cari akar masalah.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Strategic Thinker Section --}}
                    <div class="mb-6 bg-gradient-to-r from-gray-50 to-purple-50 p-4 rounded-xl border border-gray-200">
                        <h4 class="font-bold text-gray-800 mb-2 flex items-center">
                            <i class="fas fa-eye text-indigo-500 mr-2"></i>
                            Observant & Systematic Problem-Solver
                        </h4>
                        <p class="text-sm text-gray-700">
                            Di luar dunia teknis, Wafiq, S.Kom adalah tipe INFJ yang kelihatannya dingin tapi sebenarnya observatif dan penuh pertimbangan. 
                            Ia menikmati waktu sendiri dengan mendengarkan lagu, menonton film, atau masak sambil mikir hidup (dan bug). 
                            Dibekali pola kerja yang terstruktur, ia punya fondasi teknis yang rapi untuk menangani kompleksitas sistem.
                        </p>
                    </div>

                    {{-- Experience --}}
                    <div class="mb-6">
                        <h4 class="font-bold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-briefcase text-pink-500 mr-2"></i>
                            Experience & Expertise
                        </h4>
                        <ul class="space-y-2">
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-500 mt-1 mr-2 text-sm"></i>
                                <span class="text-gray-700">Full-Stack Developer for Honda Student Basketball League website</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-500 mt-1 mr-2 text-sm"></i>
                                <span class="text-gray-700">Programmer in Digital Innovation Hub research team</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-500 mt-1 mr-2 text-sm"></i>
                                <span class="text-gray-700">Internship at Riau Pos Event Organizer Division</span>
                            </li>
                        </ul>
                        <p class="mt-2 text-sm text-gray-600 italic">
                            Singkatnya, kalau ada sistem bermasalah, Wafiq, S.Kom bukan banyak tanya, tapi langsung cari akar masalah.
                        </p>
                    </div>

                    {{-- Certifications --}}
                    <div class="mb-6">
                        <h4 class="font-bold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-certificate text-indigo-500 mr-2"></i>
                            Certifications
                        </h4>
                        <div class="flex flex-wrap gap-2">
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">Java Fundamental</span>
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">Java Programming</span>
                            <span class="px-3 py-1 bg-orange-100 text-orange-700 rounded-full text-xs font-medium">Oracle Cloud Infrastructure</span>
                            <span class="px-3 py-1 bg-orange-100 text-orange-700 rounded-full text-xs font-medium">Oracle Cloud Database</span>
                        </div>
                    </div>

                    {{-- Contact & Social Media --}}
                    <div class="pt-4 border-t border-gray-100 space-y-4">
                        {{-- Email --}}
                        <div class="flex items-center">
                            <i class="fas fa-envelope text-gray-400 mr-3 w-5"></i>
                            <a href="mailto:wafiqwardatulkhairani22@gmail.com" class="text-sm text-gray-700 hover:text-purple-600 transition-colors">
                                wafiqwardatulkhairani22@gmail.com
                            </a>
                        </div>
                        
                        {{-- Instagram --}}
                        <div class="flex items-center">
                            <i class="fab fa-instagram text-gray-400 mr-3 w-5"></i>
                            <a href="https://www.instagram.com/wfiqwrdtl_?igsh=dHBiZHN6eTJwbTFn" 
                               target="_blank" 
                               class="text-sm text-gray-700 hover:text-pink-600 transition-colors">
                                @wfiqwrdtl_
                            </a>
                        </div>
                        
                        {{-- LinkedIn --}}
                        <div class="flex items-center">
                            <i class="fab fa-linkedin-in text-gray-400 mr-3 w-5"></i>
                            <a href="https://www.linkedin.com/in/wafiq-wardatul-khairani-a382873b1/" 
                               target="_blank"
                               class="text-sm text-gray-700 hover:text-blue-700 transition-colors">
                                Wafiq Wardatul Khairani
                            </a>
                        </div>
                        
                        {{-- Hobbies (Separate Section) --}}
                        <div class="pt-2">
                            <div class="flex items-center">
                                <span class="text-sm font-medium text-gray-700 mr-3">Hobbies:</span>
                                <div class="flex flex-wrap gap-1">
                                    <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs">Music</span>
                                    <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs">Movies</span>
                                    <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs">Cooking</span>
                                    <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs">Solitude</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Team Collaboration Section --}}
        <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl p-8 mb-12 border border-gray-200 animate-fadeInUp">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-800 mb-3">Full-Stack Synergy</h2>
                <p class="text-gray-600 max-w-3xl mx-auto">
                    Dua pendekatan berbeda, satu tujuan: membangun platform HSBL yang solid dan user-friendly
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-r from-blue-500 to-cyan-400 flex items-center justify-center mr-4">
                            <i class="fas fa-palette text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800">Mutia, S.Kom: Visual Precision</h3>
                            <p class="text-sm text-gray-600">INTJ - Structured & Aesthetic</p>
                        </div>
                    </div>
                    <p class="text-gray-700">
                        Mengombinasikan logika sistem dengan kepekaan visual. Pendekatan terstruktur terhadap UI/UX 
                        memastikan setiap elemen tidak hanya berfungsi baik tapi juga memberikan pengalaman visual yang menyenangkan.
                    </p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-r from-purple-500 to-pink-400 flex items-center justify-center mr-4">
                            <i class="fas fa-cogs text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800">Wafiq, S.Kom: Systematic Efficiency</h3>
                            <p class="text-sm text-gray-600">INFJ - Observant & Methodical</p>
                        </div>
                    </div>
                    <p class="text-gray-700">
                        Fokus pada arsitektur sistem yang efisien dan scalable. Pendekatan observatif memungkinkan 
                        identifikasi masalah sebelum menjadi kritis, membangun fondasi yang kuat untuk seluruh platform.
                    </p>
                </div>
            </div>
            
            <div class="mt-8 pt-8 border-t border-gray-200">
                <div class="text-center">
                    <h3 class="font-bold text-gray-800 mb-3">Kombinasi yang Solid</h3>
                    <p class="text-gray-700 max-w-3xl mx-auto">
                        Bersama-sama, mereka menangani seluruh spektrum pengembangan web - dari database dan backend logic 
                        hingga frontend design dan user experience. Keduanya lulusan Ilmu Komputer Universitas Riau dengan 
                        spesialisasi Full-Stack Development yang komplementer.
                    </p>
                </div>
            </div>
        </div>

        {{-- Code Philosophy --}}
        <div class="bg-gray-900 text-white rounded-2xl p-8 overflow-hidden relative animate-fadeInUp">
            <div class="absolute top-4 right-4 opacity-10">
                <i class="fas fa-code text-6xl"></i>
            </div>
            <div class="relative z-10">
                <h2 class="text-2xl font-bold mb-6 flex items-center">
                    <i class="fas fa-quote-left text-blue-400 mr-3"></i>
                    Development Philosophy
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1">
                                <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center">
                                    <span class="text-sm font-bold">M</span>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-gray-300 italic">
                                    "Clean code should read like well-written prose. The frontend is the conversation 
                                    we have with users, while the backend is the solid foundation that makes it all possible."
                                </p>
                                <p class="text-sm text-blue-300 mt-2">— Mutia Rizkianti, S.Kom</p>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1">
                                <div class="w-8 h-8 rounded-full bg-purple-500 flex items-center justify-center">
                                    <span class="text-sm font-bold">W</span>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-gray-300 italic">
                                    "Efficiency isn't about doing things quickly; it's about building systems that 
                                    don't need constant fixing. A good architecture solves problems before they happen."
                                </p>
                                <p class="text-sm text-purple-300 mt-2">— Wafiq Wardatul Khairani, S.Kom</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- CTA Section --}}
        <div class="text-center mt-12 animate-fadeInUp">
            <p class="text-gray-600 mb-6">
                Interested in collaborating or learning more about our development process?
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="mailto:mutiarizkianti04@gmail.com" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-500 text-white font-medium rounded-lg hover:from-blue-700 hover:to-blue-600 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    <i class="fas fa-envelope mr-2"></i>
                    Contact Mutia, S.Kom
                </a>
                <a href="mailto:wafiqwardatulkhairani22@gmail.com" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-500 text-white font-medium rounded-lg hover:from-purple-700 hover:to-pink-600 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    <i class="fas fa-envelope mr-2"></i>
                    Contact Wafiq, S.Kom
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Custom Styles for Developer Page --}}
<style>
    .animate-fadeInUp {
        animation: fadeInUp 0.6s ease-out forwards;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .developer-card-hover {
        transition: all 0.3s ease;
    }

    .developer-card-hover:hover {
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }

    .personality-badge {
        position: relative;
        overflow: hidden;
    }

    .personality-badge::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(
            to right,
            rgba(255, 255, 255, 0) 20%,
            rgba(255, 255, 255, 0.3) 50%,
            rgba(255, 255, 255, 0) 80%
        );
        transform: rotate(30deg);
        animation: shine 3s infinite linear;
    }

    @keyframes shine {
        0% {
            transform: translateX(-100%) translateY(-100%) rotate(30deg);
        }
        100% {
            transform: translateX(100%) translateY(100%) rotate(30deg);
        }
    }
</style>

{{-- Add Font Awesome Brands for LinkedIn & Instagram --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" defer></script>
@endsection