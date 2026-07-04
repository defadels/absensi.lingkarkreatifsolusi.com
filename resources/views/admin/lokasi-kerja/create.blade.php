<x-layouts.app>
    <x-slot name="title">Tambah Lokasi Kerja</x-slot>

    <div class="max-w-3xl mx-auto space-y-5">
        <a href="{{ route('admin.lokasi-kerja.index') }}" class="text-indigo-400 hover:text-white transition-colors text-sm">← Kembali</a>

        <div class="glass-card rounded-2xl p-6">
            <h2 class="text-lg font-bold text-white mb-1">Tambah Lokasi Kerja</h2>
            <p class="text-sm text-indigo-300 mb-5">Klik pada peta untuk menentukan titik lokasi kantor, lalu atur radius jangkauan.</p>

            <!-- Interactive Map -->
            <div class="rounded-xl overflow-hidden border border-indigo-700/30 mb-5">
                <div id="mapPicker" class="h-72 w-full"></div>
                <div class="bg-indigo-950/50 px-4 py-2.5 flex items-center gap-4 text-xs text-indigo-400">
                    <span>🖱️ Klik peta untuk set lokasi</span>
                    <span>🔵 Drag pin untuk adjust</span>
                    <span id="coordDisplay">📍 Belum dipilih</span>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.lokasi-kerja.store') }}" class="space-y-5">
                @csrf

                <input type="hidden" name="latitude" id="lat" value="{{ old('latitude') }}">
                <input type="hidden" name="longitude" id="lng" value="{{ old('longitude') }}">

                <div>
                    <label class="block text-sm font-medium text-indigo-200 mb-1.5">Nama Lokasi</label>
                    <input type="text" name="nama_lokasi" value="{{ old('nama_lokasi') }}"
                        placeholder="Contoh: Kantor Pusat PT Lingkar Kreatif"
                        class="input-field w-full rounded-xl px-4 py-2.5 text-white text-sm @error('nama_lokasi') border-red-500/50 @enderror">
                    @error('nama_lokasi') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                @error('latitude') <p class="text-xs text-red-400">{{ $message }}</p> @enderror
                @error('longitude') <p class="text-xs text-red-400">{{ $message }}</p> @enderror

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-indigo-200 mb-1.5">Radius (meter)</label>
                        <input type="number" name="radius_meter" id="radiusInput" value="{{ old('radius_meter', 100) }}"
                            min="10" max="5000" step="10"
                            class="input-field w-full rounded-xl px-4 py-2.5 text-white text-sm @error('radius_meter') border-red-500/50 @enderror">
                        @error('radius_meter') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-indigo-200 mb-1.5">Jam Masuk Standar</label>
                        <input type="time" name="jam_masuk_standar" value="{{ old('jam_masuk_standar', '08:00') }}"
                            class="input-field w-full rounded-xl px-4 py-2.5 text-white text-sm @error('jam_masuk_standar') border-red-500/50 @enderror">
                        @error('jam_masuk_standar') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-indigo-200 mb-1.5">Toleransi Terlambat (menit)</label>
                        <input type="number" name="toleransi_menit" value="{{ old('toleransi_menit', 15) }}"
                            min="0" max="120"
                            class="input-field w-full rounded-xl px-4 py-2.5 text-white text-sm @error('toleransi_menit') border-red-500/50 @enderror">
                        @error('toleransi_menit') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex gap-3 pt-2">
                    <a href="{{ route('admin.lokasi-kerja.index') }}" class="flex-1 py-2.5 rounded-xl text-center text-sm text-indigo-300 border border-indigo-700/50 hover:border-indigo-500 transition-all">Batal</a>
                    <button type="submit" id="saveBtn" disabled
                        class="flex-1 py-2.5 rounded-xl text-sm font-semibold bg-gray-800 text-gray-500 cursor-not-allowed transition-all">
                        Simpan Lokasi
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('styles')
    <style>
        .input-field option { background: #1e1b4b; }
        #mapPicker { cursor: crosshair; }
    </style>
    @endpush

    @push('scripts')
    <script>
        const map = L.map('mapPicker').setView([-6.2088, 106.8456], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        let marker = null, circle = null;
        let radius = parseInt(document.getElementById('radiusInput').value) || 100;

        function setLocation(lat, lng) {
            document.getElementById('lat').value = lat.toFixed(8);
            document.getElementById('lng').value = lng.toFixed(8);
            document.getElementById('coordDisplay').textContent = `📍 ${lat.toFixed(6)}, ${lng.toFixed(6)}`;

            if (marker) map.removeLayer(marker);
            if (circle) map.removeLayer(circle);

            marker = L.marker([lat, lng], { draggable: true }).addTo(map);
            circle = L.circle([lat, lng], {
                radius, color: '#6366f1', fillColor: '#4f46e5', fillOpacity: 0.15, weight: 2
            }).addTo(map);

            marker.on('dragend', e => {
                const pos = e.target.getLatLng();
                setLocation(pos.lat, pos.lng);
            });

            document.getElementById('saveBtn').disabled = false;
            document.getElementById('saveBtn').className = 'flex-1 py-2.5 rounded-xl text-sm font-semibold btn-primary text-white cursor-pointer transition-all';
        }

        map.on('click', e => setLocation(e.latlng.lat, e.latlng.lng));

        document.getElementById('radiusInput').addEventListener('input', e => {
            radius = parseInt(e.target.value) || 100;
            if (circle) {
                circle.setRadius(radius);
            }
        });

        // Try geolocation to center map
        navigator.geolocation.getCurrentPosition(pos => {
            map.setView([pos.coords.latitude, pos.coords.longitude], 16);
        }, () => {});

        // Restore if old() values exist
        @if(old('latitude') && old('longitude'))
        setLocation({{ old('latitude') }}, {{ old('longitude') }});
        @endif
    </script>
    @endpush
</x-layouts.app>
