<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Absensi Digital — PT Lingkar Kreatif Solusi</title>
    <link rel="icon" type="image/png" href="/Logo-Links.png">
    
    <!-- Meta tags for PWA compatibility -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Absensi LKS">
    <link rel="apple-touch-icon" href="/icons/icon-192x192.png">
    <link rel="manifest" href="/manifest.json">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS (Tailwind 3 is available in Laravel Breeze setup, we load app.css but since it's a landing page we can also include our own custom beautiful styles) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: radial-gradient(ellipse at 10% 20%, rgba(99,102,241,0.18) 0%, transparent 50%),
                        radial-gradient(ellipse at 90% 10%, rgba(139,92,246,0.18) 0%, transparent 50%),
                        radial-gradient(ellipse at 50% 80%, rgba(20,184,166,0.06) 0%, transparent 50%),
                        #030712;
            color: #f3f4f6;
            overflow-x: hidden;
        }
        h1, h2, h3, .font-display {
            font-family: 'Outfit', sans-serif;
        }
        .glass-navbar {
            background: rgba(3, 7, 18, 0.6);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.02);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.06);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .glass-card:hover {
            background: rgba(255, 255, 255, 0.04);
            border-color: rgba(99, 102, 241, 0.3);
            transform: translateY(-4px);
            box-shadow: 0 20px 40px -15px rgba(99, 102, 241, 0.15);
        }
        .text-glow {
            text-shadow: 0 0 20px rgba(99, 102, 241, 0.5);
        }
        .btn-primary {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            box-shadow: 0 4px 20px rgba(79, 70, 229, 0.35);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #4338ca, #6d28d9);
            box-shadow: 0 6px 28px rgba(79, 70, 229, 0.55);
            transform: translateY(-2px);
        }
        .btn-secondary {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }
        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }
        /* Floating orbs */
        @keyframes float-slow {
            0%, 100% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.95); }
        }
        .orb {
            animation: float-slow 10s ease-in-out infinite;
        }
        .orb-delay-1 {
            animation-delay: -3s;
        }
        .orb-delay-2 {
            animation-delay: -6s;
        }
        /* Tab Styles */
        .tab-btn.active {
            color: #818cf8;
            border-bottom: 2px solid #818cf8;
            background: rgba(99, 102, 241, 0.05);
        }
    </style>
