<x-layouts.app>
    <x-slot name="title">Ajukan Izin</x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="glass-card rounded-2xl p-6">
            <h2 class="text-lg font-bold text-white mb-1">Pengajuan Izin / Cuti</h2>
            <p class="text-sm text-indigo-300 mb-6">Isi formulir berikut dan tunggu persetujuan dari HRD</p>

            <form method="POST" action="{{ route('pegawai.izin.store') }}" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <!-- Jenis Izin -->
                <div>
                    <label class="block text-sm font-medium text-indigo-200 mb-2">Jenis Izin</label>
                    <div class="grid grid-cols-3 gap-3">
                        @foreach(['sakit' => ['🤒','Sakit'], 'cuti' => ['🌴','Cuti'], 'dinas' => ['✈️','Dinas Luar']] as $val => [$icon, $label])
                            <label class="cursor-pointer">
                                <input type="radio" name="jenis_izin" value="{{ $val }}" class="sr-only peer" {{ old('jenis_izin') === $val ? 'checked' : '' }} required>
                                <div class="peer-checked:border-indigo-500 peer-checked:bg-indigo-500/20 border border-indigo-700/30 rounded-xl p-3 text-center transition-all hover:border-indigo-600/50">
                                    <p class="text-2xl mb-1">{{ $icon }}</p>
                                    <p class="text-xs font-medium text-indigo-200">{{ $label }}</p>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('jenis_izin')
                        <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="tanggal_mulai" class="block text-sm font-medium text-indigo-200 mb-1.5">Tanggal Mulai</label>
                        <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}"
                            min="{{ now()->toDateString() }}"
                            class="input-field w-full rounded-xl px-4 py-2.5 text-white text-sm @error('tanggal_mulai') border-red-500/50 @enderror">
                        @error('tanggal_mulai') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="tanggal_selesai" class="block text-sm font-medium text-indigo-200 mb-1.5">Tanggal Selesai</label>
                        <input type="date" id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}"
                            min="{{ now()->toDateString() }}"
                            class="input-field w-full rounded-xl px-4 py-2.5 text-white text-sm @error('tanggal_selesai') border-red-500/50 @enderror">
                        @error('tanggal_selesai') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Duration display -->
                <div id="durasiInfo" class="hidden text-xs text-indigo-300 bg-indigo-900/30 border border-indigo-700/30 rounded-xl px-4 py-2.5"></div>

                <!-- Keterangan -->
                <div>
                    <label for="keterangan" class="block text-sm font-medium text-indigo-200 mb-1.5">Keterangan</label>
                    <textarea id="keterangan" name="keterangan" rows="3" placeholder="Jelaskan alasan izin Anda secara singkat..."
                        class="input-field w-full rounded-xl px-4 py-2.5 text-white text-sm placeholder-indigo-400/50 resize-none @error('keterangan') border-red-500/50 @enderror">{{ old('keterangan') }}</textarea>
                    @error('keterangan') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                <!-- Bukti File -->
                <div>
                    <label class="block text-sm font-medium text-indigo-200 mb-1.5">
                        Bukti Pendukung <span class="text-indigo-400 font-normal">(Opsional)</span>
                    </label>
                    <label class="flex items-center gap-3 px-4 py-3 border border-dashed border-indigo-700/50 rounded-xl cursor-pointer hover:border-indigo-500/70 transition-colors">
                        <svg class="w-5 h-5 text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                        </svg>
                        <span class="text-sm text-indigo-300" id="fileLabel">Klik untuk pilih file (JPG/PNG/PDF, maks 2MB)</span>
                        <input type="file" name="bukti_file" class="sr-only" accept=".jpg,.jpeg,.png,.pdf"
                            onchange="document.getElementById('fileLabel').textContent = this.files[0]?.name || 'Pilih file'">
                    </label>
                    @error('bukti_file') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                <div class="flex gap-3 pt-2">
                    <a href="{{ route('pegawai.dashboard') }}" class="flex-1 py-2.5 rounded-xl text-center text-sm text-indigo-300 border border-indigo-700/50 hover:border-indigo-500 transition-all">
                        Batal
                    </a>
                    <button type="submit" class="flex-1 btn-primary py-2.5 rounded-xl text-sm text-white font-semibold">
                        Kirim Pengajuan
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        const mulai = document.getElementById('tanggal_mulai');
        const selesai = document.getElementById('tanggal_selesai');
        function updateDurasi() {
            if (mulai.value && selesai.value) {
                const d1 = new Date(mulai.value), d2 = new Date(selesai.value);
                const diff = Math.round((d2 - d1) / 86400000) + 1;
                const info = document.getElementById('durasiInfo');
                if (diff > 0) {
                    info.textContent = `📅 Durasi izin: ${diff} hari kerja`;
                    info.classList.remove('hidden');
                } else {
                    info.classList.add('hidden');
                }
            }
        }
        mulai.addEventListener('change', updateDurasi);
        selesai.addEventListener('change', updateDurasi);
        mulai.addEventListener('change', () => { if (selesai.value < mulai.value) selesai.value = mulai.value; });
    </script>
    @endpush
</x-layouts.app>
