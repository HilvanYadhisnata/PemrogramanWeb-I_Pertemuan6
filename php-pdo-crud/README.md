# PHP & PDO CRUD: Data Pelanggan

Tugas Pertemuan 6, Pemrograman Web I. Aplikasi CRUD (Create, Read, Update, Delete) data pelanggan dengan PHP 8+, PDO, dan MySQL.

## Fitur

### Praktik terarah (slide 23)

Koneksi database ada di `config/database.php` dan memakai PDO dengan mode exception. Emulasi prepare dimatikan, jadi query benar-benar memakai prepared statement asli dari MySQL.

- READ, daftar pelanggan: `public/index.php`
- CREATE, form tambah dan simpan: `public/create.php`
- UPDATE, form edit dan update: `public/edit.php`
- DELETE, hapus lewat POST dengan konfirmasi: `public/delete.php`

Semua input divalidasi ulang di server, termasuk isian kosong, format email, email duplikat, dan ID yang tidak ada.

### Latihan mandiri dan tantangan (slide 24)

1. Tabel punya kolom tambahan `alamat` dan `tanggal_lahir`.
2. Pencarian nama atau email memakai `LIKE` dan prepared statement. Karakter `%` dan `_` di kata kunci di-escape, jadi tidak berfungsi sebagai wildcard.
3. Pagination menampilkan 5 data per halaman, dan kata kunci pencarian tetap terbawa saat pindah halaman.
4. Pesan sukses atau gagal dikirim lewat session flash message.
5. Semua form (tambah, edit, hapus) memakai CSRF token.

### Keamanan lain

Semua data yang ditampilkan melewati `htmlspecialchars()` (fungsi `e()`), supaya input seperti `<script>` tidak dijalankan browser. Kalau query gagal, detail errornya hanya masuk ke log server dan pengguna cukup melihat pesan umum. Setelah simpan, edit, atau hapus, halaman di-redirect (pola Post/Redirect/Get), jadi menekan refresh tidak mengirim ulang form.

## Struktur Folder

```
php-pdo-crud/
├── config/
│   └── database.php      # koneksi PDO
├── includes/
│   ├── functions.php     # helper: escape, flash, CSRF, validasi
│   ├── header.php        # layout atas + flash message
│   ├── footer.php        # layout bawah
│   └── form.php          # field form (dipakai create & edit)
├── public/
│   ├── index.php         # daftar + pencarian + pagination
│   ├── create.php        # form + simpan
│   ├── edit.php          # form edit + update
│   ├── delete.php        # proses hapus
│   └── style.css
├── database.sql          # skema tabel + data contoh
└── README.md
```

## Cara Menjalankan (XAMPP)

1. Salin folder `php-pdo-crud` ke `C:\xampp\htdocs\`.
2. Jalankan **Apache** dan **MySQL** dari XAMPP Control Panel.
3. Buka `http://localhost/phpmyadmin`, masuk ke tab **SQL**, lalu jalankan isi file `database.sql`.
   Bisa juga lewat terminal: `C:\xampp\mysql\bin\mysql -u root < database.sql`
4. Buka `http://localhost/php-pdo-crud/public/`.

Cara lain tanpa Apache, menggunakan server bawaan PHP:

```
cd php-pdo-crud
php -S localhost:8000 -t public
```

Setelah itu buka `http://localhost:8000`.

Jika username atau password MySQL bukan `root` dengan password kosong, ubah nilainya di `config/database.php` atau atur environment variable `DB_HOST`, `DB_NAME`, `DB_USER`, dan `DB_PASS`.

## Skenario Pengujian (langkah 8)

| Skenario | Langkah | Hasil yang diharapkan |
|---|---|---|
| Input kosong | Kosongkan Nama/Email lalu klik Simpan | Muncul pesan "Nama wajib diisi" / "Email wajib diisi" |
| Email tidak valid | Isi email `abc@` | Muncul pesan "Format email tidak valid" |
| Email duplikat | Tambah data dengan email `ani@example.com` | Muncul pesan "Email sudah terdaftar" |
| ID tidak ada | Buka `edit.php?id=9999` | Kembali ke daftar dengan pesan "Data pelanggan tidak ditemukan" |
| Hapus via GET | Buka `delete.php` langsung di browser | Ditolak (405 Method Not Allowed) |
| CSRF | Kirim form tanpa token yang benar | Ditolak (400) |
| Pencarian | Cari `budi` atau `example.com` | Hanya data yang cocok yang tampil |
| SQL Injection | Cari `' OR 1=1 -- ` | Tidak ada data bocor, karena input diperlakukan sebagai teks |
