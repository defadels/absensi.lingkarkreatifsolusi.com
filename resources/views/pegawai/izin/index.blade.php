<x-layouts.app>
    <x-slot name="title">Pengajuan Izin Saya</x-slot>

    <div class="space-y-4">
        <div class="flex justify-end">
            <a href="{{ route('pegawai.izin.create') }}" class="btn-primary px-5 py-2.5 rounded-xl text-sm text-white font-semibold inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Ajukan Izin Baru
            </a>
        </div>

        <div class="glass-card rounded-2xl overflow-hidden">
            <div class="px-5 py-4 border-b border-indigo-800/30">
                <h3 class="text-sm font-semibold text-white">Riwayat Pengajuan Izin</h3>
            </div>
            @if($izin->isEmpty())
                <div class="py-12 text-center">
                    <svg class="w-12 h-12 text-indigo-700 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="text-indigo-400 text-sm">Belum ada pengajuan izin</p>
                </div>
            @else
            <div class="divide-y divide-indigo-800/20">
                @foreach($izin as $item)
                <div class="px-5 py-4 hover:bg-white/5 transition-colors">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-sm font-medium text-white capitalize">
                                    {{ $item->jenis_izin === 'dinas' ? 'Dinas Luar Kota' : ucfirst($item->jenis_izin) }}
                                </span>
                                <span class="text-xs px-2 py-0.5 rounded-full badge-{{ $item->status_persetujuan }}">
                                    {{ ucfirst($item->status_persetujuan) }}
                                </span>
                            </div>
                            <p class="text-xs text-indigo-300 mb-1">
                                {{ \Carbon\Carbon::parse($item->tanggal_mulai)->isoFormat('D MMM Y') }}
                                @if($item->tanggal_mulai !== $item->tanggal_selesai)
                                    — {{ \Carbon\Carbon::parse($item->tanggal_selesai)->isoFormat('D MMM Y') }}
                                @endif
                            </p>
                            <p class="text-xs text-indigo-400">{{ Str::limit($item->keterangan, 80) }}</p>
                            @if($item->catatan_hrd)
                                <p class="text-xs text-red-400 mt-1">HRD: {{ $item->catatan_hrd }}</p>
                            @endif
                        </div>
                        <div class="text-xs text-indigo-500 flex-shrink-0">
                            {{ $item->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @if($izin->hasPages())
            <div class="px-5 py-3 border-t border-indigo-800/20">{{ $izin->links() }}</div>
            @endif
            @endif
        </div>
    </div>
</x-layouts.app>
