<x-layouts.app>
    <x-slot name="title">Absen Masuk</x-slot>

    <div class="max-w-2xl mx-auto space-y-4 sm:space-y-6">
        <!-- Header -->
        <div class="glass-card rounded-2xl p-4 sm:p-5 flex items-center gap-3 sm:gap-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-500/20 flex items-center justify-center">
                <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14" />
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-white">Absensi Masuk</h2>
                <p class="text-xs sm:text-sm text-indigo-300">Radius: {{ $lokasiAktif->radius_meter }}m • Masuk: {{ substr($lokasiAktif->jam_masuk_standar, 0, 5) }}</p>
            </div>
            <div class="ml-auto text-right">
                <p class="text-xl sm:text-2xl font-bold text-white tabular-nums" id="absenTime">--:--</p>
                <p class="text-xs text-indigo-400">WIB</p>
            </div>
        </div>

        <!-- Status Messages -->
        <div id="statusMsg" class="hidden"></div>
        <div id="locationStatus" class="glass-card rounded-xl p-4 flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-amber-500/20 flex items-center justify-center flex-shrink-0" id="locIcon">
                <svg class="w-4 h-4 text-amber-400 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-amber-300" id="locText">Mendapatkan lokasi GPS...</p>
                <p class="text-xs text-indigo-400" id="locDetail">Pastikan GPS aktif dan berikan izin lokasi</p>
            </div>
        </div>

        <!-- Camera -->
        <div class="glass-card rounded-2xl overflow-hidden">
            <div class="relative bg-gray-950 aspect-video" id="cameraContainer">
                <video id="videoFeed" class="w-full h-full object-cover" autoplay playsinline muted></video>
                <canvas id="capturedPhoto" class="w-full h-full object-cover hidden absolute inset-0"></canvas>

                <!-- Overlay frame guide -->
                <div class="absolute inset-0 flex items-center justify-center pointer-events-none" id="frameGuide">
                    <div class="w-40 h-48 border-2 border-indigo-400/60 rounded-2xl flex items-end justify-center pb-2">
                        <p class="text-xs text-indigo-300/70 text-center leading-tight">Posisikan wajah<br>di dalam kotak</p>
                    </div>
                </div>

                <!-- Camera off state -->
                <div id="cameraOff" class="absolute inset-0 flex flex-col items-center justify-center bg-gray-950 text-center p-6">
                    <svg class="w-12 h-12 text-indigo-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                    <p class="text-sm text-indigo-300 mb-3">Kamera perlu diaktifkan untuk absensi</p>
                    <button id="startCamera" class="btn-primary px-5 py-2 rounded-xl text-sm text-white font-medium">
                        Aktifkan Kamera
                    </button>
                </div>
            </div>

            <!-- Camera controls -->
            <div class="p-4 border-t border-indigo-800/30 flex items-center gap-3">
                <button id="retakeBtn" class="hidden px-4 py-2 rounded-xl text-sm text-indigo-300 border border-indigo-700/50 hover:border-indigo-500 transition-all">
                    ↺ Ambil Ulang
                </button>
                <button id="captureBtn" class="hidden flex-1 btn-primary py-2.5 rounded-xl text-sm text-white font-semibold">
                    📸 Ambil Foto
                </button>
            </div>
        </div>

        <!-- Submit -->
        <button id="submitBtn" disabled
            class="w-full py-3.5 rounded-xl font-semibold text-sm transition-all duration-200 bg-gray-800 text-gray-500 cursor-not-allowed"
            onclick="submitAbsensi()">
            ✓ Kirim Absen Masuk
        </button>

        <p class="text-center text-xs text-indigo-500">
            Foto diambil langsung dari kamera. Tidak bisa upload dari galeri.
        </p>
    </div>

    @push('scripts')
    <script>
        let stream = null;
        let fotoBase64 = null;
        let currentLat = null;
        let currentLng = null;
        let locationReady = false;

        const video = document.getElementById('videoFeed');
        const canvas = document.getElementById('capturedPhoto');
        const ctx = canvas.getContext('2d');

        // Live clock
        function tick() {
            document.getElementById('absenTime').textContent =
                new Date().toLocaleTimeString('id-ID', {hour:'2-digit',minute:'2-digit'});
        }
        tick(); setInterval(tick, 1000);

        // Get GPS location
        navigator.geolocation.watchPosition(pos => {
            currentLat = pos.coords.latitude;
            currentLng = pos.coords.longitude;
            locationReady = true;

            document.getElementById('locIcon').innerHTML = `
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>`;
            document.getElementById('locIcon').className = 'w-8 h-8 rounded-lg bg-emerald-500/20 flex items-center justify-center flex-shrink-0';
            document.getElementById('locText').textContent = 'Lokasi GPS berhasil didapat';
            document.getElementById('locText').className = 'text-sm font-medium text-emerald-300';
            document.getElementById('locDetail').textContent =
                `Lat: ${currentLat.toFixed(6)}, Lng: ${currentLng.toFixed(6)} ± ${Math.round(pos.coords.accuracy)}m`;

            checkReady();
        }, err => {
            document.getElementById('locText').textContent = 'Gagal mendapatkan lokasi: ' + err.message;
            document.getElementById('locText').className = 'text-sm font-medium text-red-400';
        }, { enableHighAccuracy: true, timeout: 30000, maximumAge: 0 });

        // Start Camera
        document.getElementById('startCamera').addEventListener('click', async () => {
            try {
                stream = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: 'user', width: { ideal: 1280 }, height: { ideal: 720 } },
                    audio: false
                });
                video.srcObject = stream;
                document.getElementById('cameraOff').classList.add('hidden');
                document.getElementById('captureBtn').classList.remove('hidden');
                document.getElementById('captureBtn').classList.add('flex');
                checkReady();
            } catch(e) {
                alert('Tidak dapat mengakses kamera: ' + e.message);
            }
        });

        // Capture photo
        document.getElementById('captureBtn').addEventListener('click', () => {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            ctx.drawImage(video, 0, 0);
            fotoBase64 = canvas.toDataURL('image/jpeg', 0.85);

            video.classList.add('hidden');
            canvas.classList.remove('hidden');
            document.getElementById('frameGuide').classList.add('hidden');
            document.getElementById('captureBtn').classList.add('hidden');
            document.getElementById('retakeBtn').classList.remove('hidden');
            checkReady();
        });

        // Retake
        document.getElementById('retakeBtn').addEventListener('click', () => {
            fotoBase64 = null;
            canvas.classList.add('hidden');
            video.classList.remove('hidden');
            document.getElementById('frameGuide').classList.remove('hidden');
            document.getElementById('captureBtn').classList.remove('hidden');
            document.getElementById('retakeBtn').classList.add('hidden');
            checkReady();
        });

        function checkReady() {
            const ready = locationReady && fotoBase64 && stream;
            const btn = document.getElementById('submitBtn');
            if (ready) {
                btn.disabled = false;
                btn.className = 'w-full py-3.5 rounded-xl font-semibold text-sm transition-all duration-200 btn-primary text-white cursor-pointer';
            } else {
                btn.disabled = true;
                btn.className = 'w-full py-3.5 rounded-xl font-semibold text-sm transition-all duration-200 bg-gray-800 text-gray-500 cursor-not-allowed';
            }
        }

        async function submitAbsensi() {
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.textContent = 'Mengirim...';

            try {
                const res = await fetch('{{ route("pegawai.absensi.masuk.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ latitude: currentLat, longitude: currentLng, foto: fotoBase64 }),
                });

                const data = await res.json();

                if (res.ok && data.success) {
                    document.getElementById('statusMsg').className = 'rounded-xl p-4 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-sm font-medium';
                    document.getElementById('statusMsg').textContent = '✓ ' + data.message;
                    document.getElementById('statusMsg').classList.remove('hidden');
                    if (stream) stream.getTracks().forEach(t => t.stop());
                    setTimeout(() => window.location.href = '{{ route("pegawai.dashboard") }}', 2000);
                } else {
                    throw new Error(data.error || 'Terjadi kesalahan.');
                }
            } catch(e) {
                document.getElementById('statusMsg').className = 'rounded-xl p-4 bg-red-500/10 border border-red-500/30 text-red-400 text-sm font-medium';
                document.getElementById('statusMsg').textContent = '✗ ' + e.message;
                document.getElementById('statusMsg').classList.remove('hidden');
                btn.disabled = false;
                btn.textContent = '✓ Kirim Absen Masuk';
                checkReady();
            }
        }
    </script>
    @endpush
</x-layouts.app>
