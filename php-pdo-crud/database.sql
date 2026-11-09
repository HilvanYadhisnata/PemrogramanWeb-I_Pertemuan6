-- Database praktik: PHP & PDO CRUD Data Pelanggan
-- Jalankan melalui phpMyAdmin (tab SQL), MySQL Workbench, atau terminal:
--   mysql -u root < database.sql

CREATE DATABASE IF NOT EXISTS db_latihan
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_latihan;

DROP TABLE IF EXISTS pelanggan;

CREATE TABLE pelanggan (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  nama          VARCHAR(100) NOT NULL,
  email         VARCHAR(150) NOT NULL UNIQUE,
  telepon       VARCHAR(20),
  alamat        VARCHAR(255),           -- Tantangan 1: kolom tambahan
  tanggal_lahir DATE,                   -- Tantangan 1: kolom tambahan
  created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Data contoh agar fitur pencarian & pagination bisa langsung dicoba
INSERT INTO pelanggan (nama, email, telepon, alamat, tanggal_lahir) VALUES
('Ani Lestari',     'ani@example.com',     '081234567801', 'Jl. Merdeka No. 1, Bandung',   '2002-03-14'),
('Budi Santoso',    'budi@example.com',    '081234567802', 'Jl. Sudirman No. 5, Jakarta',  '2001-07-21'),
('Citra Dewi',      'citra@example.com',   '081234567803', 'Jl. Diponegoro No. 9, Bogor',  '2003-01-05'),
('Dimas Pratama',   'dimas@example.com',   '081234567804', 'Jl. Ahmad Yani No. 12, Bekasi','2000-11-30'),
('Eka Putri',       'eka@example.com',     '081234567805', 'Jl. Gatot Subroto No. 3, Depok','2002-09-17'),
('Fajar Nugroho',   'fajar@example.com',   '081234567806', 'Jl. Pemuda No. 8, Semarang',   '2001-04-02'),
('Gita Maharani',   'gita@example.com',    '081234567807', 'Jl. Pahlawan No. 4, Surabaya', '2003-06-25'),
('Hendra Wijaya',   'hendra@example.com',  '081234567808', 'Jl. Veteran No. 7, Malang',    '1999-12-12'),
('Indah Sari',      'indah@example.com',   '081234567809', 'Jl. Kartini No. 2, Solo',      '2002-02-28'),
('Joko Susilo',     'joko@example.com',    '081234567810', 'Jl. Gajah Mada No. 15, Medan', '2000-08-08'),
('Kartika Ayu',     'kartika@example.com', '081234567811', 'Jl. Asia Afrika No. 6, Bandung','2001-10-19'),
('Lukman Hakim',    'lukman@example.com',  '081234567812', 'Jl. Imam Bonjol No. 11, Padang','2003-05-09');
