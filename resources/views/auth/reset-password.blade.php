<x-layouts.guest>
    <x-slot name="title">Buat Password Baru</x-slot>

    <h2 class="mb-1 text-xl font-bold text-white">Buat Password Baru</h2>
    <p class="mb-6 text-sm text-indigo-300">Pilih password baru untuk mengamankan akun Anda.</p>

    @if ($errors->has('email'))
        <div role="alert" class="mb-5 rounded-xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-300">
            {{ $errors->first('email') }}
        </div>
    @endif

    <form id="reset-password-form" method="POST" action="{{ route('password.store') }}" class="space-y-5">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <label for="email" class="mb-1.5 block text-sm font-medium text-indigo-200">Email akun</label>
            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" readonly required autocomplete="email"
                class="input-field w-full rounded-xl px-4 py-2.5 text-sm text-indigo-200 opacity-80">
            @error('email')
                <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="mb-1.5 block text-sm font-medium text-indigo-200">Password baru</label>
            <input id="password" type="password" name="password" required minlength="8" autocomplete="new-password"
                placeholder="Minimal 8 karakter"
                class="input-field w-full rounded-xl px-4 py-2.5 text-sm text-white placeholder-indigo-400/50 @error('password') border-red-500/50 @enderror">
            @error('password')
                <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="mb-1.5 block text-sm font-medium text-indigo-200">Konfirmasi password baru</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required minlength="8" autocomplete="new-password"
                placeholder="Ulangi password baru"
                class="input-field w-full rounded-xl px-4 py-2.5 text-sm text-white placeholder-indigo-400/50 @error('password_confirmation') border-red-500/50 @enderror">
            <p id="password-match-feedback" class="mt-2 min-h-5 text-xs text-indigo-300" role="status" aria-live="polite"></p>
            @error('password_confirmation')
                <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <button id="reset-submit" type="submit" class="btn-primary w-full rounded-xl py-2.5 text-sm font-semibold tracking-wide text-white">
            Simpan Password Baru
        </button>
    </form>

    <script>
        (() => {
            const form = document.getElementById('reset-password-form');
            const password = document.getElementById('password');
            const confirmation = document.getElementById('password_confirmation');
            const feedback = document.getElementById('password-match-feedback');
            const submit = document.getElementById('reset-submit');

            const checkMatch = () => {
                if (!confirmation.value) {
                    feedback.textContent = '';
                    feedback.className = 'mt-2 min-h-5 text-xs text-indigo-300';
                    confirmation.setCustomValidity('');
                    submit.disabled = false;
                    return;
                }

                const matches = password.value === confirmation.value;
                feedback.textContent = matches ? 'Konfirmasi password cocok.' : 'Konfirmasi password belum sama.';
                feedback.className = 'mt-2 min-h-5 text-xs ' + (matches ? 'text-emerald-300' : 'text-red-300');
                confirmation.setCustomValidity(matches ? '' : 'Konfirmasi password harus sama.');
                submit.disabled = !matches;
            };

            password.addEventListener('input', checkMatch);
            confirmation.addEventListener('input', checkMatch);
            form.addEventListener('submit', (event) => {
                checkMatch();
                if (!form.reportValidity()) event.preventDefault();
            });
            checkMatch();
        })();
    </script>
</x-layouts.guest>
