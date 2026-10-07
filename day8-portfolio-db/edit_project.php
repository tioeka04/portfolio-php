<?php
require_once __DIR__ . '/connection.php';
require_once __DIR__ . '/data.php';
require_once __DIR__ . '/functions.php';

// Ambil project berdasarkan ?id=
$id      = (int) ($_GET['id'] ?? 0);
$project = findProject($pdo, $id);

if (!$project) {
    setFlash('error', 'Project tidak ditemukan.');
    redirect('index.php#flash');
}

$form = [
    'title'       => $project['title'],
    'description' => $project['description'],
    'tech'        => $project['tech'],
    'category'    => $project['category'],
];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    checkCsrf();

    foreach ($form as $key => $_) {
        $form[$key] = trim((string) ($_POST[$key] ?? ''));
    }

    if (empty($_POST) && empty($_FILES) && (int) ($_SERVER['CONTENT_LENGTH'] ?? 0) > 0) {
        $errors['file'] = 'Ukuran file terlalu besar. Maksimal 2 MB.';
    }

    $errors += validateProjectInput($form);

    // File opsional saat edit
    $fileError = validateUpload($_FILES['file'] ?? null, false);
    if ($fileError) {
        $errors['file'] = $fileError;
    }

    if (empty($errors)) {
        $filePath = $project['file_path'];
        $hasNewFile = isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK;

        if ($hasNewFile) {
            $newPath = storeUpload($_FILES['file']);
            if ($newPath === false) {
                $errors['file'] = 'File gagal disimpan. Cek izin tulis folder uploads/.';
            } else {
                $filePath = $newPath;
            }
        }

        if (empty($errors)) {
            // ===== UPDATE (bonus) =====
            $stmt = $pdo->prepare(
                'UPDATE projects
                    SET title = :title, description = :description, tech = :tech,
                        category = :category, file_path = :file_path
                  WHERE id = :id'
            );
            $stmt->execute([
                ':title'       => $form['title'],
                ':description' => $form['description'],
                ':tech'        => $form['tech'],
                ':category'    => $form['category'],
                ':file_path'   => $filePath,
                ':id'          => $id,
            ]);

            // File lama dihapus setelah data baru tersimpan
            if ($hasNewFile && $project['file_path'] && $project['file_path'] !== $filePath) {
                deleteUploadedFile($project['file_path']);
            }

            setFlash('ok', 'Project "' . $form['title'] . '" berhasil diperbarui.');
            redirect('index.php#flash');
        }
    }
}

$pageTitle   = 'Edit project — ' . $nama;
$isEdit      = true;
$currentFile = $project['file_path'];
require __DIR__ . '/partials/header.php';
?>
    <main class="section">
        <h1 class="section__title">Edit project</h1>
        <p class="section__lead">Perubahan disimpan dengan query UPDATE. Kosongkan field file kalau tidak ingin mengganti file.</p>
        <?php require __DIR__ . '/partials/project_form.php'; ?>
    </main>
<?php require __DIR__ . '/partials/footer.php'; ?>
