<x-layouts.app>
    <x-slot name="title">Detail Pegawai</x-slot>

    <div class="max-w-3xl mx-auto space-y-5">
        <a href="{{ route('admin.pegawai.index') }}" class="text-indigo-400 hover:text-white transition-colors text-sm inline-flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Daftar Pegawai
        </a>

        {{-- Profile Card --}}
        <div class="glass-card rounded-2xl p-6">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center text-lg font-bold text-white flex-shrink-0">
                    {{ strtoupper(substr($pegawai->user->name, 0, 2)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h2 class="text-lg font-bold text-white">{{ $pegawai->user->name }}</h2>
                        <span class="text-xs px-2 py-0.5 rounded-full capitalize
                            {{ $pegawai->user->role === 'admin' ? 'bg-red-500/20 text-red-400' : ($pegawai->user->role === 'hrd' ? 'bg-violet-500/20 text-violet-400' : 'bg-indigo-500/20 text-indigo-300') }}">
                            {{ $pegawai->user->role === 'pegawai' ? 'Karyawan' : ($pegawai->user->role === 'hrd' ? 'HRD' : 'Admin') }}
                        </span>
                    </div>
                    <p class="text-sm text-indigo-400 mt-0.5">{{ $pegawai->user->email }}</p>
                    @if($pegawai->jabatan)
                    <p class="text-sm text-indigo-300 mt-1">{{ $pegawai->jabatan }} {{ $pegawai->divisi ? '· ' . $pegawai->divisi : '' }}</p>
                    @endif
                </div>
                <a href="{{ route('admin.pegawai.edit', $pegawai) }}"
                   class="flex-shrink-0 text-xs px-3 py-1.5 rounded-lg bg-indigo-600/20 text-indigo-300 hover:bg-indigo-600/40 transition-colors border border-indigo-600/20">
                    Edit
                </a>
            </div>

            <div class="mt-5 pt-5 border-t border-indigo-800/30 grid grid-cols-2 md:grid-cols-3 gap-4">
                <div>
                    <p class="text-xs text-indigo-400 mb-1">NIP</p>
                    <p class="text-sm text-white">{{ $pegawai->nip ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-indigo-400 mb-1">No. HP</p>
                    <p class="text-sm text-white">{{ $pegawai->no_hp ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-indigo-400 mb-1">Alamat</p>
                    <p class="text-sm text-white">{{ $pegawai->alamat ?? '—' }}</p>
                </div>
            </div>
        </div>

        {{-- Lokasi Kerja yang Ditetapkan --}}
        <div class="glass-card rounded-2xl p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-white">Lokasi Kerja yang Ditetapkan</h3>
                <a href="{{ route('admin.pegawai.edit', $pegawai) }}"
                   class="text-xs text-indigo-400 hover:text-indigo-200 transition-colors">
                    Ubah Lokasi →
                </a>
            </div>

            @if($pegawai->lokasiKerja)
            <div class="rounded-xl border border-indigo-700/30 p-4" style="background: rgba(30,27,75,0.5)">
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 rounded-lg bg-emerald-500/20 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-white">{{ $pegawai->lokasiKerja->nama_lokasi }}</p>
                        <div class="mt-1.5 flex flex-wrap gap-x-4 gap-y-1 text-xs text-indigo-400">
                            <span>📍 {{ number_format($pegawai->lokasiKerja->latitude, 6) }}, {{ number_format($pegawai->lokasiKerja->longitude, 6) }}</span>
                            <span>⭕ Radius: {{ $pegawai->lokasiKerja->radius_meter }}m</span>
                            <span>🕗 Jam Masuk: {{ substr($pegawai->lokasiKerja->jam_masuk_standar, 0, 5) }}</span>
                            <span>⏱ Toleransi: {{ $pegawai->lokasiKerja->toleransi_menit }} menit</span>
                        </div>
                        <span class="mt-2 inline-block text-xs px-2 py-0.5 rounded-full {{ $pegawai->lokasiKerja->is_active ? 'bg-emerald-500/20 text-emerald-400' : 'bg-gray-700 text-gray-400' }}">
                            {{ $pegawai->lokasiKerja->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                </div>
            </div>
            @else
            <div class="rounded-xl border border-amber-500/20 p-4 bg-amber-500/5 flex items-center gap-3">
                <svg class="w-5 h-5 text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div>
                    <p class="text-sm font-medium text-amber-400">Belum ditetapkan lokasi kerja</p>
                    <p class="text-xs text-amber-400/70 mt-0.5">Karyawan ini tidak dapat melakukan absensi sampai lokasi ditetapkan.</p>
                </div>
            </div>
            @endif
        </div>

        {{-- Riwayat Absensi Terbaru --}}
        <div class="glass-card rounded-2xl overflow-hidden">
            <div class="px-5 py-4 border-b border-indigo-800/30 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-white">Riwayat Absensi Terbaru</h3>
                <span class="text-xs text-indigo-400">10 data terakhir</span>
            </div>

            @if($absensiTerbaru->isEmpty())
            <div class="py-10 text-center">
                <p class="text-indigo-400 text-sm">Belum ada riwayat absensi</p>
            </div>
            @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-indigo-800/20">
                            <th class="px-5 py-3 text-left text-xs font-semibold text-indigo-400 uppercase">Tanggal</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-indigo-400 uppercase">Lokasi</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-indigo-400 uppercase">Masuk</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-indigo-400 uppercase">Pulang</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-indigo-400 uppercase">Status</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-indigo-400 uppercase">Jarak</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-indigo-800/20">
                        @foreach($absensiTerbaru as $ab)
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="px-5 py-3.5 text-indigo-200 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($ab->tanggal)->isoFormat('D MMM Y') }}
                            </td>
                            <td class="px-5 py-3.5 text-xs text-indigo-400">
                                {{ $ab->lokasi_kerja?->nama_lokasi ?? '—' }}
                            </td>
                            <td class="px-5 py-3.5 text-white">
                                {{ $ab->jam_masuk ? \Carbon\Carbon::parse($ab->jam_masuk)->format('H:i') : '—' }}
                            </td>
                            <td class="px-5 py-3.5 text-white">
                                {{ $ab->jam_pulang ? \Carbon\Carbon::parse($ab->jam_pulang)->format('H:i') : '—' }}
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="text-xs px-2 py-0.5 rounded-full badge-{{ $ab->status }}">
                                    {{ ucfirst($ab->status) }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-xs text-indigo-400">
                                {{ $ab->jarak_masuk_meter ? $ab->jarak_masuk_meter . 'm' : '—' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
</x-layouts.app>
