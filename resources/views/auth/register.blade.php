<x-layouts.guest>
    <x-slot name="title">Daftar</x-slot>

    <h2 class="text-xl font-bold text-white mb-1">Buat Akun Baru</h2>
    <p class="text-indigo-300 text-sm mb-6">Daftar sebagai pegawai PT Lingkar Kreatif Solusi</p>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-indigo-200 mb-1.5">Nama Lengkap</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                placeholder="John Doe"
                class="input-field w-full rounded-xl px-4 py-2.5 text-white text-sm placeholder-indigo-400/50 @error('name') border-red-500/50 @enderror">
            @error('name')
                <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- No HP -->
        <div>
            <label for="no_hp" class="block text-sm font-medium text-indigo-200 mb-1.5">Nomor HP</label>
            <input id="no_hp" type="tel" name="no_hp" value="{{ old('no_hp') }}" required
                placeholder="08xxxxxxxxxx"
                class="input-field w-full rounded-xl px-4 py-2.5 text-white text-sm placeholder-indigo-400/50 @error('no_hp') border-red-500/50 @enderror">
            @error('no_hp')
                <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-indigo-200 mb-1.5">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                placeholder="nama@perusahaan.com"
                class="input-field w-full rounded-xl px-4 py-2.5 text-white text-sm placeholder-indigo-400/50 @error('email') border-red-500/50 @enderror">
            @error('email')
                <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-indigo-200 mb-1.5">Password</label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                placeholder="Minimal 8 karakter"
                class="input-field w-full rounded-xl px-4 py-2.5 text-white text-sm placeholder-indigo-400/50 @error('password') border-red-500/50 @enderror">
            @error('password')
                <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-indigo-200 mb-1.5">Konfirmasi Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                placeholder="Ulangi password"
                class="input-field w-full rounded-xl px-4 py-2.5 text-white text-sm placeholder-indigo-400/50 @error('password_confirmation') border-red-500/50 @enderror">
            @error('password_confirmation')
                <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div class="bg-indigo-900/30 border border-indigo-700/30 rounded-xl p-3 text-xs text-indigo-300">
            💡 Setelah mendaftar, admin akan melengkapi data NIP, jabatan, dan divisi Anda.
        </div>

        <button type="submit" class="btn-primary w-full py-2.5 rounded-xl text-white font-semibold text-sm tracking-wide">
            Buat Akun
        </button>

        <p class="text-center text-sm text-indigo-400">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-indigo-300 hover:text-white font-medium transition-colors">Masuk</a>
        </p>
    </form>
</x-layouts.guest>