</head>
<body class="min-h-screen relative selection:bg-indigo-500 selection:text-white">

    <!-- Background Orbs -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="orb absolute top-1/4 left-1/4 w-80 h-80 bg-indigo-600/10 rounded-full blur-[100px]"></div>
        <div class="orb orb-delay-1 absolute bottom-1/3 right-1/4 w-96 h-96 bg-purple-600/10 rounded-full blur-[120px]"></div>
        <div class="orb orb-delay-2 absolute top-2/3 left-1/3 w-72 h-72 bg-teal-600/5 rounded-full blur-[90px]"></div>
    </div>

    <!-- Navigation -->
    <nav class="glass-navbar fixed top-0 left-0 right-0 z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <div class="bg-white px-4 py-2 rounded-xl flex items-center shadow-lg">
                        <img src="/links-logo-new.png" alt="PT Lingkar Kreatif Solusi" class="h-8 w-auto object-contain">
                    </div>
                    <span class="hidden sm:block font-display font-semibold text-lg tracking-wider text-slate-100 uppercase">Absensi LKS</span>
                </div>
                
                <!-- Action Link -->
                <div class="flex items-center gap-4">
                    <a href="{{ route('login') }}" class="btn-secondary px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-200">
                        Masuk Ke Aplikasi
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="relative z-10 pt-28">
        
        <!-- Hero Section -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-16">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <!-- Text Area -->
                <div class="lg:col-span-7 space-y-8 text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 bg-indigo-500/10 border border-indigo-500/25 px-4 py-1.5 rounded-full text-indigo-300 text-sm font-semibold tracking-wide">
                        <span class="flex h-2 w-2 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                        </span>
                        Kini Mendukung PWA & Offline Mode
                    </div>
                    
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-none text-slate-100">
                        Absensi Kerja Lebih <span class="bg-gradient-to-r from-indigo-400 via-purple-400 to-teal-300 bg-clip-text text-transparent">Cepat & Presisi</span>
                    </h1>
                    
                    <p class="text-lg text-slate-400 max-w-2xl mx-auto lg:mx-0 font-light leading-relaxed">
                        Sistem kehadiran digital PT Lingkar Kreatif Solusi. Pasang aplikasi langsung di smartphone atau desktop Anda sebagai PWA (Progressive Web App). Ringan, cepat, dan tetap berfungsi walau tanpa koneksi internet.
                    </p>
                    
                    <!-- Dynamic PWA Action Buttons -->
                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                        <!-- Custom dynamic PWA installation button (visible when beforeinstallprompt fires) -->
                        <button id="pwaInstallBtn" class="btn-primary hidden px-8 py-4 rounded-xl text-base font-bold text-white items-center gap-3 cursor-pointer group">
                            <svg class="w-5 h-5 animate-bounce group-hover:animate-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Pasang Aplikasi Sekarang
                        </button>
                        
                        <a href="#cara-pasang" class="btn-secondary px-8 py-4 rounded-xl text-base font-semibold text-slate-300 flex items-center gap-2">
                            Panduan Pemasangan Manual
                        </a>
                    </div>
                    
                    <!-- Device Info Badge -->
                    <div id="pwaInstalledNotice" class="hidden text-emerald-400 text-sm font-semibold flex items-center justify-center lg:justify-start gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Aplikasi sudah terpasang di perangkat Anda!
                    </div>
                </div>
                
                <!-- Mockup Area -->
                <div class="lg:col-span-5 flex justify-center">
                    <div class="relative w-full max-w-sm">
                        <!-- Glowing backdrop behind phone -->
                        <div class="absolute inset-0 bg-indigo-500/10 rounded-[3rem] blur-3xl transform rotate-6 scale-95"></div>
                        
                        <!-- Premium HTML Phone Mockup -->
                        <div class="relative border-4 border-slate-700/80 bg-slate-950 rounded-[2.5rem] shadow-2xl overflow-hidden aspect-[9/19] w-full max-w-[310px] mx-auto">
                            <!-- Speaker / Notch -->
                            <div class="absolute top-0 left-1/2 transform -translate-x-1/2 h-5 w-32 bg-slate-700/80 rounded-b-2xl z-20 flex justify-center items-center">
                                <div class="w-10 h-1 bg-slate-900 rounded-full"></div>
                            </div>
                            
                            <!-- Internal App UI -->
                            <div class="h-full flex flex-col justify-between p-4 pt-8 text-xs select-none">
                                <!-- App Header -->
                                <div class="flex justify-between items-center pb-3 border-b border-white/5">
                                    <div class="flex items-center gap-2">
                                        <div class="w-5 h-5 bg-white rounded-md p-0.5">
                                            <img src="/Logo-Links.png" class="w-full h-full object-contain">
                                        </div>
                                        <div>
                                            <div class="font-bold text-[10px] text-slate-100">PT LKS</div>
                                            <div class="text-[8px] text-slate-500">Sistem Absensi</div>
                                        </div>
                                    </div>
                                    <span class="badge-hadir text-[9px]">Online</span>
                                </div>
                                
                                <!-- Card: Live Status -->
                                <div class="bg-indigo-950/40 border border-indigo-500/20 rounded-xl p-3.5 mt-3 space-y-2">
                                    <div class="text-[10px] text-indigo-300 font-semibold uppercase tracking-wider">Senin, 6 Juli 2026</div>
                                    <div class="text-xl font-bold text-slate-100 tracking-tight" id="live-clock">09:00:00</div>
                                    <p class="text-[9px] text-slate-400">Silakan lakukan absensi masuk kerja harian Anda.</p>
                                </div>
                                
                                <!-- Card: Action Buttons -->
                                <div class="grid grid-cols-2 gap-2 mt-2">
                                    <div class="bg-emerald-500/10 border border-emerald-500/20 rounded-xl p-2.5 text-center flex flex-col items-center justify-center gap-1">
                                        <div class="w-7 h-7 rounded-lg bg-emerald-500/20 flex items-center justify-center text-emerald-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                            </svg>
                                        </div>
                                        <span class="font-bold text-[9px] text-slate-200">Absen Masuk</span>
                                    </div>
                                    <div class="bg-rose-500/10 border border-rose-500/20 rounded-xl p-2.5 text-center flex flex-col items-center justify-center gap-1">
                                        <div class="w-7 h-7 rounded-lg bg-rose-500/20 flex items-center justify-center text-rose-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                            </svg>
                                        </div>
                                        <span class="font-bold text-[9px] text-slate-200">Absen Pulang</span>
                                    </div>
                                </div>
                                
                                <!-- GPS visual widget -->
                                <div class="bg-white/5 border border-white/5 rounded-xl p-2.5 mt-2 flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-teal-500/20 flex items-center justify-center text-teal-400">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <div class="font-semibold text-[8px] text-slate-300">Lokasi GPS Terdeteksi</div>
                                        <div class="text-[7px] text-slate-500">Radius Kantor: Dalam Jangkauan (Aman)</div>
                                    </div>
                                </div>
                                
                                <!-- Small Footer -->
                                <div class="text-center text-[7px] text-slate-600 mt-auto pt-4">
                                    App Absensi PWA • PT Lingkar Kreatif Solusi
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Features Section -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 border-t border-white/5">
            <div class="text-center max-w-3xl mx-auto space-y-4 mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold text-slate-100">
                    Kenapa Memasang Sebagai <span class="text-indigo-400">PWA</span>?
                </h2>
                <p class="text-slate-400 font-light">
                    Progressive Web App (PWA) memberikan pengalaman layaknya aplikasi native langsung dari peramban web perangkat Anda tanpa instalasi ribet dari app store.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Feature 1 -->
                <div class="glass-card rounded-2xl p-6 space-y-4">
                    <div class="w-12 h-12 rounded-xl bg-indigo-500/10 border border-indigo-500/25 flex items-center justify-center text-indigo-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-200">Performa Instan</h3>
                    <p class="text-sm text-slate-400 leading-relaxed font-light">
                        Ukuran super kecil (kurang dari 1MB) dan loading instan berkat mekanisme caching aset dinamis dari Service Worker.
                    </p>
                </div>
                
                <!-- Feature 2 -->
                <div class="glass-card rounded-2xl p-6 space-y-4">
                    <div class="w-12 h-12 rounded-xl bg-purple-500/10 border border-purple-500/25 flex items-center justify-center text-purple-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m12.728 12.728l.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-200">Mode Offline</h3>
                    <p class="text-sm text-slate-400 leading-relaxed font-light">
                        Jaringan internet terputus secara mendadak? Halaman fallback offline memandu Anda tanpa browser error dinosaur.
                    </p>
                </div>
                
                <!-- Feature 3 -->
                <div class="glass-card rounded-2xl p-6 space-y-4">
                    <div class="w-12 h-12 rounded-xl bg-teal-500/10 border border-teal-500/25 flex items-center justify-center text-teal-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-200">Pintasan Layar</h3>
                    <p class="text-sm text-slate-400 leading-relaxed font-light">
                        Dapatkan ikon aplikasi di beranda/home screen perangkat smartphone atau pintasan desktop komputer Anda.
                    </p>
                </div>
                
                <!-- Feature 4 -->
                <div class="glass-card rounded-2xl p-6 space-y-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-500/10 border border-amber-500/25 flex items-center justify-center text-amber-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-200">Dukungan Geolokasi</h3>
                    <p class="text-sm text-slate-400 leading-relaxed font-light">
                        Mengakses modul sensor GPS perangkat untuk pencatatan koordinat titik absen presisi secara native.
                    </p>
                </div>
            </div>
        </section>
        
        <!-- How to Install Section -->
        <section id="cara-pasang" class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-20 border-t border-white/5">
            <div class="text-center space-y-4 mb-12">
                <h2 class="text-3xl font-bold text-slate-100">
                    Panduan Pemasangan Manual
                </h2>
                <p class="text-slate-400 font-light">
                    Pilih tipe perangkat Anda di bawah untuk petunjuk konfigurasi pemasangan PWA secara cepat.
                </p>
            </div>
            
            <!-- Tabs container -->
            <div class="glass-card rounded-3xl overflow-hidden shadow-2xl">
                <!-- Tab headers -->
                <div class="flex border-b border-white/10 bg-slate-950/50">
                    <button onclick="switchTab('android')" id="tab-android" class="tab-btn active flex-1 py-4 text-center font-bold text-sm tracking-wide transition-all border-b-2 uppercase">
                        Android
                    </button>
                    <button onclick="switchTab('ios')" id="tab-ios" class="tab-btn flex-1 py-4 text-center font-bold text-sm tracking-wide transition-all border-b-2 uppercase">
                        iOS (iPhone / iPad)
                    </button>
                    <button onclick="switchTab('desktop')" id="tab-desktop" class="tab-btn flex-1 py-4 text-center font-bold text-sm tracking-wide transition-all border-b-2 uppercase">
                        Windows / Mac
                    </button>
                </div>
                
                <!-- Tab Contents -->
                <div class="p-8 sm:p-10 space-y-6">
                    
                    <!-- Content: Android -->
                    <div id="content-android" class="tab-content space-y-6">
                        <div class="flex items-center gap-4 text-indigo-400">
                            <span class="w-8 h-8 rounded-full bg-indigo-500/10 flex items-center justify-center font-bold text-sm">1</span>
                            <p class="text-slate-200 font-medium">Buka browser <strong>Google Chrome</strong> di smartphone Android Anda.</p>
                        </div>
                        <div class="flex items-center gap-4 text-indigo-400">
                            <span class="w-8 h-8 rounded-full bg-indigo-500/10 flex items-center justify-center font-bold text-sm">2</span>
                            <p class="text-slate-200 font-medium">Ketuk tombol <strong>"Pasang Aplikasi Sekarang"</strong> yang berada di bagian paling atas halaman ini.</p>
                        </div>
                        <div class="flex items-center gap-4 text-indigo-400">
                            <span class="w-8 h-8 rounded-full bg-indigo-500/10 flex items-center justify-center font-bold text-sm">3</span>
                            <p class="text-slate-200 font-medium">Atau, ketuk tombol <strong>tiga titik vertikal</strong> di pojok kanan atas browser Chrome Anda.</p>
                        </div>
                        <div class="flex items-center gap-4 text-indigo-400">
                            <span class="w-8 h-8 rounded-full bg-indigo-500/10 flex items-center justify-center font-bold text-sm">4</span>
                            <p class="text-slate-200 font-medium">Pilih menu <strong>"Tambahkan ke Layar Utama"</strong> atau <strong>"Instal Aplikasi"</strong>.</p>
                        </div>
                        <div class="flex items-center gap-4 text-indigo-400">
                            <span class="w-8 h-8 rounded-full bg-indigo-500/10 flex items-center justify-center font-bold text-sm">5</span>
                            <p class="text-slate-200 font-medium">Ketuk <strong>"Instal"</strong> pada pop-up konfirmasi. Aplikasi absensi siap diakses dari laci aplikasi Anda.</p>
                        </div>
                    </div>
                    
                    <!-- Content: iOS -->
                    <div id="content-ios" class="tab-content hidden space-y-6">
                        <div class="flex items-center gap-4 text-purple-400">
                            <span class="w-8 h-8 rounded-full bg-purple-500/10 flex items-center justify-center font-bold text-sm">1</span>
                            <p class="text-slate-200 font-medium">Buka browser bawaan <strong>Safari</strong> di iPhone atau iPad Anda.</p>
                        </div>
                        <div class="flex items-center gap-4 text-purple-400">
                            <span class="w-8 h-8 rounded-full bg-purple-500/10 flex items-center justify-center font-bold text-sm">2</span>
                            <p class="text-slate-200 font-medium">Tekan tombol <strong>"Bagikan (Share)"</strong> yang diwakili ikon kotak dengan anak panah menunjuk ke atas di bagian navigasi browser Safari.</p>
                        </div>
                        <div class="flex items-center gap-4 text-purple-400">
                            <span class="w-8 h-8 rounded-full bg-purple-500/10 flex items-center justify-center font-bold text-sm">3</span>
                            <p class="text-slate-200 font-medium">Gulir menu ke bawah dan ketuk opsi <strong>"Tambahkan ke Layar Utama (Add to Home Screen)"</strong>.</p>
                        </div>
                        <div class="flex items-center gap-4 text-purple-400">
                            <span class="w-8 h-8 rounded-full bg-purple-500/10 flex items-center justify-center font-bold text-sm">4</span>
                            <p class="text-slate-200 font-medium">Beri nama pintasan (default: <strong>Absensi LKS</strong>) dan tekan tombol <strong>"Tambah (Add)"</strong> di pojok kanan atas.</p>
                        </div>
                    </div>
                    
                    <!-- Content: Desktop -->
                    <div id="content-desktop" class="tab-content hidden space-y-6">
                        <div class="flex items-center gap-4 text-teal-400">
                            <span class="w-8 h-8 rounded-full bg-teal-500/10 flex items-center justify-center font-bold text-sm">1</span>
                            <p class="text-slate-200 font-medium">Buka peramban desktop seperti <strong>Google Chrome</strong> atau <strong>Microsoft Edge</strong> di PC/Mac Anda.</p>
                        </div>
                        <div class="flex items-center gap-4 text-teal-400">
                            <span class="w-8 h-8 rounded-full bg-teal-500/10 flex items-center justify-center font-bold text-sm">2</span>
                            <p class="text-slate-200 font-medium">Pada bilah alamat (URL bar) di atas, Anda akan melihat ikon <strong>Monitor dengan panah unduh (Install App)</strong> di sebelah kanan.</p>
                        </div>
                        <div class="flex items-center gap-4 text-teal-400">
                            <span class="w-8 h-8 rounded-full bg-teal-500/10 flex items-center justify-center font-bold text-sm">3</span>
                            <p class="text-slate-200 font-medium">Ketuk ikon tersebut, lalu konfirmasi dengan mengeklik tombol <strong>"Instal"</strong>.</p>
                        </div>
                        <div class="flex items-center gap-4 text-teal-400">
                            <span class="w-8 h-8 rounded-full bg-teal-500/10 flex items-center justify-center font-bold text-sm">4</span>
                            <p class="text-slate-200 font-medium">Aplikasi akan terbuka di jendela khusus tanpa antarmuka browser biasa, dan pintasan akan dibuat di desktop Anda.</p>
                        </div>
                    </div>
                    
                </div>
            </div>
        </section>
        
    </main>

    <!-- Footer -->
    <footer class="relative z-10 border-t border-white/5 bg-slate-950/40 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4">
            <div class="flex justify-center mb-2">
                <div class="bg-white px-3 py-1.5 rounded-lg flex items-center">
                    <img src="/links-logo-new.png" alt="PT Lingkar Kreatif Solusi" class="h-6 w-auto">
                </div>
            </div>
            <p class="text-sm text-slate-500">
                &copy; 2026 PT Lingkar Kreatif Solusi. Hak Cipta Dilindungi Undang-Undang.
            </p>
            <p class="text-xs text-slate-600 font-light">
                Developed to optimize workforce attendance tracking with high integrity and security.
            </p>
        </div>
    </footer>

    <!-- Interactive Scripts for Landing Page & PWA Install -->
    <script>
        // Tab switcher function
        function switchTab(tabId) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            // Remove active classes from buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            // Show target content and activate button
            document.getElementById('content-' + tabId).classList.remove('hidden');
            document.getElementById('tab-' + tabId).classList.add('active');
        }

        // Live Clock Script for the phone preview mockup
        setInterval(() => {
            const clockEl = document.getElementById('live-clock');
            if (clockEl) {
                const now = new Date();
                const pad = (num) => String(num).padStart(2, '0');
                clockEl.textContent = `${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
            }
        }, 1000);

        // PWA Installation Handler
        let deferredPrompt;
        const installBtn = document.getElementById('pwaInstallBtn');
        const installedNotice = document.getElementById('pwaInstalledNotice');

        window.addEventListener('beforeinstallprompt', (e) => {
            // Prevent Chrome 67 and earlier from automatically showing the prompt
            e.preventDefault();
            // Stash the event so it can be triggered later.
            deferredPrompt = e;
            // Update UI notify the user they can install the PWA
            if (installBtn) {
                installBtn.style.display = 'inline-flex';
            }
        });

        if (installBtn) {
            installBtn.addEventListener('click', async () => {
                if (!deferredPrompt) {
                    alert('Browser Anda tidak mendukung instalasi otomatis atau PWA sudah terinstal. Silakan baca panduan manual di bawah.');
                    return;
                }
                // Show the prompt
                deferredPrompt.prompt();
                // Wait for the user to respond to the prompt
                const { outcome } = await deferredPrompt.userChoice;
                console.log(`User response to install prompt: ${outcome}`);
                // We've used the prompt, and can't use it again, discard it
                deferredPrompt = null;
                // Hide the install button
                installBtn.style.display = 'none';
            });
        }

        // Catch app installed event
        window.addEventListener('appinstalled', (evt) => {
            console.log('Absensi LKS PWA installed successfully!');
            if (installBtn) {
                installBtn.style.display = 'none';
            }
            if (installedNotice) {
                installedNotice.classList.remove('hidden');
            }
        });

        // Check if app is already running standalone
        if (window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true) {
            if (installBtn) {
                installBtn.style.display = 'none';
            }
            if (installedNotice) {
                installedNotice.classList.remove('hidden');
            }
        }
    </script>
</body>
</html>
