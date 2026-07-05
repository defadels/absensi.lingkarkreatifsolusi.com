<x-layouts.app>
    <x-slot name="title">Edit Lokasi Kerja</x-slot>

    <div class="max-w-3xl mx-auto space-y-5">
        <a href="{{ route('admin.lokasi-kerja.index') }}" class="text-indigo-400 hover:text-white transition-colors text-sm inline-flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali
        </a>

        <div class="glass-card rounded-2xl p-6">
            <h2 class="text-lg font-bold text-white mb-1">Edit Lokasi Kerja</h2>
            <p class="text-sm text-indigo-300 mb-6">Cari alamat lewat kotak pencarian, atau klik langsung pada peta.</p>

            {{-- Search bar + Lokasi Saya --}}
            <div class="flex gap-2 mb-4">
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                        </svg>
                    </span>
                    <input
                        id="searchInput"
                        type="text"
                        placeholder="Cari alamat atau nama tempat... (min. 3 huruf)"
                        autocomplete="off"
                        class="input-field w-full rounded-xl pl-10 pr-4 py-2.5 text-white text-sm placeholder-indigo-400/60"
                    >
                    {{-- Dropdown hasil pencarian --}}
                    <div id="searchResults"
                         class="hidden absolute top-full left-0 right-0 mt-1 rounded-xl overflow-hidden z-50 border border-indigo-700/50"
                         style="background: #1e1b4b; max-height: 240px; overflow-y: auto;">
                    </div>
                </div>
                <button
                    type="button"
                    id="myLocationBtn"
                    title="Gunakan lokasi saat ini"
                    class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium text-indigo-200 border border-indigo-700/50 hover:border-indigo-500 hover:bg-indigo-600/20 transition-all whitespace-nowrap"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                    </svg>
                    Lokasi Saya
                </button>
            </div>

            {{-- Lat / Lng display --}}
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-semibold text-indigo-400 mb-1.5 uppercase tracking-wide">Latitude</label>
                    <div id="latDisplay"
                         class="rounded-xl px-4 py-2.5 text-sm font-mono text-indigo-300 border border-indigo-700/40"
                         style="background: rgba(30,27,75,0.6)">
                        {{ $lokasiKerja->latitude }}
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-indigo-400 mb-1.5 uppercase tracking-wide">Longitude</label>
                    <div id="lngDisplay"
                         class="rounded-xl px-4 py-2.5 text-sm font-mono text-indigo-300 border border-indigo-700/40"
                         style="background: rgba(30,27,75,0.6)">
                        {{ $lokasiKerja->longitude }}
                    </div>
                </div>
            </div>

            {{-- Map --}}
            <div class="rounded-xl overflow-hidden border border-indigo-700/30 mb-6" style="height: 340px;">
                <div id="mapPicker" class="w-full h-full"></div>
            </div>

            {{-- Form --}}
            <form method="POST" action="{{ route('admin.lokasi-kerja.update', $lokasiKerja) }}" class="space-y-5">
                @csrf @method('PATCH')

                <input type="hidden" name="latitude" id="lat"
                    value="{{ old('latitude', $lokasiKerja->latitude) }}">
                <input type="hidden" name="longitude" id="lng"
                    value="{{ old('longitude', $lokasiKerja->longitude) }}">

                @error('latitude') <p class="text-xs text-red-400 -mt-3">Titik lokasi belum dipilih di peta.</p> @enderror

                <div>
                    <label class="block text-sm font-medium text-indigo-200 mb-1.5">Nama Lokasi <span class="text-red-400">*</span></label>
                    <input type="text" name="nama_lokasi"
                        value="{{ old('nama_lokasi', $lokasiKerja->nama_lokasi) }}"
                        class="input-field w-full rounded-xl px-4 py-2.5 text-white text-sm @error('nama_lokasi') border-red-500/50 @enderror">
                    @error('nama_lokasi') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-indigo-200 mb-1.5">Radius (meter) <span class="text-red-400">*</span></label>
                        <input type="number" name="radius_meter" id="radiusInput"
                            value="{{ old('radius_meter', $lokasiKerja->radius_meter) }}"
                            min="10" max="5000" step="10"
                            class="input-field w-full rounded-xl px-4 py-2.5 text-white text-sm @error('radius_meter') border-red-500/50 @enderror">
                        @error('radius_meter') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-indigo-200 mb-1.5">Jam Masuk Standar <span class="text-red-400">*</span></label>
                        <input type="time" name="jam_masuk_standar"
                            value="{{ old('jam_masuk_standar', substr($lokasiKerja->jam_masuk_standar, 0, 5)) }}"
                            class="input-field w-full rounded-xl px-4 py-2.5 text-white text-sm @error('jam_masuk_standar') border-red-500/50 @enderror">
                        @error('jam_masuk_standar') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-indigo-200 mb-1.5">Toleransi Terlambat (menit)</label>
                        <input type="number" name="toleransi_menit"
                            value="{{ old('toleransi_menit', $lokasiKerja->toleransi_menit) }}"
                            min="0" max="120"
                            class="input-field w-full rounded-xl px-4 py-2.5 text-white text-sm @error('toleransi_menit') border-red-500/50 @enderror">
                        @error('toleransi_menit') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex gap-3 pt-2">
                    <a href="{{ route('admin.lokasi-kerja.index') }}"
                       class="flex-1 py-2.5 rounded-xl text-center text-sm text-indigo-300 border border-indigo-700/50 hover:border-indigo-500 transition-all">
                        Batal
                    </a>
                    <button type="submit"
                        class="flex-1 btn-primary py-2.5 rounded-xl text-sm text-white font-semibold">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('styles')
    <style>
        #mapPicker { cursor: crosshair; }
        .search-result-item {
            padding: 10px 14px;
            cursor: pointer;
            border-bottom: 1px solid rgba(99,102,241,0.15);
            font-size: 13px;
            color: #c7d2fe;
            transition: background 0.15s;
        }
        .search-result-item:last-child { border-bottom: none; }
        .search-result-item:hover { background: rgba(99,102,241,0.25); }
        .search-result-item .result-name { font-weight: 500; color: #e0e7ff; }
        .search-result-item .result-detail { font-size: 11px; color: #818cf8; margin-top: 2px; }
        #myLocationBtn.loading { opacity: 0.6; pointer-events: none; }
    </style>
    @endpush

    @push('scripts')
    <script>
    (function () {
        /* ── Initial values from DB ── */
        const initLat = {{ old('latitude', $lokasiKerja->latitude) }};
        const initLng = {{ old('longitude', $lokasiKerja->longitude) }};
        let radius    = parseInt(document.getElementById('radiusInput').value) || 100;
        let lat = initLat, lng = initLng;
        let searchTimer = null;
        let map, marker, circle;

        /* ── Init Map pre-loaded ── */
        map = L.map('mapPicker', { zoomControl: true }).setView([initLat, initLng], 17);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        /* ── Draw initial marker & circle ── */
        marker = L.marker([initLat, initLng], { draggable: true }).addTo(map);
        circle = L.circle([initLat, initLng], {
            radius,
            color: '#6366f1', fillColor: '#4f46e5',
            fillOpacity: 0.15, weight: 2
        }).addTo(map);

        marker.on('dragend', e => {
            const p = e.target.getLatLng();
            setLocation(p.lat, p.lng);
        });

        /* ── Helpers ── */
        function setLocation(newLat, newLng, zoom) {
            lat = newLat; lng = newLng;

            document.getElementById('lat').value = lat.toFixed(8);
            document.getElementById('lng').value  = lng.toFixed(8);

            document.getElementById('latDisplay').textContent = lat.toFixed(8);
            document.getElementById('lngDisplay').textContent = lng.toFixed(8);

            marker.setLatLng([lat, lng]);
            circle.setLatLng([lat, lng]);

            if (zoom) map.setView([lat, lng], zoom);
        }

        /* ── Map click ── */
        map.on('click', e => setLocation(e.latlng.lat, e.latlng.lng));

        /* ── Radius change ── */
        document.getElementById('radiusInput').addEventListener('input', e => {
            radius = parseInt(e.target.value) || 100;
            circle.setRadius(radius);
        });

        /* ── Geolocation ── */
        document.getElementById('myLocationBtn').addEventListener('click', function () {
            if (!navigator.geolocation) {
                alert('Browser Anda tidak mendukung geolocation.');
                return;
            }
            this.classList.add('loading');
            this.innerHTML = `<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
            </svg> Mendeteksi...`;

            navigator.geolocation.getCurrentPosition(
                pos => {
                    setLocation(pos.coords.latitude, pos.coords.longitude, 17);
                    this.classList.remove('loading');
                    this.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                    </svg> Lokasi Saya`;
                },
                err => {
                    alert('Gagal mendapatkan lokasi: ' + err.message);
                    this.classList.remove('loading');
                    this.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                    </svg> Lokasi Saya`;
                },
                { enableHighAccuracy: true, timeout: 15000 }
            );
        });

        /* ── Nominatim Address Search ── */
        const searchInput   = document.getElementById('searchInput');
        const searchResults = document.getElementById('searchResults');

        searchInput.addEventListener('input', function () {
            clearTimeout(searchTimer);
            const q = this.value.trim();
            if (q.length < 3) { searchResults.classList.add('hidden'); return; }
            searchTimer = setTimeout(() => doSearch(q), 500);
        });

        searchInput.addEventListener('keydown', e => {
            if (e.key === 'Escape') { searchResults.classList.add('hidden'); searchInput.blur(); }
        });

        document.addEventListener('click', e => {
            if (!e.target.closest('#searchInput') && !e.target.closest('#searchResults')) {
                searchResults.classList.add('hidden');
            }
        });

        async function doSearch(query) {
            searchResults.innerHTML = `<div class="search-result-item text-indigo-400">Mencari...</div>`;
            searchResults.classList.remove('hidden');

            try {
                const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=6&addressdetails=1`;
                const res = await fetch(url, {
                    headers: { 'Accept-Language': 'id', 'User-Agent': 'AbsensiLKS/1.0' }
                });
                const data = await res.json();

                if (!data.length) {
                    searchResults.innerHTML = `<div class="search-result-item text-indigo-500">Tidak ditemukan hasil untuk "<b>${query}</b>"</div>`;
                    return;
                }

                searchResults.innerHTML = '';
                data.forEach(item => {
                    const el = document.createElement('div');
                    el.className = 'search-result-item';
                    const name   = item.name || item.display_name.split(',')[0];
                    const detail = item.display_name;
                    el.innerHTML = `<div class="result-name">${name}</div><div class="result-detail">${detail}</div>`;
                    el.addEventListener('click', () => {
                        setLocation(parseFloat(item.lat), parseFloat(item.lon), 17);
                        searchInput.value = name;
                        searchResults.classList.add('hidden');
                    });
                    searchResults.appendChild(el);
                });
            } catch (err) {
                searchResults.innerHTML = `<div class="search-result-item text-red-400">Gagal mencari: ${err.message}</div>`;
            }
        }
    })();
    </script>
    @endpush
</x-layouts.app>
