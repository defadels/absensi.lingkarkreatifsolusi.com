<x-layouts.guest>
    <x-slot name="title">Lupa Password</x-slot>

    <h2 class="text-xl font-bold text-white mb-1">Lupa Password?</h2>
    <p class="text-indigo-300 text-sm mb-6">Masukkan email akun Anda. Kami akan mengirim tautan untuk membuat password baru.</p>

    @if (session('error'))
        <div role="alert" class="mb-5 rounded-xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-300">
            {{ session('error') }}
        </div>
    @endif

    <form id="forgot-password-form" method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="mb-1.5 block text-sm font-medium text-indigo-200">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                placeholder="nama@perusahaan.com"
                class="input-field w-full rounded-xl px-4 py-2.5 text-sm text-white placeholder-indigo-400/50 @error('email') border-red-500/50 @enderror">
            <p id="email-feedback" class="mt-2 min-h-5 text-xs text-indigo-300" role="status" aria-live="polite">
                Masukkan email untuk memeriksa statusnya.
            </p>
            @error('email')
                <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <button id="submit-button" type="submit" disabled
            class="btn-primary w-full rounded-xl py-2.5 text-sm font-semibold tracking-wide text-white disabled:cursor-not-allowed disabled:opacity-50">
            Kirim Tautan Reset
        </button>

        <p class="text-center text-sm text-indigo-400">
            Ingat password Anda?
            <a href="{{ route('login') }}" class="font-medium text-indigo-300 transition-colors hover:text-white">Kembali ke login</a>
        </p>
    </form>

    <script>
        (() => {
            const emailInput = document.getElementById('email');
            const feedback = document.getElementById('email-feedback');
            const submitButton = document.getElementById('submit-button');
            const form = document.getElementById('forgot-password-form');
            let debounceTimer;
            let checkedEmail = '';

            const showFeedback = (message, state = 'pending') => {
                feedback.textContent = message;
                feedback.className = 'mt-2 min-h-5 text-xs ' + (state === 'success'
                    ? 'text-emerald-300'
                    : state === 'danger' ? 'text-red-300' : 'text-indigo-300');
            };

            const checkEmail = async () => {
                const email = emailInput.value.trim();
                checkedEmail = '';
                submitButton.disabled = true;

                if (!email) {
                    showFeedback('Masukkan email untuk memeriksa statusnya.');
                    return;
                }

                if (!emailInput.validity.valid) {
                    showFeedback('Masukkan alamat email dengan format yang benar.', 'danger');
                    return;
                }

                showFeedback('Memeriksa email…');

                try {
                    const response = await fetch(@json(route('password.email.check')), {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': @json(csrf_token()),
                        },
                        body: JSON.stringify({ email }),
                    });
                    const result = await response.json();

                    if (emailInput.value.trim() !== email) return;

                    checkedEmail = email;
                    submitButton.disabled = false;

                    if (response.ok && result.active) {
                        showFeedback(result.message, 'success');
                    } else {
                        showFeedback(result.message || 'Email tidak dapat digunakan.', 'danger');
                    }
                } catch (error) {
                    if (emailInput.value.trim() === email) {
                        checkedEmail = email;
                        submitButton.disabled = false;
                        showFeedback('Pemeriksaan gagal. Periksa koneksi Anda lalu coba lagi.', 'danger');
                    }
                }
            };

            emailInput.addEventListener('input', () => {
                clearTimeout(debounceTimer);
                checkedEmail = '';
                submitButton.disabled = true;
                debounceTimer = setTimeout(checkEmail, 500);
            });

            form.addEventListener('submit', (event) => {
                if (emailInput.value.trim() !== checkedEmail) {
                    event.preventDefault();
                    checkEmail();
                    return;
                }

                submitButton.disabled = true;
                submitButton.textContent = 'Mengirim…';
            });

            if (emailInput.value.trim()) checkEmail();
        })();
    </script>
</x-layouts.guest>
