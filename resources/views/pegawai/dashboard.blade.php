<x-layouts.app>
    <x-slot name="title">Dashboard</x-slot>

    @php
        $today = now()->toDateString();
        $jamSekarang = now()->format('H:i');
        $sudahMasuk = $absensiHariIni && $absensiHariIni->jam_masuk;
        $sudahPulang = $absensiHariIni && $absensiHariIni->jam_pulang;
    @endphp

    <div class="space-y-6">
        <!-- Greeting Card -->
        <div class="relative overflow-hidden rounded-2xl p-6" style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #1e1b4b 100%); border: 1px solid rgba(99,102,241,0.3);">
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-violet-600/20 rounded-full blur-2xl"></div>
                <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-indigo-600/20 rounded-full blur-2xl"></div>
            </div>
            <div class="relative z-10 flex items-start justify-between">
                <div>
                    <p class="text-indigo-300 text-sm">Selamat datang,</p>
                    <h2 class="text-2xl font-bold text-white mt-1">{{ auth()->user()->name }} 👋</h2>
                    @if($pegawai && $pegawai->jabatan)
                        <p class="text-indigo-300 text-sm mt-1">{{ $pegawai->jabatan }} • {{ $pegawai->divisi }}</p>
                    @else
                        <p class="text-amber-400 text-sm mt-1">⚠ Data jabatan belum dilengkapi oleh admin</p>
                    @endif
                </div>
                <div class="text-right">
                    <p class="text-3xl font-bold text-white tabular-nums" id="dashTime">{{ $jamSekarang }}</p>
                    <p class="text-indigo-300 text-sm">{{ now()->isoFormat('dddd, D MMMM Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Status Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Absen Masuk -->
            <div class="glass-card rounded-2xl p-5 stat-card">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center {{ $sudahMasuk ? 'bg-emerald-500/20' : 'bg-indigo-500/20' }}">
                        <svg class="w-5 h-5 {{ $sudahMasuk ? 'text-emerald-400' : 'text-indigo-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14" />
                        </svg>
                    </div>
                    @if($sudahMasuk)
                        <span class="text-xs px-2 py-1 rounded-full badge-{{ $absensiHariIni->status }}">
                            {{ ucfirst($absensiHariIni->status) }}
                        </span>
                    @endif
                </div>
                <p class="text-sm text-indigo-300 mb-1">Absen Masuk</p>
                @if($sudahMasuk)
                    <p class="text-2xl font-bold text-white">{{ \Carbon\Carbon::parse($absensiHariIni->jam_masuk)->format('H:i') }}</p>
                    <p class="text-xs text-indigo-400 mt-1">Jarak: {{ $absensiHariIni->jarak_masuk_meter }}m dari kantor</p>
                @else
                    <p class="text-2xl font-bold text-indigo-400">Belum absen</p>
                    <a href="{{ route('pegawai.absensi.masuk') }}"
                       class="mt-3 inline-block text-xs font-medium text-indigo-300 hover:text-white border border-indigo-700/50 hover:border-indigo-500 rounded-lg px-3 py-1.5 transition-all">
                        Absen Sekarang →
                    </a>
                @endif
            </div>

            <!-- Absen Pulang -->
            <div class="glass-card rounded-2xl p-5 stat-card">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center {{ $sudahPulang ? 'bg-violet-500/20' : 'bg-indigo-500/20' }}">
                        <svg class="w-5 h-5 {{ $sudahPulang ? 'text-violet-400' : 'text-indigo-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7" />
                        </svg>
                    </div>
                </div>
                <p class="text-sm text-indigo-300 mb-1">Absen Pulang</p>
                @if($sudahPulang)
                    <p class="text-2xl font-bold text-white">{{ \Carbon\Carbon::parse($absensiHariIni->jam_pulang)->format('H:i') }}</p>
                    <p class="text-xs text-indigo-400 mt-1">Selesai untuk hari ini ✓</p>
                @elseif($sudahMasuk)
                    <p class="text-2xl font-bold text-indigo-400">Belum pulang</p>
                    <a href="{{ route('pegawai.absensi.pulang') }}"
                       class="mt-3 inline-block text-xs font-medium text-violet-300 hover:text-white border border-violet-700/50 hover:border-violet-500 rounded-lg px-3 py-1.5 transition-all">
                        Absen Pulang →
                    </a>
                @else
                    <p class="text-xl font-semibold text-gray-600">—</p>
                    <p class="text-xs text-gray-600 mt-1">Absen masuk dulu</p>
                @endif
            </div>

            <!-- Lokasi Kerja -->
            <div class="glass-card rounded-2xl p-5 stat-card">
                <div class="w-10 h-10 rounded-xl bg-teal-500/20 flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    </svg>
                </div>
                <p class="text-sm text-indigo-300 mb-1">Lokasi Kerja</p>
                @if($lokasiAktif)
                    <p class="text-base font-semibold text-white leading-tight">{{ $lokasiAktif->nama_lokasi }}</p>
                    <p class="text-xs text-indigo-400 mt-1">Radius {{ $lokasiAktif->radius_meter }}m • Masuk {{ substr($lokasiAktif->jam_masuk_standar, 0, 5) }}</p>
                @else
                    <p class="text-base font-semibold text-red-400">Tidak ada lokasi aktif</p>
                    <p class="text-xs text-red-400/70 mt-1">Hubungi admin</p>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <a href="{{ route('pegawai.absensi.masuk') }}" class="glass-card rounded-xl p-4 text-center hover:bg-indigo-600/20 transition-all duration-200 group">
                <div class="w-10 h-10 rounded-xl bg-indigo-500/20 flex items-center justify-center mx-auto mb-2 group-hover:bg-indigo-500/30">
                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
                </div>
                <p class="text-xs font-medium text-indigo-200">Absen Masuk</p>
            </a>
            <a href="{{ route('pegawai.absensi.pulang') }}" class="glass-card rounded-xl p-4 text-center hover:bg-violet-600/20 transition-all duration-200 group">
                <div class="w-10 h-10 rounded-xl bg-violet-500/20 flex items-center justify-center mx-auto mb-2 group-hover:bg-violet-500/30">
                    <svg class="w-5 h-5 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                </div>
                <p class="text-xs font-medium text-indigo-200">Absen Pulang</p>
            </a>
            <a href="{{ route('pegawai.izin.create') }}" class="glass-card rounded-xl p-4 text-center hover:bg-teal-600/20 transition-all duration-200 group">
                <div class="w-10 h-10 rounded-xl bg-teal-500/20 flex items-center justify-center mx-auto mb-2 group-hover:bg-teal-500/30">
                    <svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                </div>
                <p class="text-xs font-medium text-indigo-200">Ajukan Izin</p>
            </a>
            <a href="{{ route('pegawai.riwayat') }}" class="glass-card rounded-xl p-4 text-center hover:bg-amber-600/20 transition-all duration-200 group">
                <div class="w-10 h-10 rounded-xl bg-amber-500/20 flex items-center justify-center mx-auto mb-2 group-hover:bg-amber-500/30">
                    <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <p class="text-xs font-medium text-indigo-200">Riwayat</p>
            </a>
        </div>
    </div>

    @push('scripts')
    <script>
        function updateDashTime() {
            const el = document.getElementById('dashTime');
            if (el) el.textContent = new Date().toLocaleTimeString('id-ID', {hour:'2-digit',minute:'2-digit'});
        }
        setInterval(updateDashTime, 1000);
    </script>
    @endpush
</x-layouts.app>
