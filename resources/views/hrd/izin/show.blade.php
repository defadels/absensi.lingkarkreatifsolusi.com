<x-layouts.app>
    <x-slot name="title">Detail Pengajuan Izin</x-slot>

    <div class="max-w-2xl mx-auto space-y-5">
        <a href="{{ route('hrd.izin.index') }}" class="text-indigo-400 hover:text-white transition-colors text-sm">← Kembali</a>

        <!-- Header -->
        <div class="glass-card rounded-2xl p-5">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center text-lg font-bold text-white flex-shrink-0">
                    {{ strtoupper(substr($izin->pegawai->user->name, 0, 2)) }}
                </div>
                <div class="flex-1">
                    <h2 class="text-lg font-bold text-white">{{ $izin->pegawai->user->name }}</h2>
                    <p class="text-sm text-indigo-400">{{ $izin->pegawai->jabatan }} — {{ $izin->pegawai->divisi }}</p>
                    <p class="text-xs text-indigo-500">{{ $izin->pegawai->user->email }}</p>
                </div>
                <span class="text-sm px-3 py-1.5 rounded-xl badge-{{ $izin->status_persetujuan }}">
                    {{ ucfirst($izin->status_persetujuan) }}
                </span>
            </div>
        </div>

        <!-- Izin Details -->
        <div class="glass-card rounded-2xl p-5 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-indigo-400 mb-1">Jenis Izin</p>
                    <p class="font-semibold text-white capitalize">
                        {{ $izin->jenis_izin === 'dinas' ? 'Dinas Luar Kota' : ucfirst($izin->jenis_izin) }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-indigo-400 mb-1">Durasi</p>
                    @php
                        $durasi = \Carbon\Carbon::parse($izin->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($izin->tanggal_selesai)) + 1;
                    @endphp
                    <p class="font-semibold text-white">{{ $durasi }} hari</p>
                </div>
                <div>
                    <p class="text-xs text-indigo-400 mb-1">Tanggal Mulai</p>
                    <p class="font-semibold text-white">{{ \Carbon\Carbon::parse($izin->tanggal_mulai)->isoFormat('D MMMM Y') }}</p>
                </div>
                <div>
                    <p class="text-xs text-indigo-400 mb-1">Tanggal Selesai</p>
                    <p class="font-semibold text-white">{{ \Carbon\Carbon::parse($izin->tanggal_selesai)->isoFormat('D MMMM Y') }}</p>
                </div>
            </div>

            <div>
                <p class="text-xs text-indigo-400 mb-1">Keterangan</p>
                <p class="text-sm text-indigo-200 bg-indigo-950/50 rounded-xl px-4 py-3 border border-indigo-700/30">
                    {{ $izin->keterangan }}
                </p>
            </div>

            @if($izin->bukti_file)
            <div>
                <p class="text-xs text-indigo-400 mb-2">Bukti Pendukung</p>
                @php $ext = pathinfo($izin->bukti_file, PATHINFO_EXTENSION); @endphp
                @if(in_array(strtolower($ext), ['jpg','jpeg','png']))
                    <img src="{{ Storage::url($izin->bukti_file) }}" alt="Bukti" class="max-w-full rounded-xl border border-indigo-700/30 max-h-64 object-contain">
                @else
                    <a href="{{ Storage::url($izin->bukti_file) }}" target="_blank"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-600/20 text-indigo-300 border border-indigo-600/20 text-sm hover:bg-indigo-600/30 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        Lihat Dokumen Bukti
                    </a>
                @endif
            </div>
            @endif

            @if($izin->catatan_hrd)
            <div class="bg-red-500/5 border border-red-500/20 rounded-xl px-4 py-3">
                <p class="text-xs text-red-400 mb-1 font-semibold">Catatan HRD (Penolakan):</p>
                <p class="text-sm text-red-300">{{ $izin->catatan_hrd }}</p>
            </div>
            @endif
        </div>

        <!-- Action Buttons (only if pending) -->
        @if($izin->status_persetujuan === 'pending')
        <div class="glass-card rounded-2xl p-5 space-y-4">
            <h3 class="text-sm font-semibold text-white">Keputusan HRD</h3>

            <!-- Approve -->
            <form method="POST" action="{{ route('hrd.izin.approve', $izin) }}">
                @csrf @method('PATCH')
                <button type="submit" onclick="return confirm('Setujui pengajuan izin ini?')"
                    class="w-full py-2.5 rounded-xl font-semibold text-sm bg-emerald-600 hover:bg-emerald-700 text-white transition-colors">
                    ✓ Setujui Pengajuan
                </button>
            </form>

            <!-- Reject -->
            <div>
                <p class="text-xs text-indigo-400 mb-2">Tolak dengan alasan:</p>
                <form method="POST" action="{{ route('hrd.izin.reject', $izin) }}" class="space-y-3">
                    @csrf @method('PATCH')
                    <textarea name="catatan_hrd" rows="3" required minlength="5"
                        placeholder="Tuliskan alasan penolakan..."
                        class="input-field w-full rounded-xl px-4 py-2.5 text-white text-sm resize-none @error('catatan_hrd') border-red-500/50 @enderror">{{ old('catatan_hrd') }}</textarea>
                    @error('catatan_hrd') <p class="text-xs text-red-400">{{ $message }}</p> @enderror
                    <button type="submit" onclick="return confirm('Tolak pengajuan izin ini?')"
                        class="w-full py-2.5 rounded-xl font-semibold text-sm bg-red-600/20 hover:bg-red-600/40 text-red-400 border border-red-600/20 transition-colors">
                        ✗ Tolak Pengajuan
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>
</x-layouts.app>
