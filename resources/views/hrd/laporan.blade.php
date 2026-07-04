<x-layouts.app>
    <x-slot name="title">Laporan Bulanan</x-slot>

    <div class="space-y-5">
        <!-- Filter -->
        <div class="glass-card rounded-2xl p-5">
            <h3 class="text-sm font-semibold text-white mb-4">Pilih Periode Laporan</h3>
            <form method="GET" action="{{ route('hrd.laporan') }}" class="flex flex-wrap items-end gap-3">
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
                <button type="submit" class="btn-primary px-5 py-2 rounded-xl text-sm text-white font-medium">Tampilkan</button>
                <a href="{{ route('hrd.laporan.export-pdf', ['bulan' => $bulan, 'tahun' => $tahun]) }}"
                   class="px-5 py-2 rounded-xl text-sm text-red-300 border border-red-700/50 hover:bg-red-700/20 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                    Cetak PDF
                </a>
            </form>
        </div>

        <!-- Report Table -->
        <div class="glass-card rounded-2xl overflow-hidden">
            <div class="px-5 py-4 border-b border-indigo-800/30">
                <h3 class="text-sm font-semibold text-white">
                    Laporan Kehadiran: {{ \Carbon\Carbon::create($tahun, $bulan, 1)->translatedFormat('F Y') }}
                </h3>
                <p class="text-xs text-indigo-400 mt-0.5">{{ \Carbon\Carbon::parse($tanggalMulai)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($tanggalSelesai)->format('d/m/Y') }}</p>
            </div>
            @if($pegawaiList->isEmpty())
                <div class="py-12 text-center"><p class="text-indigo-400 text-sm">Tidak ada data pegawai</p></div>
            @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-indigo-800/20">
                            <th class="px-5 py-3 text-left text-xs font-semibold text-indigo-400 uppercase">Pegawai</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-indigo-400 uppercase">Divisi</th>
                            <th class="px-5 py-3 text-center text-xs font-semibold text-emerald-400 uppercase">Hadir</th>
                            <th class="px-5 py-3 text-center text-xs font-semibold text-amber-400 uppercase">Terlambat</th>
                            <th class="px-5 py-3 text-center text-xs font-semibold text-violet-400 uppercase">Izin</th>
                            <th class="px-5 py-3 text-center text-xs font-semibold text-indigo-400 uppercase">Total Absen</th>
                            <th class="px-5 py-3 text-center text-xs font-semibold text-indigo-400 uppercase">Persentase</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-indigo-800/20">
                        @php
                            $hariKerja = \Carbon\Carbon::parse($tanggalMulai)->diffInDays(\Carbon\Carbon::parse($tanggalSelesai)) + 1;
                        @endphp
                        @foreach($pegawaiList as $item)
                        @php
                            $hadir = $item->absensi->where('status', 'hadir')->count();
                            $terlambat = $item->absensi->where('status', 'terlambat')->count();
                            $izin = $item->izin->count();
                            $totalAbsen = $hadir + $terlambat;
                            $persen = $hariKerja > 0 ? round(($totalAbsen / $hariKerja) * 100) : 0;
                        @endphp
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-lg bg-indigo-600/30 flex items-center justify-center text-xs font-bold text-indigo-300">
                                        {{ strtoupper(substr($item->user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="text-xs font-medium text-white">{{ $item->user->name }}</p>
                                        <p class="text-xs text-indigo-400">{{ $item->nip ?? '—' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-xs text-indigo-300">{{ $item->divisi ?? '—' }}</td>
                            <td class="px-5 py-3.5 text-center"><span class="text-sm font-semibold text-emerald-400">{{ $hadir }}</span></td>
                            <td class="px-5 py-3.5 text-center"><span class="text-sm font-semibold text-amber-400">{{ $terlambat }}</span></td>
                            <td class="px-5 py-3.5 text-center"><span class="text-sm font-semibold text-violet-400">{{ $izin }}</span></td>
                            <td class="px-5 py-3.5 text-center"><span class="text-sm font-semibold text-white">{{ $totalAbsen }}</span></td>
                            <td class="px-5 py-3.5 text-center">
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 bg-gray-800 rounded-full h-1.5">
                                        <div class="h-1.5 rounded-full {{ $persen >= 80 ? 'bg-emerald-500' : ($persen >= 60 ? 'bg-amber-500' : 'bg-red-500') }}"
                                             style="width: {{ min(100, $persen) }}%"></div>
                                    </div>
                                    <span class="text-xs text-indigo-300 w-10 text-right">{{ $persen }}%</span>
                                </div>
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
