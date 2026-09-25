<x-layouts.app>
    <x-slot name="title">Verifikasi Absensi</x-slot>

    <div class="max-w-4xl mx-auto space-y-4 sm:space-y-5">
        <a href="{{ route('admin.absensi.index') }}" class="text-indigo-400 hover:text-white transition-colors text-sm inline-flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Daftar
        </a>

        <!-- Header Info -->
        <div class="glass-card rounded-2xl p-4 sm:p-5">
            <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4">
                <div class="w-12 sm:w-14 h-12 sm:h-14 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center text-base sm:text-lg font-bold text-white flex-shrink-0">
                    {{ strtoupper(substr($absensi->pegawai->user->name, 0, 2)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <h2 class="text-lg font-bold text-white">{{ $absensi->pegawai->user->name }}</h2>
                    <p class="text-sm text-indigo-400">{{ $absensi->pegawai->jabatan }} — {{ $absensi->pegawai->divisi }}</p>
                    <p class="text-xs text-indigo-500 mt-0.5">NIP: {{ $absensi->pegawai->nip ?? '—' }}</p>
                </div>
                <div class="flex sm:flex-col items-center sm:items-end gap-2 sm:gap-0">
                    <span class="text-sm px-3 py-1.5 rounded-xl badge-{{ $absensi->status }}">
                        {{ ucfirst($absensi->status) }}
                    </span>
                    <p class="text-xs text-indigo-400 sm:mt-2">{{ \Carbon\Carbon::parse($absensi->tanggal)->isoFormat('dddd, D MMMM Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Time & Location -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 sm:gap-4">
            <div class="glass-card rounded-xl p-3 sm:p-4">
                <p class="text-xs text-indigo-400 mb-1">Jam Masuk</p>
                <p class="text-lg sm:text-xl font-bold text-white">{{ $absensi->jam_masuk ? \Carbon\Carbon::parse($absensi->jam_masuk)->format('H:i') : '—' }}</p>
            </div>
            <div class="glass-card rounded-xl p-3 sm:p-4">
                <p class="text-xs text-indigo-400 mb-1">Jam Pulang</p>
                <p class="text-lg sm:text-xl font-bold text-white">{{ $absensi->jam_pulang ? \Carbon\Carbon::parse($absensi->jam_pulang)->format('H:i') : '—' }}</p>
            </div>
            <div class="glass-card rounded-xl p-3 sm:p-4">
                <p class="text-xs text-indigo-400 mb-1">Jarak Masuk</p>
                <p class="text-lg sm:text-xl font-bold {{ $jarakMasuk !== null ? ($jarakMasuk <= $absensi->lokasi_kerja->radius_meter ? 'text-emerald-400' : 'text-red-400') : 'text-indigo-400' }}">
                    {{ $jarakMasuk !== null ? number_format($jarakMasuk, 0, ',', '.') . ' m' : '—' }}
                </p>
            </div>
            <div class="glass-card rounded-xl p-3 sm:p-4">
                <p class="text-xs text-indigo-400 mb-1">Jarak Pulang</p>
                <p class="text-lg sm:text-xl font-bold {{ $jarakPulang !== null ? ($jarakPulang <= $absensi->lokasi_kerja->radius_meter ? 'text-emerald-400' : 'text-red-400') : 'text-indigo-400' }}">
                    {{ $jarakPulang !== null ? number_format($jarakPulang, 0, ',', '.') . ' m' : '—' }}
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-5">
            <!-- Photos -->
            <div class="glass-card rounded-2xl p-4 sm:p-5">
                <h3 class="text-sm font-semibold text-white mb-4">Foto Absensi</h3>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <p class="text-xs text-indigo-400 mb-2">Foto Masuk</p>
                        @if($absensi->foto_masuk)
                            <img src="{{ Storage::url($absensi->foto_masuk) }}" alt="Foto Masuk"
                                class="w-full rounded-xl object-cover aspect-square border border-indigo-700/30">
                        @else
                            <div class="w-full aspect-square rounded-xl bg-gray-800/50 border border-indigo-700/20 flex items-center justify-center">
                                <p class="text-xs text-indigo-600">Tidak ada foto</p>
                            </div>
                        @endif
                    </div>
                    <div>
                        <p class="text-xs text-indigo-400 mb-2">Foto Pulang</p>
                        @if($absensi->foto_pulang)
                            <img src="{{ Storage::url($absensi->foto_pulang) }}" alt="Foto Pulang"
                                class="w-full rounded-xl object-cover aspect-square border border-indigo-700/30">
                        @else
                            <div class="w-full aspect-square rounded-xl bg-gray-800/50 border border-indigo-700/20 flex items-center justify-center">
                                <p class="text-xs text-indigo-600">Tidak ada foto</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Map Verification -->
            <div class="glass-card rounded-2xl p-4 sm:p-5">
                <h3 class="text-sm font-semibold text-white mb-3">Verifikasi Lokasi</h3>
                <div class="rounded-xl overflow-hidden border border-indigo-700/30 mb-3">
                    <div id="verifyMap" class="h-48 sm:h-52"></div>
                </div>
                <div class="text-xs text-indigo-400 space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-indigo-500 inline-block flex-shrink-0"></span>
                        <span>Lokasi Kantor: {{ $absensi->lokasi_kerja->nama_lokasi }} (radius {{ $absensi->lokasi_kerja->radius_meter }}m)</span>
                    </div>
                    @if($absensi->latitude_masuk)
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-emerald-500 inline-block flex-shrink-0"></span>
                        <span>Masuk: {{ number_format($absensi->latitude_masuk, 6) }}, {{ number_format($absensi->longitude_masuk, 6) }}</span>
                    </div>
                    @endif
                    @if($absensi->latitude_pulang)
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-violet-500 inline-block flex-shrink-0"></span>
                        <span>Pulang: {{ number_format($absensi->latitude_pulang, 6) }}, {{ number_format($absensi->longitude_pulang, 6) }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const offLat = {{ $absensi->lokasi_kerja->latitude }}, offLng = {{ $absensi->lokasi_kerja->longitude }};
        const map = L.map('verifyMap', { attributionControl: false }).setView([offLat, offLng], 16);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

        // Office radius circle
        L.circle([offLat, offLng], {
            radius: {{ $absensi->lokasi_kerja->radius_meter }},
            color: '#6366f1', fillColor: '#4f46e5', fillOpacity: 0.1, weight: 2
        }).addTo(map);
        L.circleMarker([offLat, offLng], { radius: 8, color: '#6366f1', fillColor: '#4f46e5', fillOpacity: 1 })
            .addTo(map).bindPopup('Kantor: {{ addslashes($absensi->lokasi_kerja->nama_lokasi) }}');

        @if($absensi->latitude_masuk && $absensi->longitude_masuk)
        L.circleMarker([{{ $absensi->latitude_masuk }}, {{ $absensi->longitude_masuk }}],
            { radius: 7, color: '#10b981', fillColor: '#34d399', fillOpacity: 1 })
            .addTo(map).bindPopup('Absen Masuk: {{ $absensi->jam_masuk ? \Carbon\Carbon::parse($absensi->jam_masuk)->format("H:i") : "" }}');
        @endif

        @if($absensi->latitude_pulang && $absensi->longitude_pulang)
        L.circleMarker([{{ $absensi->latitude_pulang }}, {{ $absensi->longitude_pulang }}],
            { radius: 7, color: '#8b5cf6', fillColor: '#a78bfa', fillOpacity: 1 })
            .addTo(map).bindPopup('Absen Pulang: {{ $absensi->jam_pulang ? \Carbon\Carbon::parse($absensi->jam_pulang)->format("H:i") : "" }}');
        @endif
    </script>
    @endpush
</x-layouts.app>
