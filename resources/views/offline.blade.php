<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Koneksi Terputus — PT Lingkar Kreatif Solusi</title>
    <link rel="icon" type="image/png" href="/Logo-Links.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: radial-gradient(ellipse at 20% 50%, rgba(99,102,241,0.15) 0%, transparent 60%),
                        radial-gradient(ellipse at 80% 20%, rgba(139,92,246,0.15) 0%, transparent 60%),
                        #030712;
            color: #f1f5f9;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            text-align: center;
            box-sizing: border-box;
        }
        .container {
            max-width: 480px;
            padding: 2.5rem;
            margin: 1rem;
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 1.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            animation: fadeIn 0.6s ease-out;
        }
        .icon-box {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 80px;
            height: 80px;
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.25);
            border-radius: 1.25rem;
            margin-bottom: 2rem;
            color: #ef4444;
            animation: pulse 2s infinite;
        }
        h1 {
            font-size: 1.75rem;
            font-weight: 700;
            margin: 0 0 1rem 0;
            background: linear-gradient(135deg, #f3f4f6, #9ca3af);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        p {
            font-size: 0.95rem;
            line-height: 1.6;
            color: #9ca3af;
            margin: 0 0 2.5rem 0;
        }
        .btn-retry {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
            padding: 0.875rem 1.5rem;
            font-size: 0.95rem;
            font-weight: 600;
            color: #ffffff;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            border: none;
            border-radius: 0.75rem;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }
        .btn-retry:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.45);
            background: linear-gradient(135deg, #4338ca, #6d28d9);
        }
        .btn-retry:active {
            transform: translateY(0);
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4); }
            50% { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(239, 68, 68, 0); }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon-box">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 40px; height: 40px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.284 16.284A3 3 0 0 0 12 17h.008a3 3 0 0 0 3.712-2.284m-7.44 0a3.001 3.001 0 0 0-1.422-5.44m8.862 5.44a3.001 3.001 0 0 0 1.422-5.44m-2.124-3.124a9 9 0 0 0-16.2 0m16.2 0a9 9 0 0 1 1.776 5.862M12 12a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18" />
            </svg>
        </div>
        <h1>Koneksi Terputus</h1>
        <p>Anda sedang offline. Aplikasi absensi memerlukan koneksi internet untuk dapat melakukan sinkronisasi data presensi dengan server. Silakan aktifkan data seluler atau hubungkan ke Wi-Fi dan coba kembali.</p>
        <button class="btn-retry" onclick="window.location.reload();">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 18px; height: 18px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
            </svg>
            Coba Hubungkan Kembali
        </button>
    </div>
</body>
</html>
