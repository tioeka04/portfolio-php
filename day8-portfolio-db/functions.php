<?php
/**
 * functions.php — helper, render, validasi & upload.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ---------- Konstanta upload ----------
const UPLOAD_DIR      = __DIR__ . '/uploads';   // path folder di server
const UPLOAD_URL_DIR  = 'uploads';              // path yang disimpan di DB
const MAX_FILE_SIZE   = 2 * 1024 * 1024;        // 2 MB
const ALLOWED_TYPES   = [                       // ekstensi => MIME yang diizinkan
    'pdf'  => ['application/pdf'],
    'jpg'  => ['image/jpeg'],
    'jpeg' => ['image/jpeg'],
    'png'  => ['image/png'],
];
const CATEGORIES = [
    'frontend'  => 'Frontend',
    'backend'   => 'Backend',
    'fullstack' => 'Fullstack',
    'ml'        => 'Machine Learning',
    'other'     => 'Other',
];

// ==========================================================
// Helper umum
// ==========================================================
function e($text)
{
    return htmlspecialchars((string) $text, ENT_QUOTES, 'UTF-8');
}

/** Panjang teks (aman walau ekstensi mbstring tidak aktif). */
function textLength($text)
{
    return function_exists('mb_strlen') ? mb_strlen($text, 'UTF-8') : strlen($text);
}

function redirect($url)
{
    header('Location: ' . $url);
    exit;
}

/** Simpan pesan sekali tampil (muncul setelah redirect). */
function setFlash($type, $message)
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function renderFlash()
{
    if (empty($_SESSION['flash'])) {
        return '';
    }
    $f = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return '<div class="alert alert--' . e($f['type']) . '" role="status">' . e($f['message']) . '</div>';
}

/** Token CSRF sederhana untuk form POST (hapus/simpan). */
function csrfToken()
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['csrf'];
}

function csrfField()
{
    return '<input type="hidden" name="csrf" value="' . e(csrfToken()) . '">';
}

function checkCsrf()
{
    $sent = $_POST['csrf'] ?? '';
    if (!is_string($sent) || !hash_equals(csrfToken(), $sent)) {
        http_response_code(400);
        exit('Token form tidak valid. Muat ulang halaman lalu coba lagi.');
    }
}

// ==========================================================
// Render (lanjutan dari Day 7)
// ==========================================================

/** SWITCH — kode kategori → label + class CSS. */
function getCategoryInfo($category)
{
    switch (strtolower((string) $category)) {
        case 'frontend':
            $label = 'Frontend';
            break;
        case 'backend':
            $label = 'Backend';
            break;
        case 'fullstack':
            $label = 'Fullstack';
            break;
        case 'ml':
            $label = 'Machine Learning';
            break;
        default:
            $label = 'Other';
            $category = 'other';
    }
    return ['label' => $label, 'class' => 'card--' . strtolower($category)];
}

/** Ubah "PHP, MySQL" → ['PHP', 'MySQL']. */
function splitTech($tech)
{
    return array_values(array_filter(array_map('trim', explode(',', (string) $tech)), 'strlen'));
}

/**
 * Render 1 card project dari 1 baris database.
 * Ada link Download, Edit, dan tombol Hapus.
 */
function renderProjectCard($project)
{
    $cat = getCategoryInfo($project['category']);

    $techList = '';
    foreach (splitTech($project['tech']) as $t) {
        $techList .= '<li>' . e($t) . '</li>';
    }

    if (!empty($project['file_path'])) {
        $ext = strtoupper(pathinfo($project['file_path'], PATHINFO_EXTENSION));
        $download = '<a class="btn btn--primary" href="download.php?id=' . (int) $project['id'] . '">Download ' . e($ext) . '</a>';
    } else {
        $download = '<span class="card__nofile">Belum ada file</span>';
    }

    $date = date('d M Y', strtotime($project['created_at']));

    return '
    <article class="card ' . e($cat['class']) . '" id="project-' . (int) $project['id'] . '">
        <div class="card__head">
            <h3 class="card__title">' . e($project['title']) . '</h3>
            <span class="card__cat">' . e($cat['label']) . '</span>
        </div>
        <p class="card__desc">' . nl2br(e($project['description'])) . '</p>
        <ul class="card__tech" aria-label="Teknologi">' . $techList . '</ul>
        <div class="card__foot">
            <span class="card__date">' . e($date) . '</span>
            ' . $download . '
            <a class="btn" href="edit_project.php?id=' . (int) $project['id'] . '">Edit</a>
            <form class="inline-form" method="post" action="delete_project.php"
                  onsubmit="return confirm(\'Hapus project &quot;' . e(addslashes($project['title'])) . '&quot;? File-nya juga ikut terhapus.\');">
                ' . csrfField() . '
                <input type="hidden" name="id" value="' . (int) $project['id'] . '">
                <button type="submit" class="btn btn--danger">Hapus</button>
            </form>
        </div>
    </article>';
}

/** Fungsi yang memanggil fungsi lain (renderProjectCard). */
function renderPortfolioSection($title, $items)
{
    if (empty($items)) {
        $body = '<div class="empty"><p>Belum ada project di database.</p>'
              . '<a class="btn btn--primary" href="add_project.php">Tambah project pertama</a></div>';
    } else {
        $body = '<div class="projects">';
        foreach ($items as $item) {
            $body .= renderProjectCard($item);
        }
        $body .= '</div>';
    }

    return '
    <section class="section" id="projects">
        <h2 class="section__title">' . e($title) . '</h2>
        <p class="section__lead">' . count($items) . ' project, diambil dari tabel <code>projects</code> dengan query SELECT.</p>
        ' . $body . '
    </section>';
}

