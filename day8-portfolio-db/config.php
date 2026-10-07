<?php
/**
 * config.php — SATU-SATUNYA file yang perlu diedit saat deploy.
 *
 * - Di laptop (localhost)  → otomatis pakai setting XAMPP/Laragon.
 * - Di InfinityFree        → pakai setting HOSTING di bawah.
 *   Semua nilainya ada di control panel InfinityFree → menu "MySQL Databases".
 */

// ===== HOSTING (InfinityFree) — ISI 4 BARIS INI =====
$hosting = [
    'host' => 'sqlXXX.infinityfree.com',   // "MySQL Hostname", contoh: sql305.infinityfree.com
    'name' => 'if0_XXXXXXXX_portfolio',    // "MySQL DB Name",  contoh: if0_37123456_portfolio
    'user' => 'if0_XXXXXXXX',              // "MySQL Username", contoh: if0_37123456
    'pass' => 'PASSWORD_HOSTING_KAMU',     // password akun hosting (vPanel), bukan password login web
];

// ===== LOKAL (XAMPP / Laragon) =====
$local = [
    'host' => 'localhost',
    'name' => 'portfolio_db',
    'user' => 'root',
    'pass' => '',
];

// Deteksi otomatis: lagi jalan di laptop atau di hosting?
$serverHost = strtolower(explode(':', $_SERVER['HTTP_HOST'] ?? 'localhost')[0]);
$isLocal    = in_array($serverHost, ['localhost', '127.0.0.1', '::1'], true)
           || substr($serverHost, -5) === '.test';   // domain Laragon, misal portfolio.test

$db = $isLocal ? $local : $hosting;
