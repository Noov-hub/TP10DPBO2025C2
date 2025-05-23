<?php
// views/publishers/edit.php
// $page_title dan $publisher_to_edit sudah di-set di index.php
require_once __DIR__ . '/../template/header.php';
?>

<h2><?php echo htmlspecialchars($page_title); ?></h2>

<?php if (isset($publisher_to_edit) && $publisher_to_edit instanceof Publisher): ?>
<form action="<?php echo BASE_URL; ?>index.php?action=publishers" method="POST">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($publisher_to_edit->getId()); ?>">
    <div class="form-group">
        <label for="name">Nama Penerbit:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($publisher_to_edit->getName()); ?>" required>
    </div>
    <div class="form-group">
        <label for="city">Kota:</label>
        <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($publisher_to_edit->getCity() ?? ''); ?>">
    </div>
    <div class="form-group">
        <input type="submit" name="update_publisher" value="Update Penerbit">
    </div>
</form>
<?php else: ?>
    <p>Data penerbit tidak ditemukan atau tidak valid.</p>
<?php endif; ?>

<p><a href="<?php echo BASE_URL; ?>index.php?action=publishers">Kembali ke Daftar Penerbit</a></p>

<?php
require_once __DIR__ . '/../template/footer.php';
?>
