<?php
// views/books/edit.php
// $page_title, $book_to_edit, $authors_for_select, $publishers_for_select sudah di-set di index.php
require_once __DIR__ . '/../template/header.php';
?>

<h2><?php echo htmlspecialchars($page_title); ?></h2>

<?php if (isset($book_to_edit) && $book_to_edit instanceof Book): ?>
<form action="<?php echo BASE_URL; ?>index.php?action=books" method="POST">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($book_to_edit->getId()); ?>">
    <div class="form-group">
        <label for="title">Judul Buku:</label>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($book_to_edit->getTitle()); ?>" required>
    </div>
    <div class="form-group">
        <label for="author_id">Penulis:</label>
        <select id="author_id" name="author_id">
            <option value="">-- Pilih Penulis --</option>
            <?php if (!empty($authors_for_select)): ?>
                <?php foreach ($authors_for_select as $author_select_item): ?>
                    <option value="<?php echo htmlspecialchars($author_select_item->getId()); ?>"
                        <?php echo ($book_to_edit->getAuthorId() == $author_select_item->getId()) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($author_select_item->getName()); ?>
                    </option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
    </div>
    <div class="form-group">
        <label for="publisher_id">Penerbit:</label>
        <select id="publisher_id" name="publisher_id">
            <option value="">-- Pilih Penerbit --</option>
            <?php if (!empty($publishers_for_select)): ?>
                <?php foreach ($publishers_for_select as $publisher_select_item): ?>
                    <option value="<?php echo htmlspecialchars($publisher_select_item->getId()); ?>"
                        <?php echo ($book_to_edit->getPublisherId() == $publisher_select_item->getId()) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($publisher_select_item->getName()); ?>
                    </option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
    </div>
    <div class="form-group">
        <label for="year_published">Tahun Terbit:</label>
        <input type="number" id="year_published" name="year_published" min="1000" max="<?php echo date('Y'); ?>" value="<?php echo htmlspecialchars($book_to_edit->getYearPublished() ?? ''); ?>">
    </div>
    <div class="form-group">
        <label for="isbn">ISBN:</label>
        <input type="text" id="isbn" name="isbn" value="<?php echo htmlspecialchars($book_to_edit->getIsbn() ?? ''); ?>">
    </div>
    <div class="form-group">
        <label for="stock">Stok:</label>
        <input type="number" id="stock" name="stock" value="<?php echo htmlspecialchars($book_to_edit->getStock()); ?>" min="0" required>
    </div>
    <div class="form-group">
        <input type="submit" name="update_book" value="Update Buku">
    </div>
</form>
<?php else: ?>
    <p>Data buku tidak ditemukan atau tidak valid.</p>
<?php endif; ?>

<p><a href="<?php echo BASE_URL; ?>index.php?action=books">Kembali ke Daftar Buku</a></p>

<?php
require_once __DIR__ . '/../template/footer.php';
?>
