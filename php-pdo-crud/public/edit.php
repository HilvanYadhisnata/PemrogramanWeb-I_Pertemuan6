<?php
/**
 * UPDATE: form edit pelanggan + proses UPDATE.
 */
require __DIR__ . '/../config/database.php';
require __DIR__ . '/../includes/functions.php';

$id = (int) ($_POST['id'] ?? $_GET['id'] ?? 0);

// Pastikan ID valid dan datanya ada
$stmt = $pdo->prepare(
    'SELECT id, nama, email, telepon, alamat, tanggal_lahir FROM pelanggan WHERE id = :id'
);
$stmt->execute(['id' => $id]);
$pelanggan = $stmt->fetch();

if (!$pelanggan) {
    set_flash('error', 'Data pelanggan tidak ditemukan.');
    redirect('index.php');
}

// Isi awal form dari database (NULL diubah menjadi string kosong)
$data   = array_map(fn($v) => (string) $v, $pelanggan);
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $data   = input_pelanggan();
    $errors = validasi_pelanggan($data);

    if (!$errors) {
        try {
            $stmt = $pdo->prepare(
                'UPDATE pelanggan
                 SET nama = :nama, email = :email, telepon = :telepon,
                     alamat = :alamat, tanggal_lahir = :tanggal_lahir
                 WHERE id = :id'
            );
            $stmt->execute([
                'nama'          => $data['nama'],
                'email'         => $data['email'],
                'telepon'       => nullable($data['telepon']),
                'alamat'        => nullable($data['alamat']),
                'tanggal_lahir' => nullable($data['tanggal_lahir']),
                'id'            => $id,
            ]);
            set_flash('success', 'Data pelanggan "' . $data['nama'] . '" berhasil diperbarui.');
            redirect('index.php');
        } catch (PDOException $e) {
            if (is_duplicate_entry($e)) {
                $errors['email'] = 'Email sudah dipakai pelanggan lain.';
            } else {
                error_log($e->getMessage());
                set_flash('error', 'Terjadi kesalahan saat memperbarui data.');
                redirect('index.php');
            }
        }
    }
}

$title = 'Edit Pelanggan';
require __DIR__ . '/../includes/header.php';
?>

<div class="page-head">
    <h1>Edit Pelanggan</h1>
</div>

<?php if ($errors): ?>
    <div class="alert alert-error">Periksa kembali isian form di bawah.</div>
<?php endif; ?>

<form method="POST" action="edit.php" class="card form" novalidate>
    <input type="hidden" name="id" value="<?= $id ?>">
    <?php require __DIR__ . '/../includes/form.php'; ?>
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Perbarui</button>
        <a href="index.php" class="btn btn-ghost">Batal</a>
    </div>
</form>

<?php require __DIR__ . '/../includes/footer.php'; ?>
