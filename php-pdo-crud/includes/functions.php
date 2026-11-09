<?php
/**
 * Fungsi bantu: escaping output, flash message, CSRF token, redirect, dan validasi.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/** Escape teks sebelum ditampilkan ke HTML (mencegah XSS). */
function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/** Redirect lalu hentikan eksekusi. */
function redirect(string $url): never
{
    header('Location: ' . $url);
    exit;
}

/* ---------- Flash message (Tantangan 4) ---------- */

function set_flash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

/** Ambil flash message sekali saja, lalu hapus dari session. */
function get_flash(): ?array
{
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $flash;
}

/* ---------- CSRF token (Tantangan 5) ---------- */

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

/** Hentikan request jika token tidak cocok. */
function verify_csrf(): void
{
    $token = $_POST['csrf_token'] ?? '';
    if (!is_string($token) || !hash_equals(csrf_token(), $token)) {
        http_response_code(400);
        exit('Permintaan tidak valid (CSRF token salah atau kedaluwarsa). Muat ulang halaman lalu coba lagi.');
    }
}

/* ---------- Input & validasi ---------- */

/** Ambil semua field pelanggan dari $_POST dalam keadaan sudah di-trim. */
function input_pelanggan(): array
{
    $fields = ['nama', 'email', 'telepon', 'alamat', 'tanggal_lahir'];
    $data = [];
    foreach ($fields as $f) {
        $v = $_POST[$f] ?? '';
        $data[$f] = is_string($v) ? trim($v) : '';
    }
    return $data;
}

/**
 * Validasi data pelanggan di sisi server.
 * @return array<string,string> daftar error per field (kosong jika valid)
 */
function validasi_pelanggan(array $d): array
{
    $errors = [];

    if ($d['nama'] === '') {
        $errors['nama'] = 'Nama wajib diisi.';
    } elseif (mb_strlen($d['nama']) > 100) {
        $errors['nama'] = 'Nama maksimal 100 karakter.';
    }

    if ($d['email'] === '') {
        $errors['email'] = 'Email wajib diisi.';
    } elseif (!filter_var($d['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Format email tidak valid.';
    } elseif (mb_strlen($d['email']) > 150) {
        $errors['email'] = 'Email maksimal 150 karakter.';
    }

    if ($d['telepon'] !== '' && !preg_match('/^\+?[0-9\s\-]{6,20}$/', $d['telepon'])) {
        $errors['telepon'] = 'Telepon hanya boleh angka, spasi, tanda -, dan diawali + (6–20 karakter).';
    }

    if (mb_strlen($d['alamat']) > 255) {
        $errors['alamat'] = 'Alamat maksimal 255 karakter.';
    }

    if ($d['tanggal_lahir'] !== '') {
        $tgl = DateTime::createFromFormat('!Y-m-d', $d['tanggal_lahir']);
        if (!$tgl || $tgl->format('Y-m-d') !== $d['tanggal_lahir']) {
            $errors['tanggal_lahir'] = 'Tanggal lahir tidak valid.';
        } elseif ($tgl > new DateTime('today')) {
            $errors['tanggal_lahir'] = 'Tanggal lahir tidak boleh di masa depan.';
        }
    }

    return $errors;
}

/** Ubah string kosong menjadi NULL untuk kolom opsional. */
function nullable(string $value): ?string
{
    return $value === '' ? null : $value;
}

/** True jika exception berasal dari pelanggaran UNIQUE (MySQL error 1062). */
function is_duplicate_entry(PDOException $e): bool
{
    return ($e->errorInfo[1] ?? null) === 1062;
}
