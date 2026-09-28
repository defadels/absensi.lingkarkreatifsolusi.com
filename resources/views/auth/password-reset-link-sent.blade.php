<x-layouts.guest>
    <x-slot name="title">Periksa Email</x-slot>

    <div class="mb-5 flex h-14 w-14 items-center justify-center rounded-2xl border border-emerald-400/20 bg-emerald-400/10 text-2xl text-emerald-300" aria-hidden="true">
        ✓
    </div>
    <h2 class="mb-2 text-xl font-bold text-white">Periksa Email Anda</h2>
    <p class="mb-6 text-sm leading-6 text-indigo-200">
        Validasi email berhasil, silahkan cek email untuk melakukan reset password. Ikuti tombol di email untuk membuat password baru.
    </p>
    <div class="mb-6 rounded-xl border border-indigo-500/20 bg-indigo-500/10 px-4 py-3 text-xs leading-5 text-indigo-200">
        Jika email belum terlihat, periksa folder spam atau promosi. Tautan reset hanya berlaku selama {{ config('auth.passwords.'.config('auth.defaults.passwords').'.expire') }} menit.
    </div>
    <a href="{{ route('login') }}" class="btn-primary block w-full rounded-xl py-2.5 text-center text-sm font-semibold text-white">
        Kembali ke Login
    </a>
</x-layouts.guest>
