<x-layouts.app>
    <x-slot name="title">Riwayat Kehadiran</x-slot>

    <div class="space-y-6">
        <!-- Filter -->
        <div class="glass-card rounded-2xl p-4">
            <form method="GET" action="{{ route('pegawai.riwayat') }}" class="flex flex-wrap items-end gap-3">
                <div>
                    <label class="block text-xs text-indigo-400 mb-1">Bulan</label>
                    <select name="bulan" class="input-field rounded-xl px-3 py-2 text-white text-sm">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create(null, $m)->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-indigo-400 mb-1">Tahun</label>
                    <select name="tahun" class="input-field rounded-xl px-3 py-2 text-white text-sm">
                        @foreach(range(now()->year, now()->year - 2, -1) as $y)
                            <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn-primary px-5 py-2 rounded-xl text-sm text-white font-medium">Filter</button>
            </form>
        </div>

        <!-- Stats summary -->
        @php
            $totalHadir = $absensi->where('status', 'hadir')->count();
            $totalTerlambat = $absensi->where('status', 'terlambat')->count();
            $totalIzin = $izin->count();
        @endphp
        <div class="grid grid-cols-3 gap-4">
            <div class="glass-card rounded-xl p-4 text-center">
                <p class="text-2xl font-bold text-emerald-400">{{ $totalHadir }}</p>
                <p class="text-xs text-indigo-300 mt-1">Hadir</p>
            </div>
            <div class="glass-card rounded-xl p-4 text-center">
                <p class="text-2xl font-bold text-amber-400">{{ $totalTerlambat }}</p>
                <p class="text-xs text-indigo-300 mt-1">Terlambat</p>
            </div>
            <div class="glass-card rounded-xl p-4 text-center">
                <p class="text-2xl font-bold text-violet-400">{{ $totalIzin }}</p>
                <p class="text-xs text-indigo-300 mt-1">Izin/Cuti</p>
            </div>
        </div>

        <!-- Absensi Table -->
        <div class="glass-card rounded-2xl overflow-hidden">
            <div class="px-5 py-4 border-b border-indigo-800/30">
                <h3 class="text-sm font-semibold text-white">Riwayat Absensi</h3>
            </div>
            @if($absensi->isEmpty())
                <div class="py-12 text-center">
                    <p class="text-indigo-400 text-sm">Tidak ada data absensi untuk periode ini</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-indigo-800/20">
                                <th class="px-5 py-3 text-left text-xs font-semibold text-indigo-400 uppercase">Tanggal</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-indigo-400 uppercase">Masuk</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-indigo-400 uppercase">Pulang</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-indigo-400 uppercase">Status</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-indigo-400 uppercase">Durasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-indigo-800/20">
                            @foreach($absensi as $item)
                            <tr class="hover:bg-white/5 transition-colors">
                                <td class="px-5 py-3.5 text-indigo-200 font-medium">
                                    {{ \Carbon\Carbon::parse($item->tanggal)->isoFormat('ddd, D MMM') }}
                                </td>
                                <td class="px-5 py-3.5 text-white">{{ \Carbon\Carbon::parse($item->jam_masuk)->format('H:i') }}</td>
                                <td class="px-5 py-3.5 text-white">
                                    {{ $item->jam_pulang ? \Carbon\Carbon::parse($item->jam_pulang)->format('H:i') : '—' }}
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="text-xs px-2.5 py-1 rounded-full badge-{{ $item->status }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-indigo-300">
                                    @if($item->jam_pulang)
                                        @php
                                            $menit = \Carbon\Carbon::parse($item->jam_masuk)->diffInMinutes(\Carbon\Carbon::parse($item->jam_pulang));
                                            $jam = floor($menit / 60); $sisa = $menit % 60;
                                        @endphp
                                        {{ $jam }}j {{ $sisa }}m
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-5 py-3 border-t border-indigo-800/20">
                    {{ $absensi->links() }}
                </div>
            @endif
        </div>

        <!-- Izin Table -->
        <div class="glass-card rounded-2xl overflow-hidden">
            <div class="px-5 py-4 border-b border-indigo-800/30">
                <h3 class="text-sm font-semibold text-white">Riwayat Izin</h3>
            </div>
            @if($izin->isEmpty())
                <div class="py-8 text-center">
                    <p class="text-indigo-400 text-sm">Tidak ada pengajuan izin untuk periode ini</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-indigo-800/20">
                                <th class="px-5 py-3 text-left text-xs font-semibold text-indigo-400 uppercase">Tanggal</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-indigo-400 uppercase">Jenis</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-indigo-400 uppercase">Keterangan</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-indigo-400 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-indigo-800/20">
                            @foreach($izin as $item)
                            <tr class="hover:bg-white/5 transition-colors">
                                <td class="px-5 py-3.5 text-indigo-200 text-xs">
                                    {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d/m') }} —
                                    {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d/m/Y') }}
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="text-xs px-2 py-1 rounded-full bg-indigo-500/20 text-indigo-300 capitalize">{{ $item->jenis_izin }}</span>
                                </td>
                                <td class="px-5 py-3.5 text-indigo-300 max-w-xs truncate">{{ $item->keterangan }}</td>
                                <td class="px-5 py-3.5">
                                    <span class="text-xs px-2.5 py-1 rounded-full badge-{{ $item->status_persetujuan }}">
                                        {{ ucfirst($item->status_persetujuan) }}
                                    </span>
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
