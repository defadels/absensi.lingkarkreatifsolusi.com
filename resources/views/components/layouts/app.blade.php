<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'Absensi LKS') }} — PT Lingkar Kreatif Solusi</title>
    <meta name="description" content="Sistem Absensi Digital PT Lingkar Kreatif Solusi">
    <link rel="icon" type="image/png" href="/Logo-Links.png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; }
        :root {
            --sidebar-width: 260px;
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #1e1b4b; }
        ::-webkit-scrollbar-thumb { background: #4f46e5; border-radius: 3px; }
        /* Glassmorphism card */
        .glass-card {
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.1);
        }
        /* Glow effects */
        .glow-indigo { box-shadow: 0 0 20px rgba(99,102,241,0.3); }
        .glow-violet { box-shadow: 0 0 20px rgba(139,92,246,0.3); }
        .glow-teal { box-shadow: 0 0 20px rgba(20,184,166,0.3); }
        /* Sidebar transition */
        .sidebar-link {
            transition: all 0.2s ease;
            border-radius: 10px;
        }
        .sidebar-link:hover, .sidebar-link.active {
            background: linear-gradient(135deg, rgba(99,102,241,0.3), rgba(139,92,246,0.2));
            border-left: 3px solid #818cf8;
            padding-left: calc(0.75rem + 3px);
        }
        /* Stat card hover */
        .stat-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .stat-card:hover {
            transform: translateY(-2px);
        }
        /* Badge */
        .badge-hadir { background: rgba(16,185,129,0.2); color: #34d399; border: 1px solid rgba(16,185,129,0.3); }
        .badge-terlambat { background: rgba(245,158,11,0.2); color: #fbbf24; border: 1px solid rgba(245,158,11,0.3); }
        .badge-alpha { background: rgba(239,68,68,0.2); color: #f87171; border: 1px solid rgba(239,68,68,0.3); }
        .badge-pending { background: rgba(245,158,11,0.2); color: #fbbf24; border: 1px solid rgba(245,158,11,0.3); }
        .badge-diterima { background: rgba(16,185,129,0.2); color: #34d399; border: 1px solid rgba(16,185,129,0.3); }
        .badge-ditolak { background: rgba(239,68,68,0.2); color: #f87171; border: 1px solid rgba(239,68,68,0.3); }
    </style>

    @stack('styles')
</head>
<body class="h-full bg-gray-950 text-gray-100">
    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar -->
        <aside id="sidebar" class="w-64 flex-shrink-0 bg-gradient-to-b from-indigo-950 via-violet-950 to-gray-900 border-r border-indigo-800/30 flex flex-col z-40 transition-transform duration-300">
            <!-- Logo -->
            <div class="flex items-center gap-3 px-4 py-4 border-b border-indigo-800/30">
                <img src="/Logo-Links.png" alt="Links Logo"
                     class="w-10 h-10 rounded-lg object-contain flex-shrink-0"
                     style="background: white; padding: 2px;">
                <div class="min-w-0">
                    <p class="text-sm font-bold text-white leading-tight truncate">Absensi Digital</p>
                    <p class="text-xs text-indigo-300 truncate">PT Lingkar Kreatif Solusi</p>
                </div>
            </div>

            <!-- User Info -->
            <div class="px-4 py-3 border-b border-indigo-800/30">
                <div class="flex items-center gap-3 px-3 py-2 rounded-xl bg-white/5">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-violet-500 to-indigo-600 flex items-center justify-center text-xs font-bold text-white">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-indigo-300 capitalize">{{ auth()->user()->role }}</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto px-4 py-4 space-y-1">
                @php $role = auth()->user()->role; @endphp

                @if($role === 'pegawai')
                    <p class="text-xs font-semibold text-indigo-400 uppercase tracking-wider px-3 mb-2">Menu Utama</p>

                    <a href="{{ route('pegawai.dashboard') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 text-sm text-indigo-200 {{ request()->routeIs('pegawai.dashboard') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                        Dashboard
                    </a>
                    <a href="{{ route('pegawai.absensi.masuk') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 text-sm text-indigo-200 {{ request()->routeIs('pegawai.absensi.masuk') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
                        Absen Masuk
                    </a>
                    <a href="{{ route('pegawai.absensi.pulang') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 text-sm text-indigo-200 {{ request()->routeIs('pegawai.absensi.pulang') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                        Absen Pulang
                    </a>
                    <a href="{{ route('pegawai.izin.create') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 text-sm text-indigo-200 {{ request()->routeIs('pegawai.izin.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        Ajukan Izin
                    </a>
                    <a href="{{ route('pegawai.riwayat') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 text-sm text-indigo-200 {{ request()->routeIs('pegawai.riwayat') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Riwayat
                    </a>

                @elseif($role === 'admin')
                    <p class="text-xs font-semibold text-indigo-400 uppercase tracking-wider px-3 mb-2">Administrasi</p>

                    <a href="{{ route('admin.dashboard') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 text-sm text-indigo-200 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                        Dashboard
                    </a>
                    <a href="{{ route('admin.pegawai.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 text-sm text-indigo-200 {{ request()->routeIs('admin.pegawai.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        Kelola Pegawai
                    </a>
                    <a href="{{ route('admin.lokasi-kerja.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 text-sm text-indigo-200 {{ request()->routeIs('admin.lokasi-kerja.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        Lokasi Kerja
                    </a>
                    <a href="{{ route('admin.absensi.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 text-sm text-indigo-200 {{ request()->routeIs('admin.absensi.index') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                        Absensi Harian
                    </a>
                    <a href="{{ route('admin.absensi.rekap') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 text-sm text-indigo-200 {{ request()->routeIs('admin.absensi.rekap') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        Rekap & Laporan
                    </a>

                @elseif($role === 'hrd')
                    <p class="text-xs font-semibold text-indigo-400 uppercase tracking-wider px-3 mb-2">HRD Panel</p>

                    <a href="{{ route('hrd.monitoring') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 text-sm text-indigo-200 {{ request()->routeIs('hrd.monitoring') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                        Monitoring Kehadiran
                    </a>
                    <a href="{{ route('hrd.izin.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 text-sm text-indigo-200 {{ request()->routeIs('hrd.izin.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Verifikasi Izin
                        @php $pending = \App\Models\Izin::where('status_persetujuan','pending')->count(); @endphp
                        @if($pending > 0)
                            <span class="ml-auto bg-amber-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $pending }}</span>
                        @endif
                    </a>
                    <a href="{{ route('hrd.laporan') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 text-sm text-indigo-200 {{ request()->routeIs('hrd.laporan') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                        Laporan Bulanan
                    </a>
                @endif
            </nav>

            <!-- Logout -->
            <div class="px-4 py-4 border-t border-indigo-800/30">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 text-sm text-red-400 hover:bg-red-500/10 rounded-xl transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                        Keluar
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top bar -->
            <header class="flex-shrink-0 h-16 bg-gray-900/80 backdrop-blur border-b border-indigo-800/20 flex items-center justify-between px-6">
                <div class="flex items-center gap-4">
                    <button id="sidebarToggle" class="lg:hidden text-indigo-300 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                    </button>
                    <h1 class="text-lg font-semibold text-white">{{ $title ?? 'Dashboard' }}</h1>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-sm text-indigo-300" id="currentTime"></span>
                    <div class="w-px h-5 bg-indigo-800/50"></div>
                    <span class="text-xs px-3 py-1 rounded-full bg-indigo-900/50 text-indigo-300 border border-indigo-700/30 capitalize">
                        {{ auth()->user()->role }}
                    </span>
                </div>
            </header>

            <!-- Page content -->
            <main class="flex-1 overflow-y-auto p-6">
                <!-- Flash messages -->
                @if(session('success'))
                    <div class="mb-4 flex items-center gap-3 px-4 py-3 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <p class="text-sm font-medium">{{ session('success') }}</p>
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-4 flex items-center gap-3 px-4 py-3 rounded-xl bg-red-500/10 border border-red-500/30 text-red-400">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <p class="text-sm font-medium">{{ session('error') }}</p>
                    </div>
                @endif
                @if(session('info'))
                    <div class="mb-4 flex items-center gap-3 px-4 py-3 rounded-xl bg-blue-500/10 border border-blue-500/30 text-blue-400">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <p class="text-sm font-medium">{{ session('info') }}</p>
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        // Live clock
        function updateTime() {
            const now = new Date();
            const timeEl = document.getElementById('currentTime');
            if (timeEl) {
                timeEl.textContent = now.toLocaleString('id-ID', {
                    weekday: 'long', day: 'numeric', month: 'long',
                    hour: '2-digit', minute: '2-digit'
                });
            }
        }
        updateTime();
        setInterval(updateTime, 1000);

        // Mobile sidebar toggle
        document.getElementById('sidebarToggle')?.addEventListener('click', () => {
            document.getElementById('sidebar').classList.toggle('-translate-x-full');
        });
    </script>

    @stack('scripts')
</body>
</html>
