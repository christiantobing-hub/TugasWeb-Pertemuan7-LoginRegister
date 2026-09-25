# Sistem Login/Register — Tugas Rutin 7

Sistem otentikasi sederhana dengan **PHP Native** menggunakan file **JSON** (`data/users.json`) sebagai penyimpanan data. Sudah diuji end-to-end (register, duplikasi email, login gagal/berhasil, proteksi dashboard, edit profile, logout, dan remember me) menggunakan PHP built-in server.

## Struktur File

```
login-system/
├── index.php          # Entry point → redirect ke dashboard/login
├── config.php          # Konfigurasi session & path data
├── functions.php       # Helper: baca/tulis JSON, sanitasi, validasi, auth
├── register.php        # Form & proses registrasi
├── login.php            # Form & proses login (+ Remember Me)
├── dashboard.php       # Halaman diproteksi (butuh login)
├── edit_profile.php    # Edit nama/email/password (bonus)
├── logout.php           # Hapus session & cookie
├── data/
│   ├── users.json      # "Database" JSON
│   └── .htaccess       # Blokir akses langsung ke folder data
└── assets/
    └── style.css        # Tampilan rapi & modern
```

## Cara Menjalankan

1. Pastikan PHP terpasang (PHP 7.4+ direkomendasikan, sudah diuji di PHP 8.3).
2. Dari dalam folder `login-system/`, jalankan server bawaan PHP:
   ```bash
   php -S localhost:8000
   ```
3. Buka `http://localhost:8000` di browser. Anda akan diarahkan ke halaman login.
4. Klik **"Daftar sekarang"** untuk membuat akun baru, lalu login.

> Jika di-deploy ke Apache/Nginx, pastikan folder `data/` tidak bisa diakses langsung dari browser (file `.htaccess` sudah disediakan untuk Apache; untuk Nginx tambahkan rule `location /data { deny all; }`).

## Pemenuhan Requirements

| # | Requirement | Implementasi |
|---|---|---|
| 1 | Form registrasi dengan validasi (nama, email, password) | `register.php` — validasi panjang nama, email, password ≥6 karakter, konfirmasi password |
| 2 | Validasi email dengan `filter_var()` | `functions.php → isValidEmail()` |
| 3 | Password di-hash dengan `password_hash()` | `register.php`, `edit_profile.php` menggunakan `PASSWORD_DEFAULT` |
| 4 | Data disimpan di file JSON | `data/users.json`, dikelola oleh `getAllUsers()` / `saveAllUsers()` dengan `LOCK_EX` |
| 5 | Cek duplikasi email saat registrasi | `findUserByEmail()` dipanggil sebelum simpan user baru |
| 6 | Sistem login dengan session | `login.php` set `$_SESSION`, diverifikasi dengan `password_verify()` |
| 7 | Dashboard yang diproteksi (redirect jika belum login) | `requireLogin()` di `dashboard.php` & `edit_profile.php` |
| 8 | Logout functionality (`session_destroy`) | `logout.php` — hapus session, cookie, dan remember token |
| 9 | Sanitasi input dengan `htmlspecialchars()` | `sanitizeInput()` dipakai di semua input teks (nama, dsb) |
| 10 | Pesan error & sukses yang jelas | Flash message (`setFlash`/`getFlash`) + validasi per-field di form |

## Fitur Bonus yang Diimplementasikan

- ⭐ **Remember Me**: checkbox di form login menyimpan token acak (32 byte, `random_bytes`) di cookie HttpOnly selama 30 hari dan di `users.json`. Saat session kosong tapi cookie valid, user otomatis login lagi (lihat `requireLogin()`).
- ⭐ **Edit Profile**: `edit_profile.php` memungkinkan ubah nama, email, dan password (opsional), dengan verifikasi password saat ini sebelum perubahan disimpan.
- ⭐ **Tampilan CSS Rapi**: `assets/style.css` — desain modern dengan gradient background, card, dan warna konsisten untuk semua halaman.

## Catatan Keamanan

- Password **tidak pernah** disimpan dalam bentuk plain text — selalu di-hash dengan `password_hash()` (algoritma bcrypt default).
- Pesan error login sengaja generik ("Email atau password salah") agar tidak membocorkan email mana yang terdaftar.
- Remember token dibandingkan dengan `hash_equals()` untuk mencegah timing attack.
- Semua output ke HTML di-escape dengan `htmlspecialchars()` untuk mencegah XSS.
- Folder `data/` diblokir dari akses langsung browser via `.htaccess`.
