<?php
/**
 * CREATE: form tambah pelanggan + proses INSERT.
 */
require __DIR__ . '/../config/database.php';
require __DIR__ . '/../includes/functions.php';

$data   = ['nama' => '', 'email' => '', 'telepon' => '', 'alamat' => '', 'tanggal_lahir' => ''];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $data   = input_pelanggan();
    $errors = validasi_pelanggan($data);

    if (!$errors) {
        try {
            $stmt = $pdo->prepare(
                'INSERT INTO pelanggan (nama, email, telepon, alamat, tanggal_lahir)
                 VALUES (:nama, :email, :telepon, :alamat, :tanggal_lahir)'
            );
            $stmt->execute([
                'nama'          => $data['nama'],
                'email'         => $data['email'],
                'telepon'       => nullable($data['telepon']),
                'alamat'        => nullable($data['alamat']),
                'tanggal_lahir' => nullable($data['tanggal_lahir']),
            ]);
            set_flash('success', 'Data pelanggan "' . $data['nama'] . '" berhasil ditambahkan.');
            redirect('index.php'); // Post/Redirect/Get: mencegah submit ulang saat refresh
        } catch (PDOException $e) {
            if (is_duplicate_entry($e)) {
                $errors['email'] = 'Email sudah terdaftar, gunakan email lain.';
            } else {
                error_log($e->getMessage());
                set_flash('error', 'Terjadi kesalahan saat menyimpan data.');
                redirect('index.php');
            }
        }
    }
}

$title = 'Tambah Pelanggan';
require __DIR__ . '/../includes/header.php';
?>

<div class="page-head">
    <h1>Tambah Pelanggan</h1>
</div>

<?php if ($errors): ?>
    <div class="alert alert-error">Periksa kembali isian form di bawah.</div>
<?php endif; ?>

<form method="POST" action="create.php" class="card form" novalidate>
    <?php require __DIR__ . '/../includes/form.php'; ?>
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="index.php" class="btn btn-ghost">Batal</a>
    </div>
</form>

<?php require __DIR__ . '/../includes/footer.php'; ?>
