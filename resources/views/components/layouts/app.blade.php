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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            background: #030712;
            color: #f1f5f9;
        }

        /* =============================================
           SIDEBAR LAYOUT (Pure CSS Media Queries)
           ============================================= */
        #sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 260px;
            z-index: 50;
            display: flex;
            flex-direction: column;
            background: linear-gradient(180deg, #1e1b4b 0%, #2e1065 50%, #111827 100%);
            border-right: 1px solid rgba(99, 102, 241, 0.25);
            overflow: hidden;
            transform: translateX(-100%);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        #sidebar.is-open {
            transform: translateX(0);
        }

        #sidebarCloseBtn {
            display: flex;
        }

        #hamburgerBtn {
            display: flex;
        }

        #mainWrapper {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            margin-left: 0;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @media (min-width: 1024px) {
            #sidebar {
                transform: translateX(0) !important;
            }

            #mainWrapper {
                margin-left: 260px;
            }

            #sidebarCloseBtn {
                display: none !important;
            }

            #hamburgerBtn {
                display: none !important;
            }

            #sidebarOverlay {
                display: none !important;
            }
        }

        /* Glass card */
        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Sidebar nav links */
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            border-radius: 10px;
            font-size: 14px;
            color: #a5b4fc;
            text-decoration: none;
            transition: all 0.2s;
        }

        .sidebar-link:hover {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.3), rgba(139, 92, 246, 0.2));
            border-left: 3px solid #818cf8;
            padding-left: 15px;
        }

        .sidebar-link.active {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.3), rgba(139, 92, 246, 0.2));
            border-left: 3px solid #818cf8;
            padding-left: 15px;
        }

        /* Stat cards */
        .stat-card {
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
        }

        /* Badges */
        .badge-hadir {
            background: rgba(16, 185, 129, .2);
            color: #34d399;
            border: 1px solid rgba(16, 185, 129, .3);
            border-radius: 9999px;
            padding: 2px 10px;
            font-size: 12px;
        }

        .badge-terlambat {
            background: rgba(245, 158, 11, .2);
            color: #fbbf24;
            border: 1px solid rgba(245, 158, 11, .3);
            border-radius: 9999px;
            padding: 2px 10px;
            font-size: 12px;
        }

        .badge-alpha {
            background: rgba(239, 68, 68, .2);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, .3);
            border-radius: 9999px;
            padding: 2px 10px;
            font-size: 12px;
        }

        .badge-pending {
            background: rgba(245, 158, 11, .2);
            color: #fbbf24;
            border: 1px solid rgba(245, 158, 11, .3);
            border-radius: 9999px;
            padding: 2px 10px;
            font-size: 12px;
        }

        .badge-diterima {
            background: rgba(16, 185, 129, .2);
            color: #34d399;
            border: 1px solid rgba(16, 185, 129, .3);
            border-radius: 9999px;
            padding: 2px 10px;
            font-size: 12px;
        }

        .badge-ditolak {
            background: rgba(239, 68, 68, .2);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, .3);
            border-radius: 9999px;
            padding: 2px 10px;
            font-size: 12px;
        }

        /* Form elements */
        .input-field {
            background: rgba(255, 255, 255, .05);
            border: 1px solid rgba(99, 102, 241, .3);
            color: white;
            transition: border-color .2s, box-shadow .2s;
        }

        .input-field:focus {
            border-color: rgba(99, 102, 241, .7);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, .15);
            outline: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            cursor: pointer;
            transition: all .2s;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #4338ca, #6d28d9);
            box-shadow: 0 0 20px rgba(99, 102, 241, .4);
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #1e1b4b;
        }

        ::-webkit-scrollbar-thumb {
            background: #4f46e5;
            border-radius: 3px;
        }
    </style>

    @stack('styles')
</head>

