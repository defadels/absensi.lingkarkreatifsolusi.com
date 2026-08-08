# 📋 Sistem Absensi – PT Lingkar Kreatif Solusi

Aplikasi web untuk mencatat kehadiran pegawai secara digital berbasis **GPS (lokasi)** dan **foto selfie**. Dibuat menggunakan framework **Laravel** (PHP).

---

## 🗺️ Gambaran Umum

Bayangkan sistem absensi kertas biasa, tapi versi digital yang jauh lebih canggih:

- **Pegawai** buka aplikasi di HP/komputer → klik "Absen Masuk" → aplikasi otomatis cek lokasi GPS mereka → jika berada dalam radius yang ditentukan, mereka bisa absen sambil selfie.
- **HRD** bisa memantau siapa yang hadir, terlambat, atau tidak masuk secara real-time, lalu mengunduh laporan PDF.
- **Admin** mengelola data pegawai, lokasi kerja, dan memantau rekap absensi.

---

## 🏗️ Teknologi yang Digunakan

| Teknologi | Fungsi |
|-----------|--------|
| **Laravel 13** (PHP) | Framework utama backend |
| **SQLite** | Database penyimpanan data |
| **Tailwind CSS** | Styling tampilan (CSS) |
| **Vite** | Bundler aset (JS/CSS) |
| **DomPDF** | Generate laporan PDF |
| **Laravel Breeze** | Sistem login/registrasi |

---

## 👥 Peran Pengguna (Role)

Aplikasi ini memiliki **3 jenis pengguna** dengan akses yang berbeda-beda:

```
┌─────────────────────────────────────────────────────┐
│  ADMIN      → Kelola pegawai & lokasi kerja         │
│  HRD        → Monitor absensi & verifikasi izin     │
│  PEGAWAI    → Absen masuk/pulang & ajukan izin      │
└─────────────────────────────────────────────────────┘
```

---

## 📁 Struktur Folder Penting

```
absensi.lingkarkreatifsolusi.com/
├── app/
│   ├── Http/
│   │   ├── Controllers/           ← Logika utama aplikasi
│   │   │   ├── Admin/             ← Fitur untuk Admin
│   │   │   ├── Hrd/               ← Fitur untuk HRD
│   │   │   ├── Pegawai/           ← Fitur untuk Pegawai
│   │   │   └── DashboardController.php
│   │   └── Middleware/
│   │       └── RoleMiddleware.php ← Pengaman akses per role
│   └── Models/                    ← Representasi tabel database
│       ├── User.php
│       ├── Pegawai.php
│       ├── Absensi.php
│       ├── LokasiKerja.php
│       └── Izin.php
├── database/
│   ├── migrations/                ← Script pembuatan tabel
│   └── seeders/
│       └── AdminSeeder.php        ← Data awal (akun demo)
├── resources/views/               ← Tampilan HTML (Blade)
│   ├── admin/
│   ├── hrd/
│   └── pegawai/
└── routes/
    └── web.php                    ← Daftar semua URL aplikasi
```

---

## 🔑 Fitur Utama & Penjelasan Teknis

---

### 1. 🔐 Sistem Login & Keamanan Akses (Role)

**Untuk orang awam:**  
Setiap pengguna punya "kartu identitas digital" berupa *role* (peran). Saat login, sistem langsung tahu apakah kamu Admin, HRD, atau Pegawai biasa. Kalau kamu pegawai biasa tapi mencoba masuk ke halaman admin, sistem akan menolak aksesmu.

**Secara teknis:**

📄 **File: `app/Http/Middleware/RoleMiddleware.php`**

```php
// Kode ini adalah "penjaga pintu" untuk setiap halaman.
// Ia cek: apakah role pengguna sesuai dengan yang diizinkan?

public function handle(Request $request, Closure $next, string ...$roles): Response
{
    if (!auth()->check()) {
        return redirect()->route('login'); // Belum login? Ke halaman login
    }

    $user = auth()->user();

    if (!in_array($user->role, $roles)) {
        abort(403, 'Anda tidak memiliki akses ke halaman ini.'); // Role salah? Tolak!
    }

    return $next($request); // Role sesuai? Lanjutkan
}
```

