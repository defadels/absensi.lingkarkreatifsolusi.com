<x-layouts.guest>
    <x-slot name="title">Masuk</x-slot>

    <h2 class="text-xl font-bold text-white mb-1">Selamat Datang Kembali</h2>
    <p class="text-indigo-300 text-sm mb-6">Masuk ke akun Anda untuk melanjutkan</p>

    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-4 text-sm text-emerald-400 bg-emerald-500/10 border border-emerald-500/30 rounded-xl px-4 py-3">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-indigo-200 mb-1.5">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                placeholder="nama@perusahaan.com"
                class="input-field w-full rounded-xl px-4 py-2.5 text-white text-sm placeholder-indigo-400/50 @error('email') border-red-500/50 @enderror">
            @error('email')
                <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-indigo-200 mb-1.5">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                placeholder="••••••••"
                class="input-field w-full rounded-xl px-4 py-2.5 text-white text-sm placeholder-indigo-400/50 @error('password') border-red-500/50 @enderror">
            @error('password')
                <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember me & forgot password -->
        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="remember" id="remember_me" class="w-4 h-4 rounded bg-indigo-900 border-indigo-600 text-indigo-500 focus:ring-indigo-500">
                <span class="text-sm text-indigo-300">Ingat saya</span>
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm text-indigo-400 hover:text-indigo-200 transition-colors">Lupa password?</a>
            @endif
        </div>

        <button type="submit" class="btn-primary w-full py-2.5 rounded-xl text-white font-semibold text-sm tracking-wide">
            Masuk
        </button>

        @if (Route::has('register'))
            <p class="text-center text-sm text-indigo-400">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-indigo-300 hover:text-white font-medium transition-colors">Daftar sekarang</a>
            </p>
        @endif
    </form>
</x-layouts.guest>
