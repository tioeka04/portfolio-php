<?php
/**
 * functions.php — kumpulan fungsi untuk me-render bagian-bagian portfolio.
 */

/**
 * Helper: escape teks sebelum di-echo ke HTML (mencegah XSS).
 * Contoh fungsi yang MENGEMBALIKAN NILAI.
 */
function e($text)
{
    return htmlspecialchars((string) $text, ENT_QUOTES, 'UTF-8');
}

/**
 * (Bonus 7) SWITCH — ubah kode kategori jadi label + class CSS.
 */
function getCategoryInfo($category)
{
    switch (strtolower($category)) {
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

/**
 * 5. Fungsi dengan parameter — menghasilkan HTML 1 card project.
 *
 * @param string $title
 * @param string $description
 * @param array  $tech      daftar teknologi
 * @param string $link      URL repo / demo
 * @param string $category  frontend | backend | fullstack | ml | lainnya
 */
function renderProjectCard($title, $description, $tech, $link, $category = 'other')
{
    // Variabel STATIC: nilainya tetap tersimpan antar pemanggilan fungsi.
    // Dipakai untuk memberi id unik ke setiap card.
    static $cardNumber = 0;
    $cardNumber++;

    $cat = getCategoryInfo($category); // memanggil fungsi lain

    $techList = '';
    foreach ($tech as $t) {
        $techList .= '<li>' . e($t) . '</li>';
    }

    return '
    <article class="card ' . e($cat['class']) . '" id="project-' . $cardNumber . '">
        <div class="card__head">
            <h3 class="card__title">' . e($title) . '</h3>
            <span class="card__cat">' . e($cat['label']) . '</span>
        </div>
        <p class="card__desc">' . e($description) . '</p>
        <ul class="card__tech" aria-label="Teknologi">' . $techList . '</ul>
        <div class="card__foot">
            <a class="btn" href="' . e($link) . '" target="_blank" rel="noopener">Lihat repository</a>
        </div>
    </article>';
}

/**
 * 6. Fungsi yang memanggil fungsi lain — render 1 section berisi banyak card.
 * Di dalamnya ada FOREACH yang memanggil renderProjectCard() untuk tiap item.
 */
function renderPortfolioSection($title, $items)
{
    $cards = '';
    foreach ($items as $item) {
        $cards .= renderProjectCard(
            $item['title'],
            $item['description'],
            $item['tech'],
            $item['link'],
            $item['category']
        );
    }

    return '
    <section class="section" id="projects">
        <h2 class="section__title">' . e($title) . '</h2>
        <p class="section__lead">' . count($items) . ' project, dirender lewat renderPortfolioSection() → renderProjectCard().</p>
        <div class="projects">' . $cards . '</div>
    </section>';
}

/**
 * (Bonus 8) FUNGSI REKURSIF — render nested array jadi list bertingkat.
 * Fungsi ini memanggil dirinya sendiri untuk setiap node yang punya anak.
 */
function renderSkillTree($tree, $level = 0)
{
    // Base case: kalau array kosong, tidak ada yang di-render.
    if (empty($tree)) {
        return '';
    }

    $class = ($level === 0) ? 'tree' : '';
    $html  = '<ul' . ($class ? ' class="' . $class . '"' : '') . '>';

    foreach ($tree as $name => $children) {
        if ($level === 0) {
            $nodeClass = 'tree__node tree__node--root';
        } elseif (!empty($children)) {
            $nodeClass = 'tree__node tree__node--branch';
        } else {
            $nodeClass = 'tree__node';
        }

        $html .= '<li><span class="' . $nodeClass . '">' . e($name) . '</span>';
        $html .= renderSkillTree($children, $level + 1); // ← rekursi
        $html .= '</li>';
    }

    return $html . '</ul>';
}

/**
 * Rekursi lagi (fungsi yang mengembalikan nilai): hitung total skill di tree.
 */
function countSkills($tree)
{
    $total = 0;
    foreach ($tree as $children) {
        $total += 1 + countSkills($children);
    }
    return $total;
}

/**
 * Contoh variabel GLOBAL — fungsi mengambil $nama & $tahun dari luar fungsi
 * pakai keyword `global`.
 */
function renderFooter()
{
    global $nama, $tahun;
    return '<footer class="footer"><div class="wrap">© ' . e($tahun) . ' ' . e($nama)
         . '. Dibuat dengan PHP ' . e(PHP_VERSION) . '.</div></footer>';
}
