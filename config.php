<?php
/**
 * config.php
 * Konfigurasi dasar: session, path penyimpanan data JSON, dan pengaturan cookie.
 */

// Mulai session di awal sebelum ada output apapun
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Path ke file penyimpanan data (JSON sebagai "database")
define('DATA_DIR', __DIR__ . '/data');
define('USERS_FILE', DATA_DIR . '/users.json');

// Pastikan folder data ada
if (!is_dir(DATA_DIR)) {
    mkdir(DATA_DIR, 0755, true);
}

// Pastikan file users.json ada, inisialisasi dengan array kosong jika belum ada
if (!file_exists(USERS_FILE)) {
    file_put_contents(USERS_FILE, json_encode([], JSON_PRETTY_PRINT));
}

// Nama cookie untuk fitur "Remember Me"
define('REMEMBER_COOKIE_NAME', 'remember_token');
define('REMEMBER_COOKIE_DURATION', 60 * 60 * 24 * 30); // 30 hari