> `$roles` adalah daftar role yang diizinkan. Misalnya `role:admin` berarti hanya Admin yang boleh masuk.

📄 **File: `app/Models/User.php`**

```php
// Kolom yang disimpan untuk setiap pengguna
protected $fillable = [
    'name',     // Nama lengkap
    'email',    // Email (untuk login)
    'no_hp',    // Nomor HP
    'role',     // Peran: 'admin', 'hrd', atau 'pegawai'
    'password', // Password (disimpan terenkripsi)
];

// Helper function untuk cek role
public function isAdmin(): bool   { return $this->role === 'admin'; }
public function isHrd(): bool     { return $this->role === 'hrd'; }
public function isPegawai(): bool { return $this->role === 'pegawai'; }
```

📄 **File: `app/Http/Controllers/DashboardController.php`**

```php
// Setelah login, pengguna diarahkan ke dashboard sesuai rolenya
return match ($user->role) {
    'admin' => redirect()->route('admin.dashboard'),
    'hrd'   => redirect()->route('hrd.dashboard'),
    default => redirect()->route('pegawai.dashboard'), // pegawai biasa
};
```

> Ibaratnya seperti resepsionis yang langsung mengarahkan tamu ke lantai yang tepat setelah mereka check-in.

---

### 2. 📍 Sistem Absensi Berbasis GPS

**Untuk orang awam:**  
Saat absen, aplikasi meminta izin akses lokasi di HP/browser. Sistem lalu mengukur jarak antara posisi pegawai dengan koordinat kantor. Jika terlalu jauh (misalnya lebih dari 100 meter), absen ditolak. Ini mencegah pegawai absen dari rumah!

**Secara teknis:**

📄 **File: `app/Http/Controllers/Pegawai/AbsensiController.php`**

**Fungsi `hitungJarak` (Baris 16–29):**

```php
// Rumus matematika "Haversine" untuk menghitung jarak dua titik di bumi
// (seperti yang dipakai Google Maps)

private function hitungJarak(float $lat1, float $lon1, float $lat2, float $lon2): float
{
    $earthRadius = 6371000; // Jari-jari bumi dalam meter
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);

    $a = sin($dLat / 2) * sin($dLat / 2)
       + cos(deg2rad($lat1)) * cos(deg2rad($lat2))
       * sin($dLon / 2) * sin($dLon / 2);

    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    return $earthRadius * $c; // Hasil dalam meter
}
```

> Rumus Haversine dipakai untuk menghitung jarak yang sebenarnya di permukaan bola bumi, bukan garis lurus di peta datar.

**Fungsi `masukStore` – Proses Absen Masuk (Baris 89–153):**

```php
// Langkah-langkah saat pegawai klik "Absen Masuk":

// 1. Hitung jarak dari posisi pegawai ke kantor
$jarak = $this->hitungJarak(
    $request->latitude, $request->longitude, // Posisi pegawai (dari GPS HP)
    $lokasiAktif->latitude, $lokasiAktif->longitude // Koordinat kantor
);

// 2. Jika terlalu jauh, tolak!
if ($jarak > $lokasiAktif->radius_meter) {
    return response()->json(['error' => "Anda berada di luar area..."], 422);
}

// 3. Tentukan status: hadir atau terlambat
$batasWaktu = now()->setTimeFromTimeString($lokasiAktif->jam_masuk_standar)
    ->addMinutes($lokasiAktif->toleransi_menit); // Contoh: 08:00 + 15 menit = 08:15
$status = now()->greaterThan($batasWaktu) ? 'terlambat' : 'hadir';

// 4. Simpan data absensi ke database
Absensi::create([
    'pegawai_id'        => $pegawai->id,
    'tanggal'           => now()->toDateString(),
    'jam_masuk'         => now()->format('H:i:s'),
    'latitude_masuk'    => $request->latitude,
    'longitude_masuk'   => $request->longitude,
    'jarak_masuk_meter' => round($jarak),
    'foto_masuk'        => $fotoPath,
    'status'            => $status, // 'hadir' atau 'terlambat'
]);
```

**Fungsi `simpanFotoBase64` – Menyimpan Foto Selfie (Baris 255–264):**

