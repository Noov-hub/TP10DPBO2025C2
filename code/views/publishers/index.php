<?php
// views/publishers/index.php
// $page_title dan $publishers sudah di-set di index.php
require_once __DIR__ . '/../template/header.php'; // Memuat header
?>

<h2><?php echo htmlspecialchars($page_title); ?></h2>

<h3>Tambah Penerbit Baru</h3>
<form action="<?php echo BASE_URL; ?>index.php?action=publishers" method="POST">
    <div class="form-group">
        <label for="name">Nama Penerbit:</label>
        <input type="text" id="name" name="name" required>
    </div>
    <div class="form-group">
        <label for="city">Kota:</label>
        <input type="text" id="city" name="city">
    </div>
    <div class="form-group">
        <input type="submit" name="add_publisher" value="Tambah Penerbit">
    </div>
</form>

<hr>

<h3>Daftar Penerbit</h3>
<?php if (!empty($publishers)): ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Kota</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($publishers as $publisher_item): ?>
            <tr>
                <td><?php echo htmlspecialchars($publisher_item->getId()); ?></td>
                <td><?php echo htmlspecialchars($publisher_item->getName()); ?></td>
                <td><?php echo htmlspecialchars($publisher_item->getCity() ?? '-'); ?></td>
                <td class="action-links">
                    <a href="<?php echo BASE_URL; ?>index.php?action=edit_publisher&id=<?php echo htmlspecialchars($publisher_item->getId()); ?>" class="edit-link">Edit</a>
                    <a href="<?php echo BASE_URL; ?>index.php?action=delete_publisher&id=<?php echo htmlspecialchars($publisher_item->getId()); ?>"
                       class="delete-link"
                       onclick="return confirm('Apakah Anda yakin ingin menghapus penerbit ini?');">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Belum ada data penerbit.</p>
<?php endif; ?>

<?php
require_once __DIR__ . '/../template/footer.php'; // Memuat footer
?>
