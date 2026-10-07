<?php
require_once __DIR__ . '/connection.php';
require_once __DIR__ . '/data.php';
require_once __DIR__ . '/functions.php';

// ===== SELECT: ambil semua project dari database =====
$stmt     = $pdo->query('SELECT * FROM projects ORDER BY created_at DESC, id DESC');
$projects = $stmt->fetchAll();

$pageTitle = $nama . ' — ' . $role;
require __DIR__ . '/partials/header.php';
?>

    <header class="hero">
        <h1 class="hero__name"><?= e($nama) ?></h1>
        <p class="hero__role"><?= e($role) ?></p>
        <p class="hero__campus"><?= e($kampus) ?></p>
        <p class="hero__bio"><?= e($bio) ?></p>

        <div class="hero__meta">
            <?php if ($open_to_work): ?>
                <span class="status status--open">Open to Work 🟢</span>
            <?php else: ?>
                <span class="status status--closed">Not Available 🔴</span>
            <?php endif; ?>
            <ul class="socials">
                <li><a href="<?= e($github) ?>" target="_blank" rel="noopener">GitHub</a></li>
                <li><a href="<?= e($linkedin) ?>" target="_blank" rel="noopener">LinkedIn</a></li>
                <li><a href="mailto:<?= e($email) ?>"><?= e($email) ?></a></li>
                <li><?= e($lokasi) ?></li>
            </ul>
        </div>
    </header>

    <main>
        <section class="section" id="skills">
            <h2 class="section__title">Skills</h2>
            <ul class="skills">
                <?php foreach ($skills as $skill): ?>
                    <li class="skill"><?= e($skill) ?></li>
                <?php endforeach; ?>
            </ul>
        </section>

        <section class="section" id="skill-tree">
            <h2 class="section__title">Skill tree</h2>
            <p class="section__lead"><?= countSkills($skill_tree) ?> node, dirender oleh fungsi rekursif <code>renderSkillTree()</code>.</p>
            <div class="tree-columns">
                <?php foreach ($skill_tree as $root => $children): ?>
                    <div><?= renderSkillTree([$root => $children]) ?></div>
                <?php endforeach; ?>
            </div>
        </section>

        <div id="flash"><?= renderFlash() ?></div>
        <?= renderPortfolioSection('Projects', $projects) ?>
    </main>

<?php require __DIR__ . '/partials/footer.php'; ?>
