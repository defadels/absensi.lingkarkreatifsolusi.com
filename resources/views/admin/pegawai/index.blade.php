<x-layouts.app>
    <x-slot name="title">Kelola Pegawai</x-slot>

    <div class="space-y-4">
        <!-- Search -->
        <div class="glass-card rounded-2xl p-4">
            <form method="GET" action="{{ route('admin.pegawai.index') }}" class="flex flex-wrap gap-2 sm:gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NIP, jabatan..."
                    class="input-field flex-1 rounded-xl px-3 sm:px-4 py-2 text-white text-sm placeholder-indigo-400/50 min-w-0">
                <select name="lokasi_id" class="input-field rounded-xl px-3 py-2 text-white text-sm">
                    <option value="">Semua Lokasi</option>
                    @foreach($lokasiList as $lok)
                    <option value="{{ $lok->id }}" {{ request('lokasi_id') == $lok->id ? 'selected' : '' }}>{{ $lok->nama_lokasi }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn-primary px-4 sm:px-5 py-2 rounded-xl text-sm text-white font-medium flex-shrink-0">Cari</button>
                @if(request('search') || request('lokasi_id'))
                    <a href="{{ route('admin.pegawai.index') }}" class="px-3 sm:px-4 py-2 rounded-xl text-sm text-indigo-300 border border-indigo-700/50 hover:border-indigo-500 transition-all flex-shrink-0">Reset</a>
                @endif
            </form>
        </div>

        <!-- Table -->
        <div class="glass-card rounded-2xl overflow-hidden">
            <div class="px-4 sm:px-5 py-4 border-b border-indigo-800/30">
                <h3 class="text-sm font-semibold text-white">Daftar Pegawai ({{ $pegawai->total() }})</h3>
            </div>

            <!-- Desktop Table -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-indigo-800/20">
                            <th class="px-5 py-3 text-left text-xs font-semibold text-indigo-400 uppercase">Pegawai</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-indigo-400 uppercase">NIP</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-indigo-400 uppercase">Jabatan / Divisi</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-indigo-400 uppercase">Lokasi Kerja</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-indigo-400 uppercase">Role</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-indigo-400 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-indigo-800/20">
                        @forelse($pegawai as $item)
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center text-xs font-bold text-white flex-shrink-0">
                                        {{ strtoupper(substr($item->user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-white">{{ $item->user->name }}</p>
                                        <p class="text-xs text-indigo-400">{{ $item->user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-indigo-300">{{ $item->nip ?? '—' }}</td>
                            <td class="px-5 py-3.5">
                                <p class="text-indigo-200">{{ $item->jabatan ?? '—' }}</p>
                                <p class="text-xs text-indigo-400">{{ $item->divisi ?? '' }}</p>
                            </td>
                            <td class="px-5 py-3.5">
                                @if($item->lokasiKerja)
                                    <span class="inline-flex items-center gap-1 text-xs px-2 py-1 rounded-full bg-indigo-500/20 text-indigo-300">
                                        📍 {{ $item->lokasiKerja->nama_lokasi }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs px-2 py-1 rounded-full bg-amber-500/15 text-amber-400">
                                        ⚠ Belum ditetapkan
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="text-xs px-2 py-1 rounded-full capitalize
                                    {{ $item->user->role === 'admin' ? 'bg-red-500/20 text-red-400' : ($item->user->role === 'hrd' ? 'bg-violet-500/20 text-violet-400' : 'bg-indigo-500/20 text-indigo-300') }}">
                                    {{ $item->user->role }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.pegawai.show', $item) }}"
                                       class="text-xs px-3 py-1.5 rounded-lg bg-teal-600/20 text-teal-300 hover:bg-teal-600/40 transition-colors border border-teal-600/20">
                                        Detail
                                    </a>
                                    <a href="{{ route('admin.pegawai.edit', $item) }}"
                                       class="text-xs px-3 py-1.5 rounded-lg bg-indigo-600/20 text-indigo-300 hover:bg-indigo-600/40 transition-colors border border-indigo-600/20">
                                        Edit
                                    </a>
                                    @if($item->user_id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.pegawai.destroy', $item) }}" onsubmit="return confirm('Hapus pegawai ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-xs px-3 py-1.5 rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500/20 transition-colors border border-red-500/20">
                                            Hapus
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-indigo-400">Tidak ada data pegawai</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card List -->
            <div class="md:hidden divide-y divide-indigo-800/20">
                @forelse($pegawai as $item)
                <div class="p-4">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center text-sm font-bold text-white flex-shrink-0">
                            {{ strtoupper(substr($item->user->name, 0, 2)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2">
                                <p class="font-medium text-white truncate">{{ $item->user->name }}</p>
                                <span class="text-xs px-2 py-0.5 rounded-full capitalize flex-shrink-0
                                    {{ $item->user->role === 'admin' ? 'bg-red-500/20 text-red-400' : ($item->user->role === 'hrd' ? 'bg-violet-500/20 text-violet-400' : 'bg-indigo-500/20 text-indigo-300') }}">
                                    {{ $item->user->role }}
                                </span>
                            </div>
                            <p class="text-xs text-indigo-400 truncate">{{ $item->user->email }}</p>
                            <div class="mt-2 flex flex-wrap gap-x-4 gap-y-1">
                                <span class="text-xs text-indigo-300">NIP: {{ $item->nip ?? '—' }}</span>
                                <span class="text-xs text-indigo-300">{{ $item->jabatan ?? '—' }}</span>
                                <span class="text-xs text-indigo-300">{{ $item->divisi ?? '—' }}</span>
                            </div>
                            <div class="mt-1.5">
                                @if($item->lokasiKerja)
                                    <span class="inline-flex items-center gap-1 text-xs px-2 py-0.5 rounded-full bg-indigo-500/20 text-indigo-300">
                                        📍 {{ $item->lokasiKerja->nama_lokasi }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs px-2 py-0.5 rounded-full bg-amber-500/15 text-amber-400">
                                        ⚠ Belum ada lokasi
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 flex gap-2">
                        <a href="{{ route('admin.pegawai.edit', $item) }}"
                           class="flex-1 text-center text-xs py-2 rounded-lg bg-indigo-600/20 text-indigo-300 hover:bg-indigo-600/40 transition-colors border border-indigo-600/20">
                            Edit Data
                        </a>
                        @if($item->user_id !== auth()->id())
                        <form method="POST" action="{{ route('admin.pegawai.destroy', $item) }}" onsubmit="return confirm('Hapus pegawai ini?')" class="flex-1">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-full text-xs py-2 rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500/20 transition-colors border border-red-500/20">
                                Hapus
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
                @empty
                <div class="py-12 text-center">
                    <p class="text-indigo-400 text-sm">Tidak ada data pegawai</p>
                </div>
                @endforelse
            </div>

            @if($pegawai->hasPages())
            <div class="px-5 py-3 border-t border-indigo-800/20">
                {{ $pegawai->links() }}
            </div>
            @endif
        </div>
    </div>
</x-layouts.app>
