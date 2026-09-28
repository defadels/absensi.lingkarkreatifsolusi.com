<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light">
    <title>Reset Password Absensi LKS</title>
</head>

<body style="margin:0; padding:32px 12px; background:#f3f4f6; color:#1f2937; font-family:Arial,Helvetica,sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:600px; margin:0 auto; background:#ffffff; border-radius:18px; overflow:hidden;">
        <tr>
            <td style="padding:32px 36px; background:#111827; text-align:center;">
                <p style="margin:0; color:#a5b4fc; font-size:12px; font-weight:bold; letter-spacing:2px;">PT LINGKAR KREATIF SOLUSI</p>
                <h1 style="margin:12px 0 0; color:#ffffff; font-size:24px;">Atur ulang password Anda</h1>
            </td>
        </tr>
        <tr>
            <td style="padding:36px;">
                <p style="margin:0 0 16px; font-size:16px;">Halo {{ $user->name }},</p>
                <p style="margin:0 0 24px; color:#4b5563; font-size:15px; line-height:1.7;">
                    Kami menerima permintaan untuk mengatur ulang password akun Absensi Digital Anda. Klik tombol di bawah untuk membuat password baru dan kembali mengakses akun Anda.
                </p>
                <table role="presentation" cellspacing="0" cellpadding="0" style="margin:0 auto 24px;">
                    <tr>
                        <td style="border-radius:10px; background:#4f46e5; text-align:center;">
                            <a href="{{ $resetUrl }}" style="display:inline-block; padding:15px 26px; color:#ffffff; font-size:15px; font-weight:bold; text-decoration:none;">Reset Password</a>
                        </td>
                    </tr>
                </table>
                <p style="margin:0 0 10px; color:#6b7280; font-size:13px; line-height:1.6;">
                    Tautan ini berlaku selama {{ $expireMinutes }} menit dan hanya dapat digunakan satu kali. Jika Anda tidak meminta reset password, abaikan email ini; password Anda tetap aman.
                </p>
                <p style="margin:20px 0 0; color:#9ca3af; font-size:12px; line-height:1.6;">
                    Jika tombol tidak berfungsi, salin tautan ini ke browser Anda:<br>
                    <a href="{{ $resetUrl }}" style="color:#4f46e5; word-break:break-all;">{{ $resetUrl }}</a>
                </p>
            </td>
        </tr>
        <tr>
            <td style="padding:20px 36px; background:#f9fafb; color:#9ca3af; font-size:12px; text-align:center;">
                Email otomatis dari Sistem Absensi Digital PT Lingkar Kreatif Solusi.
            </td>
        </tr>
    </table>
</body>

</html>
