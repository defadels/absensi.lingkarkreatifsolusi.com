<x-layouts.app>
    <x-slot name="title">Monitoring Kehadiran</x-slot>

    <div class="space-y-4">
        <!-- Stats -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="glass-card rounded-2xl p-5 stat-card">
                <p class="text-xs text-indigo-400 mb-1">Total Pegawai</p>
                <p class="text-2xl font-bold text-white">{{ $totalPegawai }}</p>
            </div>
            <div class="glass-card rounded-2xl p-5 stat-card">
                <p class="text-xs text-indigo-400 mb-1">Hadir</p>
                <p class="text-2xl font-bold text-emerald-400">{{ $totalHadir }}</p>
            </div>
            <div class="glass-card rounded-2xl p-5 stat-card">
                <p class="text-xs text-indigo-400 mb-1">Terlambat</p>
                <p class="text-2xl font-bold text-amber-400">{{ $totalTerlambat }}</p>
            </div>
            <div class="glass-card rounded-2xl p-5 stat-card">
                <p class="text-xs text-indigo-400 mb-1">Alpha</p>
                <p class="text-2xl font-bold text-red-400">{{ max(0, $totalAlpha) }}</p>
            </div>
        </div>

        <!-- Filter -->
        <div class="glass-card rounded-2xl p-4">
            <form method="GET" action="{{ route('hrd.monitoring') }}" class="flex flex-wrap items-end gap-3">
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

        <!-- Table -->
        <div class="glass-card rounded-2xl overflow-hidden">
            <div class="px-5 py-4 border-b border-indigo-800/30">
                <h3 class="text-sm font-semibold text-white">
                    Kehadiran: {{ \Carbon\Carbon::parse($tanggal)->isoFormat('dddd, D MMMM Y') }}
                </h3>
            </div>
            @if($absensi->isEmpty())
                <div class="py-12 text-center"><p class="text-indigo-400 text-sm">Tidak ada data absensi</p></div>
            @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-indigo-800/20">
                            <th class="px-5 py-3 text-left text-xs font-semibold text-indigo-400 uppercase">Pegawai</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-indigo-400 uppercase">Jabatan</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-indigo-400 uppercase">Masuk</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-indigo-400 uppercase">Pulang</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-indigo-400 uppercase">Status</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-indigo-400 uppercase">Jarak</th>
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
                                    <p class="font-medium text-white text-xs">{{ $item->pegawai->user->name }}</p>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-xs text-indigo-300">{{ $item->pegawai->jabatan ?? '—' }}</td>
                            <td class="px-5 py-3.5 text-white">{{ $item->jam_masuk ? \Carbon\Carbon::parse($item->jam_masuk)->format('H:i') : '—' }}</td>
                            <td class="px-5 py-3.5 text-white">{{ $item->jam_pulang ? \Carbon\Carbon::parse($item->jam_pulang)->format('H:i') : '—' }}</td>
                            <td class="px-5 py-3.5">
                                <span class="text-xs px-2.5 py-1 rounded-full badge-{{ $item->status }}">{{ ucfirst($item->status) }}</span>
                            </td>
                            <td class="px-5 py-3.5 text-xs text-indigo-400">{{ $item->jarak_masuk_meter ? $item->jarak_masuk_meter . 'm' : '—' }}</td>
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
