<?php
require_once __DIR__ . '/connection.php';
require_once __DIR__ . '/functions.php';

$id      = (int) ($_GET['id'] ?? 0);
$project = findProject($pdo, $id);
$path    = $project ? resolveUploadPath($project['file_path']) : null;

if (!$path || !is_file($path)) {
    setFlash('error', 'File tidak ditemukan di server.');
    redirect('index.php#flash');
}

// Nama file saat diunduh: judul-project.ext
$ext  = strtolower(pathinfo($path, PATHINFO_EXTENSION));
$slug = trim(preg_replace('/[^a-z0-9]+/', '-', strtolower($project['title'])), '-');
$name = ($slug ?: 'project-file') . '.' . $ext;

$mime = [
    'pdf'  => 'application/pdf',
    'jpg'  => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png'  => 'image/png',
][$ext] ?? 'application/octet-stream';

header('Content-Type: ' . $mime);
header('Content-Disposition: attachment; filename="' . $name . '"');
header('Content-Length: ' . filesize($path));
header('X-Content-Type-Options: nosniff');
readfile($path);
exit;
