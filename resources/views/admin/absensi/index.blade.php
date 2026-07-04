<x-layouts.app>
    <x-slot name="title">Absensi Harian</x-slot>

    <div class="space-y-4">
        <!-- Filter -->
        <div class="glass-card rounded-2xl p-4">
            <form method="GET" action="{{ route('admin.absensi.index') }}" class="flex flex-wrap items-end gap-3">
                <div>
                    <label class="block text-xs text-indigo-400 mb-1">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ $tanggal }}"
                        class="input-field rounded-xl px-3 py-2 text-white text-sm">
                </div>
                <div>
                    <label class="block text-xs text-indigo-400 mb-1">Pegawai</label>
                    <select name="pegawai_id" class="input-field rounded-xl px-3 py-2 text-white text-sm min-w-40">
                        <option value="">Semua Pegawai</option>
                        @foreach($pegawai as $p)
                            <option value="{{ $p->id }}" {{ $pegawaiId == $p->id ? 'selected' : '' }}>{{ $p->user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn-primary px-5 py-2 rounded-xl text-sm text-white font-medium">Filter</button>
            </form>
        </div>

        <!-- Stats -->
        @php
            $totalMasuk = $absensi->where('status', 'hadir')->count() + $absensi->where('status', 'terlambat')->count();
        @endphp
        <div class="grid grid-cols-3 gap-4">
            <div class="glass-card rounded-xl px-4 py-3 text-center">
                <p class="text-xl font-bold text-white">{{ $absensi->total() }}</p>
                <p class="text-xs text-indigo-300">Total Absensi</p>
            </div>
            <div class="glass-card rounded-xl px-4 py-3 text-center">
                <p class="text-xl font-bold text-emerald-400">{{ $absensi->where('status', 'hadir')->count() }}</p>
                <p class="text-xs text-indigo-300">Hadir Tepat Waktu</p>
            </div>
            <div class="glass-card rounded-xl px-4 py-3 text-center">
                <p class="text-xl font-bold text-amber-400">{{ $absensi->where('status', 'terlambat')->count() }}</p>
                <p class="text-xs text-indigo-300">Terlambat</p>
            </div>
        </div>

        <!-- Table -->
        <div class="glass-card rounded-2xl overflow-hidden">
            <div class="px-5 py-4 border-b border-indigo-800/30 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-white">
                    Absensi: {{ \Carbon\Carbon::parse($tanggal)->isoFormat('dddd, D MMMM Y') }}
                </h3>
                <a href="{{ route('admin.absensi.rekap') }}" class="text-xs text-indigo-400 hover:text-indigo-200">
                    Lihat Rekap Periode →
                </a>
            </div>
            @if($absensi->isEmpty())
                <div class="py-12 text-center">
                    <p class="text-indigo-400 text-sm">Tidak ada data absensi untuk tanggal ini</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-indigo-800/20">
                                <th class="px-5 py-3 text-left text-xs font-semibold text-indigo-400 uppercase">Pegawai</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-indigo-400 uppercase">Masuk</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-indigo-400 uppercase">Pulang</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-indigo-400 uppercase">Status</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-indigo-400 uppercase">Jarak</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-indigo-400 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-indigo-800/20">
                            @foreach($absensi as $item)
                            <tr class="hover:bg-white/5 transition-colors">
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-7 h-7 rounded-lg bg-indigo-600/30 flex items-center justify-center text-xs font-bold text-indigo-300">
                                            {{ strtoupper(substr($item->pegawai->user->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <p class="font-medium text-white text-xs">{{ $item->pegawai->user->name }}</p>
                                            <p class="text-indigo-400 text-xs">{{ $item->pegawai->jabatan ?? 'Pegawai' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 text-white text-sm">
                                    {{ $item->jam_masuk ? \Carbon\Carbon::parse($item->jam_masuk)->format('H:i') : '—' }}
                                </td>
                                <td class="px-5 py-3.5 text-white text-sm">
                                    {{ $item->jam_pulang ? \Carbon\Carbon::parse($item->jam_pulang)->format('H:i') : '—' }}
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="text-xs px-2.5 py-1 rounded-full badge-{{ $item->status }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-xs text-indigo-300">
                                    {{ $item->jarak_masuk_meter ? $item->jarak_masuk_meter . 'm' : '—' }}
                                </td>
                                <td class="px-5 py-3.5">
                                    <a href="{{ route('admin.absensi.show', $item) }}"
                                       class="text-xs px-3 py-1.5 rounded-lg bg-indigo-600/20 text-indigo-300 hover:bg-indigo-600/40 transition-colors border border-indigo-600/20">
                                        Verifikasi
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($absensi->hasPages())
                <div class="px-5 py-3 border-t border-indigo-800/20">{{ $absensi->links() }}</div>
                @endif
            @endif
        </div>
    </div>
</x-layouts.app>
