<?php
// views/home.php
// $page_title sudah di-set di index.php
require_once __DIR__ . '/template/header.php';
?>

<h2><?php echo htmlspecialchars($page_title); ?>!</h2>
<p>Ini adalah aplikasi sederhana manajemen inventaris toko buku menggunakan pola MVVM dengan PHP native.</p>
<p>Silakan pilih menu di atas untuk mulai mengelola data.</p>

<?php
require_once __DIR__ . '/template/footer.php';
?>
