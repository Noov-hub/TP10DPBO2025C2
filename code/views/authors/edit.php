<?php
// views/authors/edit.php
// $page_title dan $author_to_edit sudah di-set di index.php
require_once __DIR__ . '/../template/header.php';
?>

<h2><?php echo htmlspecialchars($page_title); ?></h2>

<?php if (isset($author_to_edit) && $author_to_edit instanceof Author): ?>
<form action="<?php echo BASE_URL; ?>index.php?action=authors" method="POST">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($author_to_edit->getId()); ?>">
    <div class="form-group">
        <label for="name">Nama Penulis:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($author_to_edit->getName()); ?>" required>
    </div>
    <div class="form-group">
        <label for="country">Negara:</label>
        <input type="text" id="country" name="country" value="<?php echo htmlspecialchars($author_to_edit->getCountry() ?? ''); ?>">
    </div>
    <div class="form-group">
        <input type="submit" name="update_author" value="Update Penulis">
    </div>
</form>
<?php else: ?>
    <p>Data penulis tidak ditemukan atau tidak valid.</p>
<?php endif; ?>

<p><a href="<?php echo BASE_URL; ?>index.php?action=authors">Kembali ke Daftar Penulis</a></p>

<?php
require_once __DIR__ . '/../template/footer.php';
?>
