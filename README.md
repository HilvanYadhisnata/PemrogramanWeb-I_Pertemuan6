# Pemrograman Web I: Pertemuan 6 (PHP & PDO)

Tugas Pertemuan 6 mata kuliah Pemrograman Web I, Departemen Teknik Informatika, Fakultas Industri Kreatif, Universitas Teknologi Bandung.

- **Nama:** Hilvan Yadhisnata
- **Kelas:** TIF K 24A
- **Dosen Pengampu:** Dhany Indra Gunawan, S.T., M.Kom.

## Isi Repository

| Path | Keterangan |
|---|---|
| [`php-pdo-crud/`](php-pdo-crud/) | Aplikasi CRUD data pelanggan dengan PHP 8, PDO, dan MySQL |
| [`Dokumentasi Tugas PHP dan PDO.docx`](<Dokumentasi Tugas PHP dan PDO.docx>) | Dokumentasi tugas: perancangan, implementasi, pengujian, dan jawaban evaluasi pemahaman |

## Fitur Aplikasi

- CRUD data pelanggan (tambah, tampil, ubah, hapus) dengan PDO dan prepared statement
- Kolom tambahan alamat dan tanggal lahir
- Pencarian nama atau email dengan `LIKE`
- Pagination 5 data per halaman
- Flash message untuk pesan sukses atau gagal
- CSRF token pada semua form
- Validasi input di sisi server dan escaping output untuk mencegah XSS

## Cara Menjalankan

1. Jalankan MySQL dari XAMPP.
2. Impor `php-pdo-crud/database.sql` lewat phpMyAdmin atau terminal:
   ```
   C:\xampp\mysql\bin\mysql -u root < php-pdo-crud\database.sql
   ```
3. Jalankan server bawaan PHP:
   ```
   cd php-pdo-crud
   php -S localhost:8000 -t public
   ```
4. Buka `http://localhost:8000` di browser.

Penjelasan lengkap, termasuk cara menjalankan dengan Apache dan skenario pengujian, ada di [`php-pdo-crud/README.md`](php-pdo-crud/README.md).
