<?php
/**
 * setup.php — bikin tabel `projects` + data awal secara otomatis.
 * Cukup buka sekali di browser: https://domain-kamu/setup.php
 * Aman dibuka berkali-kali: tabel tidak dibuat ulang, data awal hanya diisi kalau tabel kosong.
 */
require_once __DIR__ . '/connection.php';

$pdo->exec("
    CREATE TABLE IF NOT EXISTS projects (
      id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
      title       VARCHAR(150) NOT NULL,
      description TEXT         NOT NULL,
      tech        VARCHAR(255) NOT NULL,
      category    VARCHAR(30)  NOT NULL DEFAULT 'other',
      file_path   VARCHAR(255)          DEFAULT NULL,
      created_at  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
");

$total  = (int) $pdo->query('SELECT COUNT(*) FROM projects')->fetchColumn();
$seeded = false;

if ($total === 0) {
    $stmt = $pdo->prepare('INSERT INTO projects (title, description, tech, category) VALUES (?, ?, ?, ?)');
    $seed = [
        ['Deteksi Teks AI-Generated',
         'Capstone project: model fine-tuned XLM-RoBERTa untuk mendeteksi berita berbahasa Inggris yang ditulis AI. Disajikan lewat REST API agar bisa digabung dengan sistem klasifikasi fake news tim.',
         'Python, XLM-RoBERTa, REST API, Gradio', 'ml'],
        ['Platform Donasi ala Saweria',
         'Platform tipping untuk kreator dengan integrasi payment gateway (QRIS), antrian job, dan notifikasi real-time ke OBS lewat WebSocket.',
         'NestJS, PostgreSQL, Redis, BullMQ', 'backend'],
        ['Website Portfolio + CMS',
         'Portfolio pribadi dengan halaman admin untuk mengelola project, pengalaman, dan CV langsung dari database.',
         'HTML, CSS, JavaScript, Supabase', 'fullstack'],
    ];
    foreach ($seed as $row) {
        $stmt->execute($row);
    }
    $seeded = true;
    $total  = count($seed);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Setup database</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="wrap section">
    <h1 class="section__title">Database siap</h1>
    <div class="alert alert--ok">
        Terhubung ke <b><?= htmlspecialchars($db['name']) ?></b>. Tabel <code>projects</code> sudah ada
        dan berisi <?= $total ?> project<?= $seeded ? ' (data awal baru saja diisi)' : '' ?>.
    </div>
    <p><a class="btn btn--primary" href="index.php">Buka portfolio</a></p>
</div>
</body>
</html>
