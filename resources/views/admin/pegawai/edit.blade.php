<x-layouts.app>
    <x-slot name="title">Edit Data Pegawai</x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('admin.pegawai.index') }}" class="text-indigo-400 hover:text-white transition-colors">
                ← Kembali
            </a>
        </div>

        <div class="glass-card rounded-2xl p-6">
            <div class="flex items-center gap-4 mb-6 pb-5 border-b border-indigo-800/30">
                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center text-lg font-bold text-white">
                    {{ strtoupper(substr($pegawai->user->name, 0, 2)) }}
                </div>
                <div>
                    <h2 class="text-lg font-bold text-white">{{ $pegawai->user->name }}</h2>
                    <p class="text-sm text-indigo-400">{{ $pegawai->user->email }}</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.pegawai.update', $pegawai) }}" class="space-y-5">
                @csrf @method('PATCH')

                <p class="text-xs font-semibold text-indigo-400 uppercase tracking-wider">Data Akun</p>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-indigo-200 mb-1.5">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $pegawai->user->name) }}"
                            class="input-field w-full rounded-xl px-4 py-2.5 text-white text-sm @error('name') border-red-500/50 @enderror">
                        @error('name') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-indigo-200 mb-1.5">Role</label>
                        <select name="role" class="input-field w-full rounded-xl px-4 py-2.5 text-white text-sm">
                            <option value="pegawai" {{ old('role', $pegawai->user->role) === 'pegawai' ? 'selected' : '' }}>Pegawai</option>
                            <option value="hrd" {{ old('role', $pegawai->user->role) === 'hrd' ? 'selected' : '' }}>HRD</option>
                            <option value="admin" {{ old('role', $pegawai->user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>
                </div>

                <div class="border-t border-indigo-800/30 pt-5">
                    <p class="text-xs font-semibold text-indigo-400 uppercase tracking-wider mb-4">Data Kepegawaian</p>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-indigo-200 mb-1.5">NIP</label>
                            <input type="text" name="nip" value="{{ old('nip', $pegawai->nip) }}"
                                placeholder="Nomor Induk Pegawai"
                                class="input-field w-full rounded-xl px-4 py-2.5 text-white text-sm @error('nip') border-red-500/50 @enderror">
                            @error('nip') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-indigo-200 mb-1.5">No HP</label>
                            <input type="tel" name="no_hp" value="{{ old('no_hp', $pegawai->no_hp) }}"
                                class="input-field w-full rounded-xl px-4 py-2.5 text-white text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-indigo-200 mb-1.5">Jabatan</label>
                            <input type="text" name="jabatan" value="{{ old('jabatan', $pegawai->jabatan) }}"
                                placeholder="Contoh: Staff IT"
                                class="input-field w-full rounded-xl px-4 py-2.5 text-white text-sm @error('jabatan') border-red-500/50 @enderror">
                            @error('jabatan') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-indigo-200 mb-1.5">Divisi</label>
                            <input type="text" name="divisi" value="{{ old('divisi', $pegawai->divisi) }}"
                                placeholder="Contoh: IT, Finance, HR"
                                class="input-field w-full rounded-xl px-4 py-2.5 text-white text-sm @error('divisi') border-red-500/50 @enderror">
                            @error('divisi') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-indigo-200 mb-1.5">Lokasi Kerja</label>
                        <select name="lokasi_kerja_id" class="input-field w-full rounded-xl px-4 py-2.5 text-white text-sm">
                            <option value="">— Belum ditetapkan —</option>
                            @foreach($lokasiList as $lok)
                            <option value="{{ $lok->id }}"
                                {{ old('lokasi_kerja_id', $pegawai->lokasi_kerja_id) == $lok->id ? 'selected' : '' }}>
                                {{ $lok->nama_lokasi }}
                                (Masuk: {{ substr($lok->jam_masuk_standar, 0, 5) }})
                            </option>
                            @endforeach
                        </select>
                        @error('lokasi_kerja_id') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                        <p class="mt-1 text-xs text-indigo-500">Karyawan hanya bisa absensi di lokasi yang ditetapkan</p>
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-indigo-200 mb-1.5">Alamat</label>
                        <textarea name="alamat" rows="2" placeholder="Alamat lengkap pegawai"
                            class="input-field w-full rounded-xl px-4 py-2.5 text-white text-sm resize-none">{{ old('alamat', $pegawai->alamat) }}</textarea>
                    </div>
                </div>

                <div class="flex gap-3 pt-2">
                    <a href="{{ route('admin.pegawai.index') }}" class="flex-1 py-2.5 rounded-xl text-center text-sm text-indigo-300 border border-indigo-700/50 hover:border-indigo-500 transition-all">
                        Batal
                    </a>
                    <button type="submit" class="flex-1 btn-primary py-2.5 rounded-xl text-sm text-white font-semibold">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
