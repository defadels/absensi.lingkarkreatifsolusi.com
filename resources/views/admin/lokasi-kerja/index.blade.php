<x-layouts.app>
    <x-slot name="title">Lokasi Kerja</x-slot>

    <div class="space-y-4">
        <div class="flex justify-end">
            <a href="{{ route('admin.lokasi-kerja.create') }}" class="btn-primary px-5 py-2.5 rounded-xl text-sm text-white font-semibold inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Tambah Lokasi
            </a>
        </div>

        <div class="grid gap-4">
            @forelse($lokasi as $item)
            <div class="glass-card rounded-2xl overflow-hidden">
                <div class="flex flex-col sm:flex-row sm:items-start gap-3 sm:gap-4 p-4 sm:p-5">
                    <div class="w-10 h-10 rounded-xl {{ $item->is_active ? 'bg-emerald-500/20' : 'bg-gray-700/50' }} flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 {{ $item->is_active ? 'text-emerald-400' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <h3 class="font-semibold text-white">{{ $item->nama_lokasi }}</h3>
                            <span class="text-xs px-2 py-0.5 rounded-full {{ $item->is_active ? 'bg-emerald-500/20 text-emerald-400' : 'bg-gray-700 text-gray-400' }}">
                                {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-5 gap-x-6 gap-y-1 text-xs text-indigo-400">
                            <span>📍 {{ number_format($item->latitude, 6) }}, {{ number_format($item->longitude, 6) }}</span>
                            <span>⭕ Radius: {{ $item->radius_meter }}m</span>
                            <span>🕗 Masuk: {{ substr($item->jam_masuk_standar, 0, 5) }}</span>
                            <span>⏱ Toleransi: {{ $item->toleransi_menit }} menit</span>
                            <span class="font-medium {{ $item->pegawai_count > 0 ? 'text-indigo-300' : 'text-amber-400' }}">
                                👥 {{ $item->pegawai_count }} karyawan
                            </span>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-2 flex-shrink-0 sm:flex-col sm:items-end">
                        <form method="POST" action="{{ route('admin.lokasi-kerja.toggle', $item) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="text-xs px-3 py-1.5 rounded-lg transition-colors border
                                {{ $item->is_active
                                    ? 'bg-gray-700/50 text-gray-300 border-gray-600/30 hover:bg-gray-700'
                                    : 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20 hover:bg-emerald-500/20' }}">
                                {{ $item->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                        </form>
                        <a href="{{ route('admin.lokasi-kerja.edit', $item) }}"
                           class="text-xs px-3 py-1.5 rounded-lg bg-indigo-600/20 text-indigo-300 hover:bg-indigo-600/40 transition-colors border border-indigo-600/20">
                            Edit
                        </a>
                        <form method="POST" action="{{ route('admin.lokasi-kerja.destroy', $item) }}" onsubmit="return confirm('Hapus lokasi ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs px-3 py-1.5 rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500/20 transition-colors border border-red-500/20">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Mini Map -->
                <div id="map-{{ $item->id }}" class="h-48 border-t border-indigo-800/20"></div>
            </div>

            @push('scripts')
            <script>
                (function() {
                    const map = L.map('map-{{ $item->id }}', { zoomControl: false, attributionControl: false });
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

                    const lat = {{ $item->latitude }}, lng = {{ $item->longitude }}, radius = {{ $item->radius_meter }};

                    const marker = L.marker([lat, lng]).addTo(map);
                    L.circle([lat, lng], { radius, color: '#6366f1', fillColor: '#4f46e5', fillOpacity: 0.15, weight: 2 }).addTo(map);
                    map.setView([lat, lng], 17);
                })();
            </script>
            @endpush
            @empty
            <div class="glass-card rounded-2xl p-12 text-center">
                <svg class="w-12 h-12 text-indigo-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                </svg>
                <p class="text-indigo-400 text-sm mb-4">Belum ada lokasi kerja. Tambahkan sekarang agar pegawai bisa absensi.</p>
                <a href="{{ route('admin.lokasi-kerja.create') }}" class="btn-primary inline-block px-5 py-2.5 rounded-xl text-sm text-white font-medium">
                    Tambah Lokasi Pertama
                </a>
            </div>
            @endforelse
        </div>
    </div>
</x-layouts.app>
