<?php
// views/authors/index.php
// $page_title sudah di-set di index.php
require_once __DIR__ . '/../template/header.php'; // Memuat header
?>

<h2><?php echo htmlspecialchars($page_title); ?></h2>

<h3>Tambah Penulis Baru</h3>
<form action="<?php echo BASE_URL; ?>index.php?action=authors" method="POST">
    <div class="form-group">
        <label for="name">Nama Penulis:</label>
        <input type="text" id="name" name="name" required>
    </div>
    <div class="form-group">
        <label for="country">Negara:</label>
        <input type="text" id="country" name="country">
    </div>
    <div class="form-group">
        <input type="submit" name="add_author" value="Tambah Penulis">
    </div>
</form>

<hr>

<h3>Daftar Penulis</h3>
<?php if (!empty($authors)): ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Negara</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($authors as $author_item): ?>
            <tr>
                <td><?php echo htmlspecialchars($author_item->getId()); ?></td>
                <td><?php echo htmlspecialchars($author_item->getName()); ?></td>
                <td><?php echo htmlspecialchars($author_item->getCountry() ?? '-'); ?></td>
                <td class="action-links">
                    <a href="<?php echo BASE_URL; ?>index.php?action=edit_author&id=<?php echo htmlspecialchars($author_item->getId()); ?>" class="edit-link">Edit</a>
                    <a href="<?php echo BASE_URL; ?>index.php?action=delete_author&id=<?php echo htmlspecialchars($author_item->getId()); ?>" 
                       class="delete-link" 
                       onclick="return confirm('Apakah Anda yakin ingin menghapus penulis ini?');">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Belum ada data penulis.</p>
<?php endif; ?>

<?php
require_once __DIR__ . '/../template/footer.php'; // Memuat footer
?>
