<?php
/**
 * connection.php — koneksi ke database pakai PDO.
 * Di-include di setiap halaman yang butuh database.
 * Setting database ada di config.php.
 */
require_once __DIR__ . '/config.php';

try {
    $pdo = new PDO(
        "mysql:host={$db['host']};dbname={$db['name']};charset=utf8mb4",
        $db['user'],
        $db['pass'],
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // error jadi exception
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // hasil query = array asosiatif
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $ex) {
    http_response_code(500);
    $hint = $isLocal
        ? 'Pastikan MySQL di XAMPP/Laragon sudah jalan dan <code>database.sql</code> sudah di-import.'
        : 'Cek lagi 4 isian di <code>config.php</code> (hostname, nama DB, username, password) '
          . 'sesuai menu "MySQL Databases" di control panel hosting.';
    exit('<p style="font-family:sans-serif;padding:24px;max-width:640px">Gagal terhubung ke database <b>'
        . htmlspecialchars($db['name']) . '</b>. ' . $hint . '<br><small>'
        . htmlspecialchars($ex->getMessage()) . '</small></p>');
}
