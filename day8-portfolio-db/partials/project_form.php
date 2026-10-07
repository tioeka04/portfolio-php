<?php
/**
 * Form project — dipakai add_project.php & edit_project.php.
 * @var array  $form       nilai field (title, description, tech, category)
 * @var array  $errors     error per field (key 'file' untuk upload)
 * @var bool   $isEdit
 * @var string|null $currentFile path file lama (khusus edit)
 */
$invalid = function ($field) use ($errors) {
    return isset($errors[$field]) ? ' field--invalid' : '';
};
$errorText = function ($field) use ($errors) {
    return isset($errors[$field])
        ? '<small id="err-' . $field . '" style="color:var(--bad)">' . e($errors[$field]) . '</small>'
        : '';
};
?>

<?php if (!empty($errors)): ?>
    <div class="alert alert--error" role="alert">
        <strong>Data belum tersimpan. Perbaiki dulu:</strong>
        <ul>
            <?php foreach ($errors as $msg): ?>
                <li><?= e($msg) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form class="form" method="post" enctype="multipart/form-data" novalidate>
    <?= csrfField() ?>
    <input type="hidden" name="MAX_FILE_SIZE" value="<?= MAX_FILE_SIZE ?>">

    <div class="field<?= $invalid('title') ?>">
        <label for="title">Judul project</label>
        <input type="text" id="title" name="title" maxlength="150" required
               value="<?= e($form['title']) ?>">
        <?= $errorText('title') ?>
    </div>

    <div class="field<?= $invalid('description') ?>">
        <label for="description">Deskripsi</label>
        <textarea id="description" name="description" required><?= e($form['description']) ?></textarea>
        <?= $errorText('description') ?>
    </div>

    <div class="field<?= $invalid('tech') ?>">
        <label for="tech">Teknologi</label>
        <input type="text" id="tech" name="tech" maxlength="255" required
               placeholder="PHP, MySQL, Bootstrap" value="<?= e($form['tech']) ?>">
        <small>Pisahkan dengan koma.</small>
        <?= $errorText('tech') ?>
    </div>

    <div class="field<?= $invalid('category') ?>">
        <label for="category">Kategori</label>
        <select id="category" name="category" required>
            <option value="">Pilih kategori</option>
            <?php foreach (CATEGORIES as $value => $label): ?>
                <option value="<?= e($value) ?>" <?= $form['category'] === $value ? 'selected' : '' ?>>
                    <?= e($label) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?= $errorText('category') ?>
    </div>

    <div class="field<?= $invalid('file') ?>">
        <label for="file">File pendukung<?= $isEdit ? ' (opsional)' : '' ?></label>
        <input type="file" id="file" name="file" accept=".pdf,.jpg,.jpeg,.png" <?= $isEdit ? '' : 'required' ?>>
        <small>
            PDF, JPG, atau PNG, maksimal 2 MB.
            <?php if ($isEdit && !empty($currentFile)): ?>
                File sekarang: <a href="download.php?id=<?= (int) $project['id'] ?>"><?= e(basename($currentFile)) ?></a>.
                Upload file baru untuk menggantinya.
            <?php endif; ?>
        </small>
        <?= $errorText('file') ?>
    </div>

    <div class="form__actions">
        <button type="submit" class="btn btn--primary"><?= $isEdit ? 'Simpan perubahan' : 'Simpan project' ?></button>
        <a class="btn" href="index.php#projects">Batal</a>
    </div>
</form>