/** Fungsi rekursif — skill tree. */
function renderSkillTree($tree, $level = 0)
{
    if (empty($tree)) {
        return '';
    }
    $html = '<ul' . ($level === 0 ? ' class="tree"' : '') . '>';
    foreach ($tree as $name => $children) {
        if ($level === 0) {
            $cls = 'tree__node tree__node--root';
        } elseif (!empty($children)) {
            $cls = 'tree__node tree__node--branch';
        } else {
            $cls = 'tree__node';
        }
        $html .= '<li><span class="' . $cls . '">' . e($name) . '</span>'
               . renderSkillTree($children, $level + 1)
               . '</li>';
    }
    return $html . '</ul>';
}

function countSkills($tree)
{
    $total = 0;
    foreach ($tree as $children) {
        $total += 1 + countSkills($children);
    }
    return $total;
}

// ==========================================================
// Validasi & file
// ==========================================================

/**
 * Validasi field teks. Mengembalikan array error (kosong = valid).
 */
function validateProjectInput($data)
{
    $errors = [];

    if ($data['title'] === '') {
        $errors['title'] = 'Judul project wajib diisi.';
    } elseif (textLength($data['title']) > 150) {
        $errors['title'] = 'Judul maksimal 150 karakter.';
    }

    if ($data['description'] === '') {
        $errors['description'] = 'Deskripsi wajib diisi.';
    }

    if ($data['tech'] === '') {
        $errors['tech'] = 'Teknologi wajib diisi (pisahkan dengan koma).';
    } elseif (textLength($data['tech']) > 255) {
        $errors['tech'] = 'Daftar teknologi maksimal 255 karakter.';
    }

    if ($data['category'] === '') {
        $errors['category'] = 'Pilih kategori project.';
    } elseif (!array_key_exists($data['category'], CATEGORIES)) {
        $errors['category'] = 'Kategori tidak dikenal.';
    }

    return $errors;
}

/**
 * Validasi file upload.
 * @param array|null $file     elemen dari $_FILES
 * @param bool       $required true = wajib upload (form tambah)
 * @return string|null pesan error, atau null kalau valid / tidak ada file (opsional)
 */
function validateUpload($file, $required)
{
    if (!$file || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return $required ? 'File pendukung wajib diupload (PDF, JPG, atau PNG).' : null;
    }

    switch ($file['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            return 'Ukuran file melebihi batas 2 MB.';
        case UPLOAD_ERR_PARTIAL:
            return 'File hanya terupload sebagian. Coba upload ulang.';
        default:
            return 'Upload gagal di server (kode ' . (int) $file['error'] . ').';
    }

    if ($file['size'] > MAX_FILE_SIZE) {
        return 'Ukuran file ' . round($file['size'] / 1048576, 2) . ' MB, maksimal 2 MB.';
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!array_key_exists($ext, ALLOWED_TYPES)) {
        return 'Tipe file .' . $ext . ' tidak diizinkan. Gunakan PDF, JPG, atau PNG.';
    }

    // Cek isi file asli (bukan cuma nama), kalau ekstensi fileinfo aktif.
    if (function_exists('finfo_open')) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime  = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        if (!in_array($mime, ALLOWED_TYPES[$ext], true)) {
            return 'Isi file tidak sesuai dengan ekstensi .' . $ext . '.';
        }
    }

    return null;
}

/** Buat folder uploads/ otomatis kalau belum ada. */
function ensureUploadDir()
{
    if (!is_dir(UPLOAD_DIR)) {
        if (!mkdir(UPLOAD_DIR, 0755, true)) {
            return false;
        }
    }
    // Cegah file .php dieksekusi dari folder uploads (Apache).
    $htaccess = UPLOAD_DIR . '/.htaccess';
    if (!file_exists($htaccess)) {
        file_put_contents($htaccess, "Options -Indexes\n<FilesMatch \"\\.(php|phtml|phar)$\">\n  Require all denied\n</FilesMatch>\n");
    }
    return is_writable(UPLOAD_DIR);
}

/**
 * Pindahkan file ke uploads/ dengan nama acak.
 * @return string|false path relatif untuk disimpan ke DB, contoh "uploads/a1b2c3.pdf"
 */
function storeUpload($file)
{
    if (!ensureUploadDir()) {
        return false;
    }
    $ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $filename = date('Ymd') . '_' . bin2hex(random_bytes(6)) . '.' . $ext;

    if (!move_uploaded_file($file['tmp_name'], UPLOAD_DIR . '/' . $filename)) {
        return false;
    }
    return UPLOAD_URL_DIR . '/' . $filename;
}

/**
 * Ubah path dari DB jadi path absolut yang aman (harus di dalam uploads/).
 * @return string|null
 */
function resolveUploadPath($relativePath)
{
    if (!$relativePath) {
        return null;
    }
    $full = realpath(__DIR__ . '/' . $relativePath);
    $base = realpath(UPLOAD_DIR);
    if ($full === false || $base === false || strpos($full, $base . DIRECTORY_SEPARATOR) !== 0) {
        return null;
    }
    return $full;
}

/** Hapus file fisik dari uploads/ pakai unlink(). */
function deleteUploadedFile($relativePath)
{
    $full = resolveUploadPath($relativePath);
    if ($full && is_file($full)) {
        return unlink($full);
    }
    return false;
}

/** Ambil 1 project berdasarkan id. */
function findProject($pdo, $id)
{
    $stmt = $pdo->prepare('SELECT * FROM projects WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch() ?: null;
}
