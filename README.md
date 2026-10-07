# Tugas Bootcamp Backend — Portfolio PHP

Dua folder terpisah, masing-masing bisa dijalankan sendiri. Website portfolio utama (HTML/JS + Supabase) tidak disentuh.

```
day7-portfolio-php/   → Praktikum Day 7 (variabel, array, kondisi, loop, fungsi, rekursi)
day8-portfolio-db/    → Praktikum Day 8 (MySQL, CRUD, upload, download)
```

## Cara menjalankan (XAMPP)

1. Copy kedua folder ke `C:\xampp\htdocs\` (Laragon: `C:\laragon\www\`).
2. Start **Apache** dan **MySQL** di XAMPP Control Panel.
3. Day 7: buka `http://localhost/day7-portfolio-php/`
   - Coba `?available=0` di URL untuk lihat badge "Not Available 🔴".
4. Day 8:
   - Buka `http://localhost/phpmyadmin` → tab **Import** → pilih `day8-portfolio-db/database.sql` → **Go**
     (ini membuat database `portfolio_db` sesuai soal).
   - Kalau MySQL kamu pakai password, ubah bagian `$local` di `config.php`.
   - Buka `http://localhost/day8-portfolio-db/`.
   - Folder `uploads/` sengaja tidak ada — dibuat otomatis oleh `mkdir()` saat upload pertama.

Data diri ada di `data.php` (nama lengkap, email, LinkedIn masih bertanda `TODO`).

## Day 7 — peta ke kriteria penilaian

| Kriteria | Di mana |
|---|---|
| File `.php`, jalan di server lokal | `index.php` |
| ≥ 5 variabel data diri | `data.php`: `$nama`, `$role`, `$kampus`, `$bio`, `$lokasi`, `$email`, `$github`, `$linkedin` |
| Skill dalam array | `$skills` di `data.php` |
| IF/ELSE memengaruhi tampilan | badge `$open_to_work` di `index.php` |
| `foreach` render skill & project | section Skills + `renderPortfolioSection()` |
| `renderProjectCard()` ≥ 3 parameter | `functions.php` (`$title, $description, $tech, $link, $category`) |
| Fungsi memanggil fungsi lain | `renderPortfolioSection()` → `renderProjectCard()` → `getCategoryInfo()` |
| Bonus SWITCH kategori | `getCategoryInfo()` |
| Bonus fungsi rekursif | `renderSkillTree()` + `countSkills()` |
| Variabel lokal / global / superglobal / static | lokal di tiap fungsi, `global` di `renderFooter()`, `$_GET` di `index.php`, `static $cardNumber` di `renderProjectCard()` |

## Day 8 — peta ke kriteria penilaian

| Kriteria | Di mana |
|---|---|
| Database `portfolio_db` + tabel `projects` | `database.sql` |
| Koneksi database | `connection.php` (PDO), setting di `config.php` |
| Tampil data dengan SELECT | `index.php` |
| Form input + INSERT INTO | `add_project.php` |
| Folder `uploads/` otomatis | `ensureUploadDir()` di `functions.php` (pakai `mkdir()`) |
| Upload file + path di DB | `storeUpload()` → kolom `file_path` |
| Validasi input & file (wajib isi, pdf/jpg/png, maks 2 MB) | `validateProjectInput()` + `validateUpload()` |
| Hapus data + file (`DELETE FROM` + `unlink()`) | `delete_project.php` |
| Link download | `download.php` (tombol "Download" di tiap card) |
| Bonus Edit/UPDATE | `edit_project.php` |
| Deploy ke free hosting | siap deploy, ikuti `DEPLOY-INFINITYFREE.md` |

Fitur keamanan tambahan: prepared statement (anti SQL injection), `htmlspecialchars` (anti XSS), token CSRF di form, hapus hanya lewat POST, nama file upload diacak, cek MIME asli file, dan `.htaccess` di `uploads/` yang memblokir eksekusi `.php`.

## Deploy

Ikuti `DEPLOY-INFINITYFREE.md`. Yang di-upload adalah `htdocs-infinityfree.zip`.
