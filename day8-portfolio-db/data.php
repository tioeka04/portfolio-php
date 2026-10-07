<?php
/**
 * data.php — semua data portfolio disimpan di sini sebagai variabel & array PHP.
 * Data diri tetap di sini; data project pindah ke database (lihat index.php).
 */

// ===== 1. Data diri (variabel PHP) =====
$nama      = 'Tio';                                   // TODO: ganti dengan nama lengkap
$role      = 'Backend & Full Stack Developer';
$kampus    = 'Informatika, Universitas Mercu Buana';
$bio       = 'Mahasiswa Informatika yang suka membangun sistem dari sisi server: '
           . 'merancang database, membuat REST API, sampai men-deploy model machine learning '
           . 'supaya bisa dipakai aplikasi lain.';
$lokasi    = 'Jakarta, Indonesia';
$email     = 'email@contoh.com';                      // TODO: ganti email asli
$github    = 'https://github.com/tioeka04';
$linkedin  = 'https://www.linkedin.com/in/username';  // TODO: ganti link LinkedIn
$tahun     = date('Y');

// ===== 3. Status ketersediaan (dipakai di IF/ELSE) =====
$open_to_work = true;

// ===== 2. Daftar skill (array) =====
// Tambah skill baru? Cukup tambah 1 item di sini.
$skills = ['HTML', 'CSS', 'JavaScript', 'PHP', 'MySQL', 'PostgreSQL', 'Node.js', 'Python', 'Git'];

// Data project sekarang diambil dari DATABASE (tabel `projects`), bukan array lagi.

// ===== 8. Skill tree (nested array) untuk fungsi rekursif =====
// Key = nama node, value = array anak-anaknya (array kosong = daun).
$skill_tree = [
    'Web Development' => [
        'Frontend' => [
            'HTML' => [],
            'CSS'  => [
                'Flexbox' => [],
                'Grid'    => [],
            ],
            'JavaScript' => [
                'DOM' => [],
                'Fetch API' => [],
            ],
        ],
        'Backend' => [
            'PHP' => [
                'Function' => [],
                'Array & Loop' => [],
            ],
            'Node.js' => [
                'NestJS' => [],
            ],
            'Database' => [
                'MySQL' => [],
                'PostgreSQL' => [],
            ],
        ],
    ],
    'Machine Learning' => [
        'NLP' => [
            'Transformers' => [
                'XLM-RoBERTa' => [],
            ],
        ],
        'Computer Vision' => [],
    ],
];
