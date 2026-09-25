<x-layouts.app>
    <x-slot name="title">Tambah Pegawai</x-slot>

    <div class="max-w-3xl mx-auto space-y-5">
        <a href="{{ route('admin.pegawai.index') }}" class="text-indigo-400 hover:text-white transition-colors text-sm inline-flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Daftar Pegawai
        </a>

        <div class="glass-card rounded-2xl p-6">
            <h2 class="text-lg font-bold text-white mb-1">Tambah Pegawai Baru</h2>
            <p class="text-sm text-indigo-300 mb-6">Buat akun login dan lengkapi data pegawai. Password awal dapat diubah setelah pengguna masuk.</p>

            <form method="POST" action="{{ route('admin.pegawai.store') }}" class="space-y-6">
                @csrf

                <section>
                    <p class="text-xs font-semibold text-indigo-400 uppercase tracking-wider mb-4">Data Akun</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-indigo-200 mb-1.5">Nama Lengkap <span class="text-red-400">*</span></label>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name"
                                class="input-field w-full rounded-xl px-4 py-2.5 text-white text-sm @error('name') border-red-500/50 @enderror">
                            @error('name') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-indigo-200 mb-1.5">Email Login <span class="text-red-400">*</span></label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                                class="input-field w-full rounded-xl px-4 py-2.5 text-white text-sm @error('email') border-red-500/50 @enderror">
                            @error('email') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="no_hp" class="block text-sm font-medium text-indigo-200 mb-1.5">No HP</label>
                            <input id="no_hp" type="tel" name="no_hp" value="{{ old('no_hp') }}" autocomplete="tel"
                                class="input-field w-full rounded-xl px-4 py-2.5 text-white text-sm @error('no_hp') border-red-500/50 @enderror">
                            @error('no_hp') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="role" class="block text-sm font-medium text-indigo-200 mb-1.5">Role <span class="text-red-400">*</span></label>
                            <select id="role" name="role" required class="input-field w-full rounded-xl px-4 py-2.5 text-white text-sm @error('role') border-red-500/50 @enderror">
                                <option value="pegawai" {{ old('role', 'pegawai') === 'pegawai' ? 'selected' : '' }}>Karyawan</option>
                                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="hrd" {{ old('role') === 'hrd' ? 'selected' : '' }}>HRD</option>
                            </select>
                            @error('role') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="password" class="block text-sm font-medium text-indigo-200 mb-1.5">Password Awal <span class="text-red-400">*</span></label>
                            <input id="password" type="password" name="password" required autocomplete="new-password"
                                class="input-field w-full rounded-xl px-4 py-2.5 text-white text-sm @error('password') border-red-500/50 @enderror">
                            @error('password') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-indigo-200 mb-1.5">Konfirmasi Password <span class="text-red-400">*</span></label>
                            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                                class="input-field w-full rounded-xl px-4 py-2.5 text-white text-sm">
                        </div>
                    </div>
                </section>

                <section class="border-t border-indigo-800/30 pt-5">
                    <p class="text-xs font-semibold text-indigo-400 uppercase tracking-wider mb-4">Data Kepegawaian</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="nip" class="block text-sm font-medium text-indigo-200 mb-1.5">NIP</label>
                            <input id="nip" type="text" name="nip" value="{{ old('nip') }}" placeholder="Nomor Induk Pegawai"
                                class="input-field w-full rounded-xl px-4 py-2.5 text-white text-sm @error('nip') border-red-500/50 @enderror">
                            @error('nip') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="jabatan" class="block text-sm font-medium text-indigo-200 mb-1.5">Jabatan</label>
                            <input id="jabatan" type="text" name="jabatan" value="{{ old('jabatan') }}" placeholder="Contoh: Staff IT"
                                class="input-field w-full rounded-xl px-4 py-2.5 text-white text-sm @error('jabatan') border-red-500/50 @enderror">
                            @error('jabatan') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="divisi" class="block text-sm font-medium text-indigo-200 mb-1.5">Divisi</label>
                            <input id="divisi" type="text" name="divisi" value="{{ old('divisi') }}" placeholder="Contoh: IT, Finance, HR"
                                class="input-field w-full rounded-xl px-4 py-2.5 text-white text-sm @error('divisi') border-red-500/50 @enderror">
                            @error('divisi') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="lokasi_kerja_id" class="block text-sm font-medium text-indigo-200 mb-1.5">Lokasi Kerja</label>
                            <select id="lokasi_kerja_id" name="lokasi_kerja_id" class="input-field w-full rounded-xl px-4 py-2.5 text-white text-sm @error('lokasi_kerja_id') border-red-500/50 @enderror">
                                <option value="">— Belum ditetapkan —</option>
                                @foreach($lokasiList as $lokasi)
                                    <option value="{{ $lokasi->id }}" {{ old('lokasi_kerja_id') == $lokasi->id ? 'selected' : '' }}>
                                        {{ $lokasi->nama_lokasi }} (Masuk: {{ substr($lokasi->jam_masuk_standar, 0, 5) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('lokasi_kerja_id') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="mt-4">
                        <label for="alamat" class="block text-sm font-medium text-indigo-200 mb-1.5">Alamat</label>
                        <textarea id="alamat" name="alamat" rows="2" placeholder="Alamat lengkap pegawai"
                            class="input-field w-full rounded-xl px-4 py-2.5 text-white text-sm resize-none @error('alamat') border-red-500/50 @enderror">{{ old('alamat') }}</textarea>
                        @error('alamat') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>
                </section>

                <div class="flex flex-col-reverse sm:flex-row gap-3 pt-2">
                    <a href="{{ route('admin.pegawai.index') }}" class="flex-1 py-2.5 rounded-xl text-center text-sm text-indigo-300 border border-indigo-700/50 hover:border-indigo-500 transition-all">
                        Batal
                    </a>
                    <button type="submit" class="flex-1 btn-primary py-2.5 rounded-xl text-sm text-white font-semibold">
                        Buat Akun Pegawai
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
