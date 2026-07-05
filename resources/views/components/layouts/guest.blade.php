<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Login' }} — PT Lingkar Kreatif Solusi</title>
    <link rel="icon" type="image/png" href="/Logo-Links.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        .auth-bg {
            background: radial-gradient(ellipse at 20% 50%, rgba(99,102,241,0.15) 0%, transparent 60%),
                        radial-gradient(ellipse at 80% 20%, rgba(139,92,246,0.15) 0%, transparent 60%),
                        radial-gradient(ellipse at 60% 80%, rgba(20,184,166,0.08) 0%, transparent 50%),
                        #030712;
        }
        .glass-form {
            background: rgba(255,255,255,0.04);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.08);
        }
        .input-field {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(99,102,241,0.3);
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .input-field:focus {
            border-color: rgba(99,102,241,0.7);
            box-shadow: 0 0 0 3px rgba(99,102,241,0.15);
            outline: none;
        }
        .btn-primary {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            transition: all 0.2s ease;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #4338ca, #6d28d9);
            box-shadow: 0 0 20px rgba(99,102,241,0.4);
            transform: translateY(-1px);
        }
        /* Animated orbs */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .orb { animation: float 6s ease-in-out infinite; }
        .orb-2 { animation-delay: -3s; }
    </style>
</head>
<body class="min-h-screen auth-bg flex items-center justify-center p-4">

    <!-- Background orbs -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="orb absolute top-1/4 left-1/4 w-72 h-72 bg-indigo-600/10 rounded-full blur-3xl"></div>
        <div class="orb orb-2 absolute bottom-1/4 right-1/4 w-96 h-96 bg-violet-600/10 rounded-full blur-3xl"></div>
    </div>

    <div class="relative z-10 w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="flex justify-center mb-4">
                <div class="rounded-2xl overflow-hidden shadow-2xl" style="box-shadow: 0 0 30px rgba(99,102,241,0.25); background: white; padding: 10px 18px;">
                    <img src="/links-logo-new.png" alt="PT Lingkar Kreatif Solusi" class="h-14 w-auto object-contain">
                </div>
            </div>
            <p class="text-indigo-300 text-sm mt-3">Sistem Absensi Digital</p>
        </div>

        <!-- Card -->
        <div class="glass-form rounded-2xl p-8 shadow-2xl">
            {{ $slot }}
        </div>
    </div>
</body>
</html>