```php
// Foto dari kamera HP dikirim dalam format "base64" (teks panjang berisi data gambar)
// Fungsi ini mengubahnya kembali menjadi file gambar nyata dan menyimpannya

private function simpanFotoBase64(string $base64, string $tipe): string
{
    $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $base64); // Hapus header
    $decoded   = base64_decode($imageData); // Decode teks jadi data gambar
    $filename  = 'absensi-foto/' . date('Y/m/d') . '/' . $tipe . '_' . auth()->id() . '_' . time() . '.jpg';

    Storage::disk('public')->put($filename, $decoded); // Simpan ke folder storage
    return $filename;
}
```

---

### 3. 🗺️ Manajemen Lokasi Kerja (Admin)

**Untuk orang awam:**  
Admin bisa menambahkan lokasi kantor beserta koordinat GPS-nya. Admin juga menentukan radius (misalnya 100 meter) dan jam masuk standar. Setiap pegawai kemudian ditetapkan ke salah satu lokasi.

**Secara teknis:**

📄 **File: `app/Http/Controllers/Admin/LokasiKerjaController.php`**

```php
// Saat Admin menyimpan lokasi kerja baru:
$lokasi = LokasiKerja::create([
    'nama_lokasi'       => $request->nama_lokasi,      // Nama kantor
    'latitude'          => $request->latitude,          // Koordinat GPS
    'longitude'         => $request->longitude,         // Koordinat GPS
    'radius_meter'      => $request->radius_meter,      // Area valid absen (meter)
    'jam_masuk_standar' => $request->jam_masuk_standar, // Jam masuk normal, misal 08:00
    'toleransi_menit'   => $request->toleransi_menit,   // Toleransi keterlambatan
    'is_active'         => true,
]);

// Tetapkan pegawai yang dipilih ke lokasi ini
if ($request->filled('pegawai_ids')) {
    Pegawai::whereIn('id', $request->pegawai_ids)
        ->update(['lokasi_kerja_id' => $lokasi->id]);
}
```

```php
// Admin bisa menonaktifkan lokasi tanpa menghapusnya
// (berguna jika kantor sementara tutup)
public function toggleActive(LokasiKerja $lokasiKerja)
{
    $lokasiKerja->update(['is_active' => !$lokasiKerja->is_active]);
    // true → false (nonaktif) atau false → true (aktif)
}
```

📄 **File: `app/Models/LokasiKerja.php`**

```php
// Data yang disimpan untuk setiap lokasi kerja
protected $fillable = [
    'nama_lokasi',       // Contoh: "Kantor Pusat Jakarta"
    'latitude',          // Koordinat lintang (misal: -6.2088)
    'longitude',         // Koordinat bujur (misal: 106.8456)
    'radius_meter',      // Radius area absen valid (contoh: 100 meter)
    'jam_masuk_standar', // Jam masuk normal (contoh: "08:00:00")
    'toleransi_menit',   // Menit toleransi (contoh: 15 menit)
    'is_active',         // Apakah lokasi ini aktif? (true/false)
];
```

---

### 4. 📝 Pengajuan & Verifikasi Izin

**Untuk orang awam:**  
Pegawai bisa mengajukan izin (sakit, cuti, atau dinas) lewat aplikasi. HRD kemudian melihat pengajuan tersebut dan bisa menyetujui atau menolaknya (dengan catatan alasan jika ditolak).

**Secara teknis:**

📄 **File: `app/Http/Controllers/Hrd/IzinController.php`**

```php
// HRD menyetujui izin
public function approve(Izin $izin)
{
    $izin->update([
        'status_persetujuan' => 'diterima',     // Status berubah jadi diterima
        'diverifikasi_oleh'  => auth()->id(),   // Dicatat siapa HRD yang approve
    ]);
}

// HRD menolak izin (wajib isi alasan penolakan)
public function reject(Request $request, Izin $izin)
{
    $request->validate([
        'catatan_hrd' => ['required', 'string', 'min:5'], // Alasan minimal 5 karakter
    ]);

    $izin->update([
        'status_persetujuan' => 'ditolak',
        'diverifikasi_oleh'  => auth()->id(),
        'catatan_hrd'        => $request->catatan_hrd, // Alasan penolakan disimpan
    ]);
}
```

📄 **File: `app/Models/Izin.php`** – Struktur data izin:

