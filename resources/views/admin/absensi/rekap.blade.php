<x-layouts.app>
    <x-slot name="title">Rekap Absensi</x-slot>

    <div class="space-y-5">
        <!-- Filter -->
        <div class="glass-card rounded-2xl p-4 sm:p-5">
            <h3 class="text-sm font-semibold text-white mb-4">Filter Periode</h3>
            <form method="GET" action="{{ route('admin.absensi.rekap') }}" class="flex flex-wrap items-end gap-2 sm:gap-3">
                <div class="w-full sm:w-auto">
                    <label class="block text-xs text-indigo-400 mb-1">Dari Tanggal</label>
                    <input type="date" name="tanggal_mulai" value="{{ $tanggalMulai }}"
                        class="input-field w-full rounded-xl px-3 py-2 text-white text-sm">
                </div>
                <div class="w-full sm:w-auto">
                    <label class="block text-xs text-indigo-400 mb-1">Sampai Tanggal</label>
                    <input type="date" name="tanggal_selesai" value="{{ $tanggalSelesai }}"
                        class="input-field w-full rounded-xl px-3 py-2 text-white text-sm">
                </div>
                <div class="w-full sm:w-auto">
                    <label class="block text-xs text-indigo-400 mb-1">Pegawai</label>
                    <select name="pegawai_id" class="input-field w-full rounded-xl px-3 py-2 text-white text-sm sm:min-w-40">
                        <option value="">Semua Pegawai</option>
                        @foreach($pegawaiDropdown as $p)
                            <option value="{{ $p->id }}" {{ $pegawaiId == $p->id ? 'selected' : '' }}>{{ $p->user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2 w-full sm:w-auto">
                    <button type="submit" class="btn-primary flex-1 sm:flex-none px-5 py-2 rounded-xl text-sm text-white font-medium">Tampilkan</button>
                    <a href="{{ route('admin.absensi.export-pdf', request()->all()) }}"
                       class="flex-1 sm:flex-none px-4 sm:px-5 py-2 rounded-xl text-sm text-red-300 border border-red-700/50 hover:bg-red-700/20 transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                        Export PDF
                    </a>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="glass-card rounded-2xl overflow-hidden">
            <div class="px-4 sm:px-5 py-4 border-b border-indigo-800/30">
                <h3 class="text-sm font-semibold text-white">
                    Rekap Periode: {{ \Carbon\Carbon::parse($tanggalMulai)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($tanggalSelesai)->format('d/m/Y') }}
                </h3>
            </div>
            @if($pegawaiList->isEmpty())
                <div class="py-12 text-center"><p class="text-indigo-400 text-sm">Tidak ada data</p></div>
            @else

            <!-- Desktop Table -->
            <div class="hidden sm:block overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-indigo-800/20">
                            <th class="px-5 py-3 text-left text-xs font-semibold text-indigo-400 uppercase">Pegawai</th>
                            <th class="px-5 py-3 text-center text-xs font-semibold text-emerald-400 uppercase">Hadir</th>
                            <th class="px-5 py-3 text-center text-xs font-semibold text-amber-400 uppercase">Terlambat</th>
                            <th class="px-5 py-3 text-center text-xs font-semibold text-violet-400 uppercase">Izin</th>
                            <th class="px-5 py-3 text-center text-xs font-semibold text-red-400 uppercase">Total Absen</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-indigo-800/20">
                        @foreach($pegawaiList as $item)
                        @php
                            $hadir = $item->absensi->where('status', 'hadir')->count();
                            $terlambat = $item->absensi->where('status', 'terlambat')->count();
                            $izin = $item->izin->count();
                        @endphp
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-lg bg-indigo-600/30 flex items-center justify-center text-xs font-bold text-indigo-300">
                                        {{ strtoupper(substr($item->user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-white text-xs">{{ $item->user->name }}</p>
                                        <p class="text-indigo-400 text-xs">{{ $item->nip ?? $item->jabatan ?? '—' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="text-sm font-semibold text-emerald-400">{{ $hadir }}</span>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="text-sm font-semibold text-amber-400">{{ $terlambat }}</span>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="text-sm font-semibold text-violet-400">{{ $izin }}</span>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="text-sm font-semibold text-white">{{ $hadir + $terlambat }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card List -->
            <div class="sm:hidden divide-y divide-indigo-800/20">
                @foreach($pegawaiList as $item)
                @php
                    $hadir = $item->absensi->where('status', 'hadir')->count();
                    $terlambat = $item->absensi->where('status', 'terlambat')->count();
                    $izin = $item->izin->count();
                @endphp
                <div class="p-4">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-8 h-8 rounded-lg bg-indigo-600/30 flex items-center justify-center text-xs font-bold text-indigo-300 flex-shrink-0">
                            {{ strtoupper(substr($item->user->name, 0, 2)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-white truncate">{{ $item->user->name }}</p>
                            <p class="text-xs text-indigo-400">{{ $item->nip ?? $item->jabatan ?? '—' }}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-4 gap-2 text-center">
                        <div class="bg-white/5 rounded-lg py-2">
                            <p class="text-sm font-bold text-emerald-400">{{ $hadir }}</p>
                            <p class="text-xs text-indigo-400">Hadir</p>
                        </div>
                        <div class="bg-white/5 rounded-lg py-2">
                            <p class="text-sm font-bold text-amber-400">{{ $terlambat }}</p>
                            <p class="text-xs text-indigo-400">Telat</p>
                        </div>
                        <div class="bg-white/5 rounded-lg py-2">
                            <p class="text-sm font-bold text-violet-400">{{ $izin }}</p>
                            <p class="text-xs text-indigo-400">Izin</p>
                        </div>
                        <div class="bg-white/5 rounded-lg py-2">
                            <p class="text-sm font-bold text-white">{{ $hadir + $terlambat }}</p>
                            <p class="text-xs text-indigo-400">Total</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</x-layouts.app>