<body>
    {{-- ====== OVERLAY (latar gelap mobile) ====== --}}
    <div id="sidebarOverlay"
        style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.65); z-index:40; backdrop-filter:blur(3px);"
        onclick="closeSidebar()"></div>

    {{-- ====== SIDEBAR ====== --}}
    <aside id="sidebar">
        {{-- Logo + tombol close (mobile) --}}
        <div
            style="display:flex; align-items:center; gap:10px; padding:14px 16px; border-bottom:1px solid rgba(99,102,241,0.2); flex-shrink:0;">
            <img src="{{ Storage::url('public/logo-links.png') }}" alt="Logo"
                style="width:38px; height:38px; border-radius:8px; object-fit:contain; background:white; padding:2px; flex-shrink:0;">
            <div style="min-width:0; flex:1;">
                <p
                    style="font-size:13px; font-weight:700; color:white; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; margin:0;">
                    Absensi Digital</p>
                <p
                    style="font-size:11px; color:#a5b4fc; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                    PT Lingkar Kreatif Solusi</p>
            </div>
            <button id="sidebarCloseBtn" onclick="closeSidebar()"
                style="background:none; border:none; cursor:pointer; color:#a5b4fc; padding:4px; border-radius:6px; flex-shrink:0;"
                aria-label="Tutup">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Info user --}}
        <div style="padding:10px 16px; border-bottom:1px solid rgba(99,102,241,0.2); flex-shrink:0;">
            <div
                style="display:flex; align-items:center; gap:10px; background:rgba(255,255,255,.05); border-radius:10px; padding:8px 10px;">
                <div
                    style="width:30px; height:30px; border-radius:7px; background:linear-gradient(135deg,#7c3aed,#4f46e5); display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:700; color:white; flex-shrink:0;">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div style="min-width:0;">
                    <p
                        style="font-size:13px; font-weight:500; color:white; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                        {{ auth()->user()->name }}</p>
                    <p style="font-size:11px; color:#a5b4fc; margin:0; text-transform:capitalize;">
                        {{ auth()->user()->role }}</p>
                </div>
            </div>
        </div>

        {{-- Navigasi --}}
        <nav style="flex:1; overflow-y:auto; padding:12px 14px; display:flex; flex-direction:column; gap:2px;">
            @php $role = auth()->user()->role; @endphp

            @if ($role === 'pegawai')
                <p
                    style="font-size:10px; font-weight:700; color:#6366f1; text-transform:uppercase; letter-spacing:.06em; padding:4px 12px 6px; margin:0;">
                    Menu Utama</p>
                <a href="{{ route('pegawai.dashboard') }}"
                    class="sidebar-link {{ request()->routeIs('pegawai.dashboard') ? 'active' : '' }}">
                    <svg width="18" height="18" style="flex-shrink:0;" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('pegawai.absensi.masuk') }}"
                    class="sidebar-link {{ request()->routeIs('pegawai.absensi.masuk') ? 'active' : '' }}">
                    <svg width="18" height="18" style="flex-shrink:0;" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                    Absen Masuk
                </a>
                <a href="{{ route('pegawai.absensi.pulang') }}"
                    class="sidebar-link {{ request()->routeIs('pegawai.absensi.pulang') ? 'active' : '' }}">
                    <svg width="18" height="18" style="flex-shrink:0;" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Absen Pulang
                </a>
                <a href="{{ route('pegawai.izin.create') }}"
                    class="sidebar-link {{ request()->routeIs('pegawai.izin.*') ? 'active' : '' }}">
                    <svg width="18" height="18" style="flex-shrink:0;" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Ajukan Izin
                </a>
                <a href="{{ route('pegawai.riwayat') }}"
                    class="sidebar-link {{ request()->routeIs('pegawai.riwayat') ? 'active' : '' }}">
                    <svg width="18" height="18" style="flex-shrink:0;" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Riwayat
                </a>
            @elseif($role === 'admin')
                <p
                    style="font-size:10px; font-weight:700; color:#6366f1; text-transform:uppercase; letter-spacing:.06em; padding:4px 12px 6px; margin:0;">
                    Administrasi</p>
                <a href="{{ route('admin.dashboard') }}"
                    class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg width="18" height="18" style="flex-shrink:0;" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('admin.pegawai.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.pegawai.*') ? 'active' : '' }}">
                    <svg width="18" height="18" style="flex-shrink:0;" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Kelola Pegawai
                </a>
                <a href="{{ route('admin.lokasi-kerja.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.lokasi-kerja.*') ? 'active' : '' }}">
                    <svg width="18" height="18" style="flex-shrink:0;" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Lokasi Kerja
                </a>
                <a href="{{ route('admin.absensi.index') }}"
                    class="sidebar-link {{ request()->routeIs('admin.absensi.index') ? 'active' : '' }}">
                    <svg width="18" height="18" style="flex-shrink:0;" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    Absensi Harian
                </a>
                <a href="{{ route('admin.absensi.rekap') }}"
                    class="sidebar-link {{ request()->routeIs('admin.absensi.rekap') ? 'active' : '' }}">
                    <svg width="18" height="18" style="flex-shrink:0;" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Rekap & Laporan
                </a>
            @elseif($role === 'hrd')
                <p
                    style="font-size:10px; font-weight:700; color:#6366f1; text-transform:uppercase; letter-spacing:.06em; padding:4px 12px 6px; margin:0;">
                    HRD Panel</p>
                <a href="{{ route('hrd.monitoring') }}"
                    class="sidebar-link {{ request()->routeIs('hrd.monitoring') ? 'active' : '' }}">
                    <svg width="18" height="18" style="flex-shrink:0;" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Monitoring Kehadiran
                </a>
                <a href="{{ route('hrd.izin.index') }}"
                    class="sidebar-link {{ request()->routeIs('hrd.izin.*') ? 'active' : '' }}">
                    <svg width="18" height="18" style="flex-shrink:0;" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Verifikasi Izin
                    @php $pendingIzin = \App\Models\Izin::where('status_persetujuan','pending')->count(); @endphp
                    @if ($pendingIzin > 0)
                        <span
                            style="margin-left:auto; background:#f59e0b; color:white; font-size:10px; font-weight:700; padding:1px 7px; border-radius:9999px;">{{ $pendingIzin }}</span>
                    @endif
                </a>
                <a href="{{ route('hrd.laporan') }}"
                    class="sidebar-link {{ request()->routeIs('hrd.laporan') ? 'active' : '' }}">
                    <svg width="18" height="18" style="flex-shrink:0;" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    Laporan Bulanan
                </a>
            @endif
        </nav>

        {{-- Logout --}}
        <div style="padding:14px 16px; border-top:1px solid rgba(99,102,241,0.2); flex-shrink:0;">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    style="width:100%; display:flex; align-items:center; gap:12px; padding:10px 12px; background:none; border:none; cursor:pointer; color:#f87171; border-radius:10px; font-size:14px; transition:background 0.2s;"
                    onmouseover="this.style.background='rgba(239,68,68,0.1)'"
                    onmouseout="this.style.background='none'">
                    <svg width="18" height="18" style="flex-shrink:0;" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    {{-- ====== MAIN CONTENT WRAPPER ====== --}}
    <div id="mainWrapper">

        {{-- Topbar --}}
        <header
            style="position:sticky; top:0; z-index:30; height:56px; background:rgba(3,7,18,0.92); backdrop-filter:blur(12px); border-bottom:1px solid rgba(99,102,241,0.15); display:flex; align-items:center; justify-content:space-between; padding:0 14px; gap:10px; flex-shrink:0;">
            <div style="display:flex; align-items:center; gap:10px; min-width:0;">
                <button id="hamburgerBtn" onclick="toggleSidebar()"
                    style="background:none; border:none; cursor:pointer; color:#a5b4fc; padding:6px; border-radius:8px; flex-shrink:0;"
                    aria-label="Menu">
                    <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <h1
                    style="font-size:15px; font-weight:600; color:white; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                    {{ $title ?? 'Dashboard' }}</h1>
            </div>
            <div style="display:flex; align-items:center; gap:10px; flex-shrink:0;">
                <span id="clockEl" style="font-size:11px; color:#818cf8;"></span>
                <span
                    style="font-size:11px; padding:3px 10px; border-radius:9999px; background:rgba(30,27,75,.8); color:#a5b4fc; border:1px solid rgba(99,102,241,.3); text-transform:capitalize;">
                    {{ auth()->user()->role }}
                </span>
            </div>
        </header>

        {{-- Flash messages --}}
        <div style="padding:12px 12px 0;">
            @if (session('success'))
                <div
                    class="flex items-center gap-3 px-4 py-3 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 mb-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
            @endif
            @if (session('error'))
                <div
                    class="flex items-center gap-3 px-4 py-3 rounded-xl bg-red-500/10 border border-red-500/30 text-red-400 mb-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm">{{ session('error') }}</p>
                </div>
            @endif
            @if (session('info'))
                <div
                    class="flex items-center gap-3 px-4 py-3 rounded-xl bg-blue-500/10 border border-blue-500/30 text-blue-400 mb-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm">{{ session('info') }}</p>
                </div>
            @endif
        </div>

        {{-- Page slot --}}
        <main style="flex:1; padding:8px 12px 20px;" class="sm:px-5 lg:px-6 sm:pb-6">
            {{ $slot }}
        </main>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        // ============================================================
        // SIDEBAR STATE — toggled by class
        // ============================================================
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');

        function openSidebar() {
            sidebar.classList.add('is-open');
            overlay.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            sidebar.classList.remove('is-open');
            overlay.style.display = 'none';
            document.body.style.overflow = '';
        }

        function toggleSidebar() {
            if (sidebar.classList.contains('is-open')) {
                closeSidebar();
            } else {
                openSidebar();
            }
        }

        // Close sidebar on navigation link click (mobile only)
        sidebar.querySelectorAll('a.sidebar-link').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 1024) {
                    closeSidebar();
                }
            });
        });

        // Handle window resize to clean up mobile state if resized to desktop
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                document.body.style.overflow = '';
                overlay.style.display = 'none';
            }
        });

        // ============================================================
        // JAM REAL-TIME
        // ============================================================
        function updateClock() {
            const el = document.getElementById('clockEl');
            if (el) {
                el.textContent = new Date().toLocaleString('id-ID', {
                    weekday: 'short',
                    day: 'numeric',
                    month: 'short',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }
        }
        updateClock();
        setInterval(updateClock, 1000);
    </script>

    @stack('scripts')
</body>

</html>
