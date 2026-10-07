<?php
require_once __DIR__ . '/connection.php';
require_once __DIR__ . '/data.php';
require_once __DIR__ . '/functions.php';

$form   = ['title' => '', 'description' => '', 'tech' => '', 'category' => ''];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    checkCsrf();

    // Ambil & rapikan input
    foreach ($form as $key => $_) {
        $form[$key] = trim((string) ($_POST[$key] ?? ''));
    }

    // Kalau total request > post_max_size, PHP mengosongkan $_POST & $_FILES
    if (empty($_POST) && empty($_FILES) && (int) ($_SERVER['CONTENT_LENGTH'] ?? 0) > 0) {
        $errors['file'] = 'Ukuran file terlalu besar. Maksimal 2 MB.';
    }

    // 1) Validasi field teks
    $errors += validateProjectInput($form);

    // 2) Validasi file (wajib di form tambah)
    $fileError = validateUpload($_FILES['file'] ?? null, true);
    if ($fileError) {
        $errors['file'] = $fileError;
    }

    // 3) Simpan kalau semua valid
    if (empty($errors)) {
        $filePath = storeUpload($_FILES['file']);   // mkdir() uploads/ otomatis di sini

        if ($filePath === false) {
            $errors['file'] = 'File gagal disimpan. Cek izin tulis folder uploads/.';
        } else {
            $stmt = $pdo->prepare(
                'INSERT INTO projects (title, description, tech, category, file_path)
                 VALUES (:title, :description, :tech, :category, :file_path)'
            );
            $stmt->execute([
                ':title'       => $form['title'],
                ':description' => $form['description'],
                ':tech'        => $form['tech'],
                ':category'    => $form['category'],
                ':file_path'   => $filePath,
            ]);

            setFlash('ok', 'Project "' . $form['title'] . '" berhasil ditambahkan.');
            redirect('index.php#flash');
        }
    }
}

$pageTitle = 'Tambah project — ' . $nama;
$isEdit = false;
$currentFile = null;
require __DIR__ . '/partials/header.php';
?>
    <main class="section">
        <h1 class="section__title">Tambah project</h1>
        <p class="section__lead">Semua field wajib diisi. Data disimpan ke tabel <code>projects</code>, file ke folder <code>uploads/</code>.</p>
        <?php require __DIR__ . '/partials/project_form.php'; ?>
    </main>
<?php require __DIR__ . '/partials/footer.php'; ?>
