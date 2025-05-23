<?php
// views/books/index.php
// $page_title, $books, $authors_for_select, $publishers_for_select sudah di-set di index.php
require_once __DIR__ . '/../template/header.php'; // Memuat header
?>

<h2><?php echo htmlspecialchars($page_title); ?></h2>

<h3>Tambah Buku Baru</h3>
<form action="<?php echo BASE_URL; ?>index.php?action=books" method="POST">
    <div class="form-group">
        <label for="title">Judul Buku:</label>
        <input type="text" id="title" name="title" required>
    </div>
    <div class="form-group">
        <label for="author_id">Penulis:</label>
        <select id="author_id" name="author_id">
            <option value="">-- Pilih Penulis --</option>
            <?php if (!empty($authors_for_select)): ?>
                <?php foreach ($authors_for_select as $author_select_item): ?>
                    <option value="<?php echo htmlspecialchars($author_select_item->getId()); ?>">
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
                    <option value="<?php echo htmlspecialchars($publisher_select_item->getId()); ?>">
                        <?php echo htmlspecialchars($publisher_select_item->getName()); ?>
                    </option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
    </div>
    <div class="form-group">
        <label for="year_published">Tahun Terbit:</label>
        <input type="number" id="year_published" name="year_published" min="1000" max="<?php echo date('Y'); ?>">
    </div>
    <div class="form-group">
        <label for="isbn">ISBN:</label>
        <input type="text" id="isbn" name="isbn">
    </div>
    <div class="form-group">
        <label for="stock">Stok:</label>
        <input type="number" id="stock" name="stock" value="0" min="0" required>
    </div>
    <div class="form-group">
        <input type="submit" name="add_book" value="Tambah Buku">
    </div>
</form>

<hr>

<h3>Daftar Buku</h3>
<?php if (!empty($books)): ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Judul</th>
                <th>Penulis</th>
                <th>Penerbit</th>
                <th>Tahun</th>
                <th>ISBN</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($books as $book_item): ?>
            <tr>
                <td><?php echo htmlspecialchars($book_item->getId()); ?></td>
                <td><?php echo htmlspecialchars($book_item->getTitle()); ?></td>
                <td><?php echo $book_item->author ? htmlspecialchars($book_item->author->getName()) : 'N/A'; ?></td>
                <td><?php echo $book_item->publisher ? htmlspecialchars($book_item->publisher->getName()) : 'N/A'; ?></td>
                <td><?php echo htmlspecialchars($book_item->getYearPublished() ?? '-'); ?></td>
                <td><?php echo htmlspecialchars($book_item->getIsbn() ?? '-'); ?></td>
                <td><?php echo htmlspecialchars($book_item->getStock()); ?></td>
                <td class="action-links">
                    <a href="<?php echo BASE_URL; ?>index.php?action=edit_book&id=<?php echo htmlspecialchars($book_item->getId()); ?>" class="edit-link">Edit</a>
                    <a href="<?php echo BASE_URL; ?>index.php?action=delete_book&id=<?php echo htmlspecialchars($book_item->getId()); ?>"
                       class="delete-link"
                       onclick="return confirm('Apakah Anda yakin ingin menghapus buku ini?');">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Belum ada data buku.</p>
<?php endif; ?>

<?php
require_once __DIR__ . '/../template/footer.php'; // Memuat footer
?>