```
Tabel: izin
├── jenis_izin         → 'sakit', 'cuti', atau 'dinas'
├── tanggal_mulai      → Tanggal mulai izin
├── tanggal_selesai    → Tanggal selesai izin
├── keterangan         → Alasan izin dari pegawai
├── bukti_file         → File bukti (opsional, contoh: surat dokter)
├── status_persetujuan → 'pending', 'diterima', atau 'ditolak'
├── diverifikasi_oleh  → ID akun HRD yang memproses
└── catatan_hrd        → Catatan dari HRD (diisi jika ditolak)
```

---

### 5. 📊 Laporan & Export PDF (HRD)

**Untuk orang awam:**  
HRD bisa mencetak laporan kehadiran bulanan dalam format PDF. Laporan berisi rekap hadir, terlambat, dan izin setiap pegawai selama satu bulan.

**Secara teknis:**

📄 **File: `app/Http/Controllers/Hrd/AbsensiController.php`**

```php
public function exportPdf(Request $request)
{
    // Ambil data semua pegawai beserta absensi & izin di bulan yang dipilih
    $pegawaiList = Pegawai::with([
        'user',
        'absensi' => fn($q) => $q->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai]),
        'izin'    => fn($q) => $q->where('status_persetujuan', 'diterima')->whereBetween(...),
    ])->get();

    // Buat PDF dari template Blade dan langsung download
    $pdf = Pdf::loadView('pdf.laporan-bulanan', compact(...))->setPaper('a4', 'landscape');
    return $pdf->download("laporan-kehadiran-{$bulanNama}-{$tahun}.pdf");
}
```

> Menggunakan library **DomPDF** untuk mengubah halaman HTML menjadi file PDF yang bisa didownload.

---

### 6. 🗃️ Struktur Database

**Untuk orang awam:**  
Database adalah "gudang data" aplikasi. Setiap tabel seperti laci yang menyimpan satu jenis informasi.

📄 **Folder: `database/migrations/`** – Script yang membuat tabel-tabel database:

```
Tabel: users
├── id, name, email, no_hp
├── role → 'admin' | 'hrd' | 'pegawai'
└── password (tersimpan terenkripsi otomatis)

Tabel: pegawai
├── id, user_id (link ke tabel users / akun login)
├── nip, jabatan, divisi, no_hp, alamat
└── lokasi_kerja_id (link ke tabel lokasi_kerja)

Tabel: lokasi_kerja
├── id, nama_lokasi
├── latitude, longitude (koordinat GPS kantor)
├── radius_meter (area valid absen, contoh: 100 meter)
├── jam_masuk_standar, toleransi_menit
└── is_active (aktif/nonaktif)

Tabel: absensi
├── id, pegawai_id, lokasi_kerja_id
├── tanggal, jam_masuk, jam_pulang
├── latitude_masuk/pulang, longitude_masuk/pulang (posisi GPS saat absen)
├── jarak_masuk_meter, jarak_pulang_meter (jarak ke kantor saat absen)
├── foto_masuk, foto_pulang (path file selfie)
└── status → 'hadir' | 'terlambat'

Tabel: izin
├── id, pegawai_id
├── tanggal_mulai, tanggal_selesai
├── jenis_izin → 'sakit' | 'cuti' | 'dinas'
├── keterangan, bukti_file
├── status_persetujuan → 'pending' | 'diterima' | 'ditolak'
├── diverifikasi_oleh (ID HRD yang memproses)
└── catatan_hrd
```

---

### 7. 🛣️ Daftar URL Aplikasi (Routes)

📄 **File: `routes/web.php`** – Mengatur semua alamat halaman yang bisa diakses:

```
/             → Halaman landing / redirect ke dashboard jika sudah login
/offline      → Halaman offline (PWA fallback)
/dashboard    → Redirect otomatis sesuai role

[PEGAWAI] Prefix: /pegawai/
├── /dashboard          → Dashboard (status absensi hari ini)
├── /absensi/masuk      → Form absen masuk (GPS + foto)
├── /absensi/pulang     → Form absen pulang (GPS + foto)
├── /riwayat            → Riwayat absensi bulanan
└── /izin               → Pengajuan dan daftar izin

[ADMIN] Prefix: /admin/
├── /dashboard          → Dashboard admin (statistik)
├── /pegawai            → Manajemen data pegawai
├── /lokasi-kerja       → Manajemen lokasi kerja (CRUD)
└── /absensi            → Monitoring & rekap absensi

[HRD] Prefix: /hrd/
├── /monitoring         → Pantau absensi real-time per hari
├── /laporan            → Laporan bulanan semua pegawai
├── /laporan/export-pdf → Download PDF laporan
└── /izin               → Verifikasi pengajuan izin
```

