<?php
/**
 * READ: daftar pelanggan + pencarian (LIKE) + pagination.
 */
require __DIR__ . '/../config/database.php';
require __DIR__ . '/../includes/functions.php';

$perPage = 5;
$q       = trim((string) ($_GET['q'] ?? ''));
$page    = max(1, (int) ($_GET['page'] ?? 1));

// --- Pencarian (Tantangan 2) ---
// Karakter wildcard % dan _ di-escape agar diperlakukan sebagai teks biasa.
$where  = '';
$params = [];
if ($q !== '') {
    $like   = '%' . addcslashes($q, '%_\\') . '%';
    // Placeholder dibedakan (:q1, :q2) karena prepared statement asli
    // tidak mengizinkan nama placeholder yang sama dipakai dua kali.
    $where  = 'WHERE nama LIKE :q1 OR email LIKE :q2';
    $params = ['q1' => $like, 'q2' => $like];
}

// --- Hitung total data untuk pagination (Tantangan 3) ---
$stmt = $pdo->prepare("SELECT COUNT(*) FROM pelanggan $where");
$stmt->execute($params);
$total      = (int) $stmt->fetchColumn();
$totalPages = max(1, (int) ceil($total / $perPage));
$page       = min($page, $totalPages);
$offset     = ($page - 1) * $perPage;

// --- Ambil data halaman saat ini ---
$stmt = $pdo->prepare(
    "SELECT id, nama, email, telepon, alamat, tanggal_lahir
     FROM pelanggan $where
     ORDER BY id DESC
     LIMIT :limit OFFSET :offset"
);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value, PDO::PARAM_STR);
}
$stmt->bindValue('limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue('offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$pelanggan = $stmt->fetchAll();

/** Buat URL halaman dengan tetap membawa kata kunci pencarian. */
function page_url(int $p, string $q): string
{
    return 'index.php?' . http_build_query(array_filter(['q' => $q, 'page' => $p]));
}

$title = 'Daftar Pelanggan';
require __DIR__ . '/../includes/header.php';
?>

<div class="page-head">
    <h1>Daftar Pelanggan</h1>
    <a href="create.php" class="btn btn-primary">+ Tambah Pelanggan</a>
</div>

<form method="GET" action="index.php" class="search">
    <input type="search" name="q" value="<?= e($q) ?>" placeholder="Cari nama atau email…">
    <button type="submit" class="btn">Cari</button>
    <?php if ($q !== ''): ?>
        <a href="index.php" class="btn btn-ghost">Reset</a>
    <?php endif; ?>
</form>

<p class="meta">
    <?php if ($q !== ''): ?>
        Ditemukan <strong><?= $total ?></strong> data untuk “<?= e($q) ?>”.
    <?php else: ?>
        Total <strong><?= $total ?></strong> pelanggan.
    <?php endif; ?>
</p>

<?php if (!$pelanggan): ?>
    <div class="empty">Belum ada data<?= $q !== '' ? ' yang cocok dengan pencarian' : '' ?>.</div>
<?php else: ?>
<div class="table-wrap">
<table>
    <thead>
    <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Email</th>
        <th>Telepon</th>
        <th>Alamat</th>
        <th>Tgl Lahir</th>
        <th>Aksi</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($pelanggan as $i => $p): ?>
        <tr>
            <td><?= $offset + $i + 1 ?></td>
            <td><?= e($p['nama']) ?></td>
            <td><?= e($p['email']) ?></td>
            <td><?= e($p['telepon'] ?? '-') ?></td>
            <td><?= e($p['alamat'] ?? '-') ?></td>
            <td><?= $p['tanggal_lahir'] ? e(date('d-m-Y', strtotime($p['tanggal_lahir']))) : '-' ?></td>
            <td class="actions">
                <a href="edit.php?id=<?= (int) $p['id'] ?>" class="btn btn-small">Edit</a>
                <!-- Hapus memakai POST + CSRF token + konfirmasi, bukan link GET -->
                <form method="POST" action="delete.php"
                      onsubmit="return confirm('Hapus pelanggan <?= e(addslashes($p['nama'])) ?>?');">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= (int) $p['id'] ?>">
                    <button type="submit" class="btn btn-small btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>

<?php if ($totalPages > 1): ?>
<nav class="pagination">
    <?php if ($page > 1): ?>
        <a href="<?= e(page_url($page - 1, $q)) ?>">&laquo; Sebelumnya</a>
    <?php endif; ?>
    <?php for ($p = 1; $p <= $totalPages; $p++): ?>
        <?php if ($p === $page): ?>
            <span class="current"><?= $p ?></span>
        <?php else: ?>
            <a href="<?= e(page_url($p, $q)) ?>"><?= $p ?></a>
        <?php endif; ?>
    <?php endfor; ?>
    <?php if ($page < $totalPages): ?>
        <a href="<?= e(page_url($page + 1, $q)) ?>">Berikutnya &raquo;</a>
    <?php endif; ?>
</nav>
<?php endif; ?>
<?php endif; ?>

<?php require __DIR__ . '/../includes/footer.php'; ?>
