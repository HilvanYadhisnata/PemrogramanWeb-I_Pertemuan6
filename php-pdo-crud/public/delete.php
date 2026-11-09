<?php
/**
 * DELETE: proses hapus pelanggan. Hanya menerima POST + CSRF token.
 */
require __DIR__ . '/../config/database.php';
require __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    exit('Method tidak diizinkan. Penghapusan hanya melalui tombol Hapus.');
}

verify_csrf();

$id = (int) ($_POST['id'] ?? 0);

$stmt = $pdo->prepare('DELETE FROM pelanggan WHERE id = :id');
$stmt->execute(['id' => $id]);

if ($stmt->rowCount() > 0) {
    set_flash('success', 'Data pelanggan berhasil dihapus.');
} else {
    set_flash('error', 'Data pelanggan tidak ditemukan.');
}

redirect('index.php');
