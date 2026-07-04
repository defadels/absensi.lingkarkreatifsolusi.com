<x-layouts.app>
    <x-slot name="title">Edit Lokasi Kerja</x-slot>

    <div class="max-w-3xl mx-auto space-y-5">
        <a href="{{ route('admin.lokasi-kerja.index') }}" class="text-indigo-400 hover:text-white transition-colors text-sm">← Kembali</a>

        <div class="glass-card rounded-2xl p-6">
            <h2 class="text-lg font-bold text-white mb-1">Edit Lokasi Kerja</h2>
            <p class="text-sm text-indigo-300 mb-5">Klik pada peta atau geser pin untuk mengubah posisi lokasi.</p>

            <div class="rounded-xl overflow-hidden border border-indigo-700/30 mb-5">
                <div id="mapPicker" class="h-72 w-full"></div>
                <div class="bg-indigo-950/50 px-4 py-2.5 flex items-center gap-4 text-xs text-indigo-400">
                    <span>🖱️ Klik peta untuk set lokasi</span>
                    <span>🔵 Drag pin untuk adjust</span>
                    <span id="coordDisplay">📍 {{ $lokasiKerja->latitude }}, {{ $lokasiKerja->longitude }}</span>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.lokasi-kerja.update', $lokasiKerja) }}" class="space-y-5">
                @csrf @method('PATCH')

                <input type="hidden" name="latitude" id="lat" value="{{ old('latitude', $lokasiKerja->latitude) }}">
                <input type="hidden" name="longitude" id="lng" value="{{ old('longitude', $lokasiKerja->longitude) }}">

                <div>
                    <label class="block text-sm font-medium text-indigo-200 mb-1.5">Nama Lokasi</label>
                    <input type="text" name="nama_lokasi" value="{{ old('nama_lokasi', $lokasiKerja->nama_lokasi) }}"
                        class="input-field w-full rounded-xl px-4 py-2.5 text-white text-sm @error('nama_lokasi') border-red-500/50 @enderror">
                    @error('nama_lokasi') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-indigo-200 mb-1.5">Radius (meter)</label>
                        <input type="number" name="radius_meter" id="radiusInput"
                            value="{{ old('radius_meter', $lokasiKerja->radius_meter) }}" min="10" max="5000" step="10"
                            class="input-field w-full rounded-xl px-4 py-2.5 text-white text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-indigo-200 mb-1.5">Jam Masuk Standar</label>
                        <input type="time" name="jam_masuk_standar"
                            value="{{ old('jam_masuk_standar', substr($lokasiKerja->jam_masuk_standar, 0, 5)) }}"
                            class="input-field w-full rounded-xl px-4 py-2.5 text-white text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-indigo-200 mb-1.5">Toleransi Terlambat (menit)</label>
                        <input type="number" name="toleransi_menit"
                            value="{{ old('toleransi_menit', $lokasiKerja->toleransi_menit) }}" min="0" max="120"
                            class="input-field w-full rounded-xl px-4 py-2.5 text-white text-sm">
                    </div>
                </div>

                <div class="flex gap-3 pt-2">
                    <a href="{{ route('admin.lokasi-kerja.index') }}" class="flex-1 py-2.5 rounded-xl text-center text-sm text-indigo-300 border border-indigo-700/50 hover:border-indigo-500 transition-all">Batal</a>
                    <button type="submit" class="flex-1 btn-primary py-2.5 rounded-xl text-sm text-white font-semibold">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        const initLat = {{ $lokasiKerja->latitude }}, initLng = {{ $lokasiKerja->longitude }};
        let radius = parseInt(document.getElementById('radiusInput').value) || 100;

        const map = L.map('mapPicker').setView([initLat, initLng], 16);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

        let marker = L.marker([initLat, initLng], { draggable: true }).addTo(map);
        let circle = L.circle([initLat, initLng], {
            radius, color: '#6366f1', fillColor: '#4f46e5', fillOpacity: 0.15, weight: 2
        }).addTo(map);

        function updateLocation(lat, lng) {
            document.getElementById('lat').value = lat.toFixed(8);
            document.getElementById('lng').value = lng.toFixed(8);
            document.getElementById('coordDisplay').textContent = `📍 ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
            marker.setLatLng([lat, lng]);
            circle.setLatLng([lat, lng]);
        }

        marker.on('dragend', e => {
            const pos = e.target.getLatLng();
            updateLocation(pos.lat, pos.lng);
        });
        map.on('click', e => updateLocation(e.latlng.lat, e.latlng.lng));

        document.getElementById('radiusInput').addEventListener('input', e => {
            radius = parseInt(e.target.value) || 100;
            circle.setRadius(radius);
        });
    </script>
    @endpush
</x-layouts.app>
