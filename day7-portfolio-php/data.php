<?php
/**
 * data.php — semua data portfolio disimpan di sini sebagai variabel & array PHP.
 * Mau update isi portfolio? Cukup edit file ini, HTML-nya nggak perlu disentuh.
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

// ===== Daftar project (array asosiatif / array of array) =====
$projects = [
    [
        'title'       => 'Deteksi Teks AI-Generated',
        'description' => 'Capstone project: model fine-tuned XLM-RoBERTa untuk mendeteksi berita '
                       . 'berbahasa Inggris yang ditulis AI. Disajikan lewat REST API agar bisa '
                       . 'digabung dengan sistem klasifikasi fake news tim.',
        'tech'        => ['Python', 'XLM-RoBERTa', 'REST API', 'Gradio'],
        'link'        => 'https://github.com/tioeka04',
        'category'    => 'ml',
    ],
    [
        'title'       => 'Platform Donasi ala Saweria',
        'description' => 'Platform tipping untuk kreator dengan integrasi payment gateway (QRIS), '
                       . 'antrian job, dan notifikasi real-time ke OBS lewat WebSocket.',
        'tech'        => ['NestJS', 'PostgreSQL', 'Redis', 'BullMQ'],
        'link'        => 'https://github.com/tioeka04',
        'category'    => 'backend',
    ],
    [
        'title'       => 'Website Portfolio + CMS',
        'description' => 'Portfolio pribadi dengan halaman admin untuk mengelola project, '
                       . 'pengalaman, dan CV langsung dari database.',
        'tech'        => ['HTML', 'CSS', 'JavaScript', 'Supabase'],
        'link'        => 'https://github.com/tioeka04/portofolio',
        'category'    => 'fullstack',
    ],
    [
        'title'       => 'Landing Page Responsif',
        'description' => 'Latihan slicing desain ke HTML & CSS dengan layout Flexbox dan Grid '
                       . 'yang rapi dari layar HP sampai desktop.',
        'tech'        => ['HTML', 'CSS'],
        'link'        => 'https://github.com/tioeka04',
        'category'    => 'frontend',
    ],
];

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
