<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Throwable;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /** Display confirmation that the reset email has been requested. */
    public function sent(): View
    {
        return view('auth.password-reset-link-sent');
    }

    /** Check the account and mail-capable domain while the user types. */
    public function checkEmail(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        $email = mb_strtolower($validated['email']);
        $userExists = User::whereRaw('LOWER(email) = ?', [$email])->exists();
        $domainAcceptsMail = $this->domainAcceptsMail($email);

        return response()->json([
            'registered' => $userExists,
            'active' => $userExists && $domainAcceptsMail,
            'message' => ! $domainAcceptsMail
                ? 'Domain email ini tidak aktif atau tidak dapat menerima email.'
                : (! $userExists ? 'Email ini belum terdaftar dalam sistem.' : 'Email terdaftar dan domainnya mendukung layanan email.'),
        ]);
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        $email = mb_strtolower($validated['email']);

        if (! $this->domainAcceptsMail($email)) {
            return back()->withInput(['email' => $email])->with('error', 'Domain email ini tidak aktif atau tidak dapat menerima email.');
        }

        $user = User::whereRaw('LOWER(email) = ?', [$email])->first();

        if (! $user) {
            return back()->withInput(['email' => $email])->with('error', 'Email ini belum terdaftar dalam sistem.');
        }

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        try {
            $status = Password::sendResetLink(['email' => $user->email]);
        } catch (Throwable $exception) {
            Log::error('Password reset email could not be sent.', [
                'user_id' => $user->id,
                'exception' => $exception,
            ]);

            return back()->withInput(['email' => $email])
                ->with('error', 'Email reset password gagal dikirim. Periksa konfigurasi SMTP atau coba lagi beberapa saat.');
        }

        return $status == Password::RESET_LINK_SENT
                    ? redirect()->route('password.sent')
                    : back()->withInput(['email' => $email])
                        ->with('error', $status == Password::RESET_THROTTLED
                            ? 'Permintaan reset baru saja dikirim. Silakan tunggu sebelum mencoba lagi.'
                            : 'Tautan reset password gagal dikirim. Silakan coba lagi.');
    }

    /**
     * Check whether a domain advertises mail service, including implicit MX via A/AAAA.
     */
    private function domainAcceptsMail(string $email): bool
    {
        $domain = substr(strrchr($email, '@') ?: '', 1);

        if ($domain === '') {
            return false;
        }

        return checkdnsrr($domain, 'MX')
            || checkdnsrr($domain, 'A')
            || checkdnsrr($domain, 'AAAA');
    }
}
