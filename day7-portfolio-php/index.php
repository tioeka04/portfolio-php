<?php
require_once __DIR__ . '/data.php';
require_once __DIR__ . '/functions.php';

// Contoh SUPERGLOBAL ($_GET): status bisa di-override lewat URL buat demo,
// misal index.php?available=0  → badge berubah jadi "Not Available".
if (isset($_GET['available'])) {
    $open_to_work = ($_GET['available'] === '1');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($nama) ?> — <?= e($role) ?></title>
    <meta name="description" content="<?= e($bio) ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Familjen+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<div class="wrap">
    <nav class="nav">
        <a class="nav__brand" href="#"><?= e($nama) ?></a>
        <div class="nav__links">
            <a href="#skills">Skills</a>
            <a href="#skill-tree">Skill tree</a>
            <a href="#projects">Projects</a>
        </div>
    </nav>

    <!-- ===== HERO: data diri dari variabel PHP ===== -->
    <header class="hero">
        <h1 class="hero__name"><?php echo e($nama); ?></h1>
        <p class="hero__role"><?php echo e($role); ?></p>
        <p class="hero__campus"><?php echo e($kampus); ?></p>
        <p class="hero__bio"><?php echo e($bio); ?></p>

        <div class="hero__meta">
            <?php
            // ===== IF/ELSE: badge Open to Work / Not Available =====
            if ($open_to_work) {
                echo '<span class="status status--open">Open to Work 🟢</span>';
            } else {
                echo '<span class="status status--closed">Not Available 🔴</span>';
            }
            ?>
            <ul class="socials">
                <li><a href="<?= e($github) ?>" target="_blank" rel="noopener">GitHub</a></li>
                <li><a href="<?= e($linkedin) ?>" target="_blank" rel="noopener">LinkedIn</a></li>
                <li><a href="mailto:<?= e($email) ?>"><?= e($email) ?></a></li>
                <li><?= e($lokasi) ?></li>
            </ul>
        </div>
    </header>

    <main>
        <!-- ===== SKILLS: array + foreach ===== -->
        <section class="section" id="skills">
            <h2 class="section__title">Skills</h2>
            <p class="section__lead"><?= count($skills) ?> skill, dirender dari array <code>$skills</code> pakai foreach.</p>
            <ul class="skills">
                <?php foreach ($skills as $skill): ?>
                    <li class="skill"><?= e($skill) ?></li>
                <?php endforeach; ?>
            </ul>
        </section>

        <!-- ===== SKILL TREE: fungsi rekursif ===== -->
        <section class="section" id="skill-tree">
            <h2 class="section__title">Skill tree</h2>
            <p class="section__lead"><?= countSkills($skill_tree) ?> node dari nested array, dirender oleh fungsi rekursif <code>renderSkillTree()</code>.</p>
            <div class="tree-columns">
                <?php foreach ($skill_tree as $root => $children): ?>
                    <div><?= renderSkillTree([$root => $children]) ?></div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- ===== PROJECTS: fungsi memanggil fungsi ===== -->
        <?= renderPortfolioSection('Projects', $projects) ?>
    </main>
</div>

<?= renderFooter() ?>

</body>
</html>
