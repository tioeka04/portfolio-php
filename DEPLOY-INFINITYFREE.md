# Deploy Day 8 ke InfinityFree (± 15 menit)

Day 7 **tidak perlu** di-hosting (kriterianya cuma server lokal). Yang di-deploy hanya Day 8.
Kodenya sudah siap: kamu cuma perlu **isi 4 baris di `config.php`** dan **buka `setup.php` sekali**.
Nggak perlu import SQL manual.

## 1. Bikin akun & hosting
1. Daftar di https://www.infinityfree.com lalu verifikasi email.
2. Klik **Create Account**, pilih subdomain gratis (contoh: `tio-portfolio.infinityfreeapp.com`), dan selesaikan.
3. Tunggu sampai status akun **Active**.

## 2. Bikin database
1. Buka akun hosting kamu, lalu **Control Panel**, lalu **MySQL Databases**.
2. Isi nama database, misalnya `portfolio`, lalu klik **Create Database**.
3. Catat 4 data ini:
   - **MySQL Hostname**, misalnya `sql305.infinityfree.com`
   - **MySQL DB Name**, misalnya `if0_37123456_portfolio`
   - **MySQL Username**, misalnya `if0_37123456`
   - **Password**: password akun hosting, ada di client area bagian *Account Details*, tombol **Show**

## 3. Upload file
1. Dari client area, buka **File Manager**, lalu masuk ke folder `htdocs`.
2. Hapus file bawaan yang ada di `htdocs` (misalnya `index2.html`).
3. Upload `htdocs-infinityfree.zip`, klik kanan, lalu **Extract** di dalam `htdocs`.
   Kalau opsi Extract nggak ada, extract zip-nya di laptop lalu upload semua isinya, atau pakai FileZilla (data FTP ada di client area).
4. Pastikan `index.php` ada langsung di dalam `htdocs/`, jangan di dalam subfolder.

## 4. Isi config.php
Di File Manager, edit `htdocs/config.php` dan ganti 4 baris bagian `$hosting` dengan data dari langkah 2. Lalu save.

## 5. Jalankan setup sekali
Buka `https://subdomain-kamu.infinityfreeapp.com/setup.php`.
Kalau muncul "Database siap", klik **Buka portfolio**. Selesai.

## 6. Tes & bukti untuk penilaian
- Tambah project + upload PDF/JPG, lalu klik Download, Edit, dan Hapus.
- Screenshot halamannya dengan URL publik kelihatan di address bar.
- Kumpulkan URL-nya.

## Kalau error
| Gejala | Solusi |
|---|---|
| "Gagal terhubung ke database" | Cek lagi 4 isian di `config.php`. Hostname **bukan** `localhost`. |
| Halaman masih default InfinityFree | Subdomain baru butuh waktu aktif, bisa beberapa menit sampai beberapa jam. |
| "Token form tidak valid" | Refresh halaman form, lalu submit lagi. |
| Upload gagal disimpan | Bikin folder `uploads` manual di `htdocs` lewat File Manager. |
