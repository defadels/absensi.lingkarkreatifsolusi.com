<x-layouts.app>
    <x-slot name="title">Verifikasi Izin</x-slot>

    <div class="space-y-4">
        <!-- Filter + Pending Badge -->
        <div class="glass-card rounded-2xl p-4">
            <form method="GET" action="{{ route('hrd.izin.index') }}" class="flex flex-wrap items-end gap-3">
                <div>
                    <label class="block text-xs text-indigo-400 mb-1">Status</label>
                    <select name="status" class="input-field rounded-xl px-3 py-2 text-white text-sm">
                        <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                        <option value="diterima" {{ $status === 'diterima' ? 'selected' : '' }}>✓ Diterima</option>
                        <option value="ditolak" {{ $status === 'ditolak' ? 'selected' : '' }}>✗ Ditolak</option>
                        <option value="semua" {{ $status === 'semua' ? 'selected' : '' }}>Semua</option>
                    </select>
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
                @if($pendingCount > 0)
                <span class="ml-auto text-sm text-amber-400 bg-amber-500/10 border border-amber-500/30 px-4 py-2 rounded-xl">
                    ⚠ {{ $pendingCount }} pengajuan menunggu persetujuan
                </span>
                @endif
            </form>
        </div>

        <!-- Table -->
        <div class="glass-card rounded-2xl overflow-hidden">
            <div class="px-5 py-4 border-b border-indigo-800/30">
                <h3 class="text-sm font-semibold text-white">Daftar Pengajuan Izin</h3>
            </div>
            @if($izin->isEmpty())
                <div class="py-12 text-center"><p class="text-indigo-400 text-sm">Tidak ada pengajuan izin</p></div>
            @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-indigo-800/20">
                            <th class="px-5 py-3 text-left text-xs font-semibold text-indigo-400 uppercase">Pegawai</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-indigo-400 uppercase">Jenis</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-indigo-400 uppercase">Tanggal</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-indigo-400 uppercase">Keterangan</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-indigo-400 uppercase">Status</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-indigo-400 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-indigo-800/20">
                        @foreach($izin as $item)
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-lg bg-indigo-600/30 flex items-center justify-center text-xs font-bold text-indigo-300">
                                        {{ strtoupper(substr($item->pegawai->user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="text-xs font-medium text-white">{{ $item->pegawai->user->name }}</p>
                                        <p class="text-xs text-indigo-400">{{ $item->pegawai->jabatan ?? '—' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="text-xs px-2 py-1 rounded-full bg-indigo-500/20 text-indigo-300 capitalize">
                                    {{ $item->jenis_izin === 'dinas' ? 'Dinas Luar' : ucfirst($item->jenis_izin) }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-xs text-indigo-200">
                                {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d/m') }} —
                                {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d/m/Y') }}
                            </td>
                            <td class="px-5 py-3.5 text-xs text-indigo-300 max-w-xs">
                                <p class="truncate">{{ $item->keterangan }}</p>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="text-xs px-2.5 py-1 rounded-full badge-{{ $item->status_persetujuan }}">
                                    {{ ucfirst($item->status_persetujuan) }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                <a href="{{ route('hrd.izin.show', $item) }}"
                                   class="text-xs px-3 py-1.5 rounded-lg bg-indigo-600/20 text-indigo-300 hover:bg-indigo-600/40 transition-colors border border-indigo-600/20">
                                    {{ $item->status_persetujuan === 'pending' ? 'Proses' : 'Detail' }}
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($izin->hasPages())
            <div class="px-5 py-3 border-t border-indigo-800/20">{{ $izin->links() }}</div>
            @endif
            @endif
        </div>
    </div>
</x-layouts.app>
