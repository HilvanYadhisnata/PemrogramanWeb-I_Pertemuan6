<?php
/**
 * Field form pelanggan yang dipakai bersama oleh create.php dan edit.php.
 * @var array $data   nilai field
 * @var array $errors pesan error per field
 */
$field_error = function (string $name) use ($errors): string {
    return isset($errors[$name]) ? '<small class="error">' . e($errors[$name]) . '</small>' : '';
};
?>
<?= csrf_field() ?>

<div class="field">
    <label for="nama">Nama <span class="req">*</span></label>
    <input type="text" id="nama" name="nama" maxlength="100" required
           value="<?= e($data['nama']) ?>" class="<?= isset($errors['nama']) ? 'invalid' : '' ?>">
    <?= $field_error('nama') ?>
</div>

<div class="field">
    <label for="email">Email <span class="req">*</span></label>
    <input type="email" id="email" name="email" maxlength="150" required
           value="<?= e($data['email']) ?>" class="<?= isset($errors['email']) ? 'invalid' : '' ?>">
    <?= $field_error('email') ?>
</div>

<div class="field">
    <label for="telepon">Telepon</label>
    <input type="tel" id="telepon" name="telepon" maxlength="20"
           value="<?= e($data['telepon']) ?>" class="<?= isset($errors['telepon']) ? 'invalid' : '' ?>">
    <?= $field_error('telepon') ?>
</div>

<div class="field">
    <label for="alamat">Alamat</label>
    <textarea id="alamat" name="alamat" maxlength="255" rows="3"
              class="<?= isset($errors['alamat']) ? 'invalid' : '' ?>"><?= e($data['alamat']) ?></textarea>
    <?= $field_error('alamat') ?>
</div>

<div class="field">
    <label for="tanggal_lahir">Tanggal Lahir</label>
    <input type="date" id="tanggal_lahir" name="tanggal_lahir" max="<?= date('Y-m-d') ?>"
           value="<?= e($data['tanggal_lahir']) ?>" class="<?= isset($errors['tanggal_lahir']) ? 'invalid' : '' ?>">
    <?= $field_error('tanggal_lahir') ?>
</div>
