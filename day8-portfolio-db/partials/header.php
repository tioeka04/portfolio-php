<?php
/** @var string $pageTitle */
/** @var string $nama */
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Familjen+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="wrap">
    <nav class="nav">
        <a class="nav__brand" href="index.php"><?= e($nama) ?></a>
        <div class="nav__links">
            <a href="index.php#skills">Skills</a>
            <a href="index.php#projects">Projects</a>
            <a class="btn btn--primary" href="add_project.php">Tambah project</a>
        </div>
    </nav>
