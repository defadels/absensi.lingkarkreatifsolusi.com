<x-layouts.app>
    <x-slot name="title">Dashboard Admin</x-slot>

    <div class="space-y-4 sm:space-y-6">
        <!-- Welcome -->
        <div class="relative overflow-hidden rounded-2xl p-4 sm:p-6" style="background: linear-gradient(135deg, #1e1b4b 0%, #3730a3 50%, #1e1b4b 100%); border: 1px solid rgba(99,102,241,0.4);">
            <div class="absolute -top-12 -right-12 w-48 h-48 bg-indigo-500/15 rounded-full blur-3xl"></div>
            <div class="relative z-10">
                <h2 class="text-xl sm:text-2xl font-bold text-white">Dashboard Admin 🛡️</h2>
                <p class="text-indigo-300 text-xs sm:text-sm mt-1">{{ now()->isoFormat('dddd, D MMMM Y') }}</p>
            </div>
        </div>

        <!-- Stat Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
            <div class="glass-card rounded-2xl p-4 sm:p-5 stat-card">
                <div class="w-10 h-10 rounded-xl bg-indigo-500/20 flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                </div>
                <p class="text-xl sm:text-2xl font-bold text-white">{{ $stats['total_pegawai'] }}</p>
                <p class="text-xs text-indigo-300 mt-0.5">Total Pegawai</p>
            </div>
            <div class="glass-card rounded-2xl p-4 sm:p-5 stat-card">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/20 flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <p class="text-xl sm:text-2xl font-bold text-white">{{ $stats['hadir_hari_ini'] }}</p>
                <p class="text-xs text-indigo-300 mt-0.5">Hadir Hari Ini</p>
            </div>
            <div class="glass-card rounded-2xl p-4 sm:p-5 stat-card">
                <div class="w-10 h-10 rounded-xl bg-amber-500/20 flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <p class="text-xl sm:text-2xl font-bold text-white">{{ $stats['pending_izin'] }}</p>
                <p class="text-xs text-indigo-300 mt-0.5">Izin Pending</p>
            </div>
            <div class="glass-card rounded-2xl p-4 sm:p-5 stat-card">
                <div class="w-10 h-10 rounded-xl bg-teal-500/20 flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /></svg>
                </div>
                <p class="text-xl sm:text-2xl font-bold text-white">{{ $stats['lokasi_aktif'] }}</p>
                <p class="text-xs text-indigo-300 mt-0.5">Lokasi Aktif</p>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
            <a href="{{ route('admin.absensi.index') }}" class="glass-card rounded-2xl p-4 sm:p-5 hover:bg-white/5 transition-all group">
                <div class="flex items-center gap-3 sm:gap-4">
                    <div class="w-10 sm:w-12 h-10 sm:h-12 rounded-xl bg-indigo-500/20 flex items-center justify-center group-hover:bg-indigo-500/30 transition-colors flex-shrink-0">
                        <svg class="w-5 sm:w-6 h-5 sm:h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                    </div>
                    <div class="min-w-0">
                        <p class="font-semibold text-white text-sm sm:text-base">Absensi Harian</p>
                        <p class="text-xs text-indigo-400 mt-0.5">Lihat kehadiran pegawai hari ini</p>
                    </div>
                    <svg class="w-4 h-4 text-indigo-500 ml-auto group-hover:text-indigo-300 transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                </div>
            </a>
            <a href="{{ route('admin.pegawai.index') }}" class="glass-card rounded-2xl p-4 sm:p-5 hover:bg-white/5 transition-all group">
                <div class="flex items-center gap-3 sm:gap-4">
                    <div class="w-10 sm:w-12 h-10 sm:h-12 rounded-xl bg-violet-500/20 flex items-center justify-center group-hover:bg-violet-500/30 transition-colors flex-shrink-0">
                        <svg class="w-5 sm:w-6 h-5 sm:h-6 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    </div>
                    <div class="min-w-0">
                        <p class="font-semibold text-white text-sm sm:text-base">Kelola Pegawai</p>
                        <p class="text-xs text-indigo-400 mt-0.5">Lengkapi data NIP, jabatan, divisi</p>
                    </div>
                    <svg class="w-4 h-4 text-indigo-500 ml-auto group-hover:text-indigo-300 transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                </div>
            </a>
            <a href="{{ route('admin.lokasi-kerja.index') }}" class="glass-card rounded-2xl p-4 sm:p-5 hover:bg-white/5 transition-all group">
                <div class="flex items-center gap-3 sm:gap-4">
                    <div class="w-10 sm:w-12 h-10 sm:h-12 rounded-xl bg-teal-500/20 flex items-center justify-center group-hover:bg-teal-500/30 transition-colors flex-shrink-0">
                        <svg class="w-5 sm:w-6 h-5 sm:h-6 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /></svg>
                    </div>
                    <div class="min-w-0">
                        <p class="font-semibold text-white text-sm sm:text-base">Lokasi Kerja</p>
                        <p class="text-xs text-indigo-400 mt-0.5">Set titik & radius kantor di peta</p>
                    </div>
                    <svg class="w-4 h-4 text-indigo-500 ml-auto group-hover:text-indigo-300 transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                </div>
            </a>
            <a href="{{ route('admin.absensi.rekap') }}" class="glass-card rounded-2xl p-4 sm:p-5 hover:bg-white/5 transition-all group">
                <div class="flex items-center gap-3 sm:gap-4">
                    <div class="w-10 sm:w-12 h-10 sm:h-12 rounded-xl bg-amber-500/20 flex items-center justify-center group-hover:bg-amber-500/30 transition-colors flex-shrink-0">
                        <svg class="w-5 sm:w-6 h-5 sm:h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    </div>
                    <div class="min-w-0">
                        <p class="font-semibold text-white text-sm sm:text-base">Rekap & Laporan</p>
                        <p class="text-xs text-indigo-400 mt-0.5">Ekspor PDF rekap kehadiran</p>
                    </div>
                    <svg class="w-4 h-4 text-indigo-500 ml-auto group-hover:text-indigo-300 transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                </div>
            </a>
        </div>
    </div>
</x-layouts.app>