---

### 8. 🔄 Hubungan Antar Data (Relasi)

```
User ──────── Pegawai ──────── LokasiKerja
                 │
                 ├──── Absensi (rekaman hadir/terlambat per hari)
                 │
                 └──── Izin (pengajuan sakit/cuti/dinas)
```

Setiap **Pegawai** terhubung ke satu **User** (akun login) dan satu **Lokasi Kerja**.  
Satu pegawai bisa punya banyak catatan **Absensi** dan banyak **Izin**.

📄 **File: `app/Models/Pegawai.php`** – Contoh relasi antar model:

```php
// Pegawai punya akun login
public function user()      { return $this->belongsTo(User::class, 'user_id'); }

// Pegawai ditetapkan ke satu lokasi kerja
public function lokasiKerja() { return $this->belongsTo(LokasiKerja::class, 'lokasi_kerja_id'); }

// Pegawai bisa punya banyak catatan absensi
public function absensi()   { return $this->hasMany(Absensi::class, 'pegawai_id'); }

// Pegawai bisa punya banyak pengajuan izin
public function izin()      { return $this->hasMany(Izin::class, 'pegawai_id'); }
```

---

## 🚀 Cara Install & Menjalankan

### Prasyarat
- PHP >= 8.3
- Composer
- Node.js & NPM

### Langkah-langkah

```bash
# 1. Clone atau download project, masuk ke foldernya

# 2. Install semua dependency PHP
composer install

# 3. Salin file konfigurasi environment
cp .env.example .env

# 4. Generate application key (keamanan aplikasi)
php artisan key:generate

# 5. Jalankan migrasi database (buat semua tabel)
php artisan migrate

# 6. Isi data awal (akun admin, hrd, pegawai contoh + lokasi kantor)
php artisan db:seed

# 7. Install dependency JavaScript
npm install

# 8. Build aset CSS/JS untuk production
npm run build

# 9. Jalankan server lokal
php artisan serve
```

Buka browser ke: **http://localhost:8000**

> Atau bisa juga jalankan semua sekaligus dengan: `composer run dev`

---

## 👤 Akun Demo (Default Seeder)

Setelah menjalankan `php artisan db:seed`, tersedia akun berikut:

| Role | Email | Password |
|------|-------|----------|
| Admin | `admin@lingkarkreatif.com` | `admin123` |
| HRD | `hrd@lingkarkreatif.com` | `hrd12345` |
| Pegawai | `budi@lingkarkreatif.com` | `pegawai123` |

> ⚠️ **Penting:** Ganti password akun-akun ini sebelum deploy ke production!

> 📄 File akun demo: `database/seeders/AdminSeeder.php`

---

## 📌 Hal Penting yang Perlu Diketahui

1. **Absensi menggunakan GPS nyata** — Pegawai harus mengizinkan browser mengakses lokasi saat absen.
2. **Foto selfie wajib** — Setiap absen masuk/pulang memerlukan foto dari kamera.
3. **Status otomatis** — Sistem otomatis menandai "terlambat" jika absen melebihi batas waktu + toleransi.
4. **Satu absen per hari** — Sistem mencegah double absen dalam satu hari.
5. **Pegawai tanpa lokasi kerja tidak bisa absen** — Admin harus menetapkan lokasi kerja terlebih dahulu.
6. **Validasi GPS di server** — Pengecekan jarak dilakukan di server (bukan hanya di browser), sehingga tidak bisa dimanipulasi.

---

## 📞 Informasi Proyek

**Dikembangkan untuk:** PT Lingkar Kreatif Solusi  
**Framework:** Laravel 13 (PHP 8.3+)  
**Database:** SQLite (mudah diubah ke MySQL di file `.env`)
