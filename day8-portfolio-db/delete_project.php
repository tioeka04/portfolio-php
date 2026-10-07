<?php
require_once __DIR__ . '/connection.php';
require_once __DIR__ . '/functions.php';

// Hapus hanya lewat POST (bukan link GET) supaya tidak terhapus tanpa sengaja
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}
checkCsrf();

$id      = (int) ($_POST['id'] ?? 0);
$project = findProject($pdo, $id);

if (!$project) {
    setFlash('error', 'Project tidak ditemukan atau sudah dihapus.');
    redirect('index.php#flash');
}

// ===== DELETE FROM =====
$stmt = $pdo->prepare('DELETE FROM projects WHERE id = ?');
$stmt->execute([$id]);

// Hapus file terkait dari uploads/ pakai unlink()
if (!empty($project['file_path'])) {
    deleteUploadedFile($project['file_path']);
}

setFlash('ok', 'Project "' . $project['title'] . '" dan file-nya berhasil dihapus.');
redirect('index.php#flash');
