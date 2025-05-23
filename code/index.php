<?php
// index.php (Router Utama)

// Mulai session untuk flash messages
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Memasukkan file ViewModel yang dibutuhkan
require_once __DIR__ . '/viewmodels/AuthorViewModel.php';
require_once __DIR__ . '/viewmodels/PublisherViewModel.php';
require_once __DIR__ . '/viewmodels/BookViewModel.php';
// Model juga di-require di dalam ViewModel masing-masing

// Definisikan BASE_URL jika belum ada (berguna untuk path aset dan link)
if (!defined('BASE_URL')) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'];
    $script_dir = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
    define('BASE_URL', $protocol . $host . $script_dir);
}

// Fungsi untuk mengatur flash message
function set_flash_message(string $message, string $type = 'success') {
    $_SESSION['flash_message'] = ['text' => $message, 'type' => $type];
}

// Mendapatkan aksi dari URL, defaultnya 'home'
$action = $_GET['action'] ?? 'home';
// Mendapatkan ID jika ada (untuk edit/delete)
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

// Inisialisasi ViewModel
$authorViewModel = new AuthorViewModel();
$publisherViewModel = new PublisherViewModel();
$bookViewModel = new BookViewModel();

// Routing sederhana berdasarkan parameter 'action'
switch ($action) {
    // --- AUTHORS ---
    case 'authors':
        $page_title = "Manajemen Penulis";
        // Logika untuk menangani POST request (tambah/update penulis)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['add_author'])) {
                $author = new Author($_POST['name'], $_POST['country']);
                if ($authorViewModel->create($author)) {
                    set_flash_message("Penulis berhasil ditambahkan.");
                } else {
                    set_flash_message("Gagal menambahkan penulis.", "error");
                }
                header("Location: " . BASE_URL . "index.php?action=authors"); // Redirect untuk mencegah resubmit
                exit;
            } elseif (isset($_POST['update_author']) && isset($_POST['id'])) {
                $author = new Author($_POST['name'], $_POST['country'], (int)$_POST['id']);
                if ($authorViewModel->update($author)) {
                     set_flash_message("Penulis berhasil diperbarui.");
                } else {
                    set_flash_message("Gagal memperbarui penulis atau tidak ada perubahan.", "error");
                }
                header("Location: " . BASE_URL . "index.php?action=authors");
                exit;
            }
        }
        // Mendapatkan semua penulis untuk ditampilkan
        $authors = $authorViewModel->getAll();
        // Memuat view untuk daftar penulis dan form tambah
        require_once __DIR__ . '/views/authors/index.php';
        break;

    case 'edit_author':
        $page_title = "Edit Penulis";
        if ($id === null) {
            set_flash_message("ID Penulis tidak valid.", "error");
            header("Location: " . BASE_URL . "index.php?action=authors");
            exit;
        }
        $author_to_edit = $authorViewModel->getById($id);
        if (!$author_to_edit) {
            set_flash_message("Penulis tidak ditemukan.", "error");
            header("Location: " . BASE_URL . "index.php?action=authors");
            exit;
        }
        // Memuat view untuk form edit penulis
        require_once __DIR__ . '/views/authors/edit.php';
        break;

    case 'delete_author':
        if ($id === null) {
            set_flash_message("ID Penulis tidak valid untuk dihapus.", "error");
        } else {
            if ($authorViewModel->delete($id)) {
                set_flash_message("Penulis berhasil dihapus.");
            } else {
                set_flash_message("Gagal menghapus penulis. Mungkin penulis masih memiliki buku terkait atau ID tidak ditemukan.", "error");
            }
        }
        header("Location: " . BASE_URL . "index.php?action=authors");
        exit;
        break;

    // --- PUBLISHERS ---
    case 'publishers':
        $page_title = "Manajemen Penerbit";
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['add_publisher'])) {
                $publisher = new Publisher($_POST['name'], $_POST['city']);
                if ($publisherViewModel->create($publisher)) {
                    set_flash_message("Penerbit berhasil ditambahkan.");
                } else {
                    set_flash_message("Gagal menambahkan penerbit.", "error");
                }
                header("Location: " . BASE_URL . "index.php?action=publishers");
                exit;
            } elseif (isset($_POST['update_publisher']) && isset($_POST['id'])) {
                $publisher = new Publisher($_POST['name'], $_POST['city'], (int)$_POST['id']);
                if ($publisherViewModel->update($publisher)) {
                    set_flash_message("Penerbit berhasil diperbarui.");
                } else {
                    set_flash_message("Gagal memperbarui penerbit atau tidak ada perubahan.", "error");
                }
                header("Location: " . BASE_URL . "index.php?action=publishers");
                exit;
            }
        }
        $publishers = $publisherViewModel->getAll();
        require_once __DIR__ . '/views/publishers/index.php';
        break;

    case 'edit_publisher':
        $page_title = "Edit Penerbit";
        if ($id === null) { /* ... error handling ... */ header("Location: " . BASE_URL . "index.php?action=publishers"); exit;}
        $publisher_to_edit = $publisherViewModel->getById($id);
        if (!$publisher_to_edit) { /* ... error handling ... */ header("Location: " . BASE_URL . "index.php?action=publishers"); exit;}
        require_once __DIR__ . '/views/publishers/edit.php';
        break;

    case 'delete_publisher':
        if ($id === null) { /* ... error handling ... */ }
        else {
            if ($publisherViewModel->delete($id)) {
                set_flash_message("Penerbit berhasil dihapus.");
            } else {
                set_flash_message("Gagal menghapus penerbit. Mungkin penerbit masih memiliki buku terkait atau ID tidak ditemukan.", "error");
            }
        }
        header("Location: " . BASE_URL . "index.php?action=publishers");
        exit;
        break;

    // --- BOOKS ---
    case 'books':
        $page_title = "Manajemen Buku";
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['add_book'])) {
                $book = new Book(
                    $_POST['title'],
                    !empty($_POST['author_id']) ? (int)$_POST['author_id'] : null,
                    !empty($_POST['publisher_id']) ? (int)$_POST['publisher_id'] : null,
                    !empty($_POST['year_published']) ? (int)$_POST['year_published'] : null,
                    $_POST['isbn'],
                    (int)$_POST['stock']
                );
                if ($bookViewModel->create($book)) {
                    set_flash_message("Buku berhasil ditambahkan.");
                } else {
                    set_flash_message("Gagal menambahkan buku.", "error");
                }
                header("Location: " . BASE_URL . "index.php?action=books");
                exit;
            } elseif (isset($_POST['update_book']) && isset($_POST['id'])) {
                 $book = new Book(
                    $_POST['title'],
                    !empty($_POST['author_id']) ? (int)$_POST['author_id'] : null,
                    !empty($_POST['publisher_id']) ? (int)$_POST['publisher_id'] : null,
                    !empty($_POST['year_published']) ? (int)$_POST['year_published'] : null,
                    $_POST['isbn'],
                    (int)$_POST['stock'],
                    (int)$_POST['id']
                );
                if ($bookViewModel->update($book)) {
                    set_flash_message("Buku berhasil diperbarui.");
                } else {
                    set_flash_message("Gagal memperbarui buku atau tidak ada perubahan.", "error");
                }
                header("Location: " . BASE_URL . "index.php?action=books");
                exit;
            }
        }
        $books = $bookViewModel->getAllWithDetails();
        // Data untuk dropdown form tambah buku
        $authors_for_select = $authorViewModel->getAll(); // atau $bookViewModel->getAllAuthorsForSelect();
        $publishers_for_select = $publisherViewModel->getAll(); // atau $bookViewModel->getAllPublishersForSelect();
        require_once __DIR__ . '/views/books/index.php';
        break;

    case 'edit_book':
        $page_title = "Edit Buku";
        if ($id === null) { /* ... error handling ... */ header("Location: " . BASE_URL . "index.php?action=books"); exit;}
        $book_to_edit = $bookViewModel->getByIdWithDetails($id);
        if (!$book_to_edit) { /* ... error handling ... */ header("Location: " . BASE_URL . "index.php?action=books"); exit;}
        // Data untuk dropdown form edit buku
        $authors_for_select = $authorViewModel->getAll();
        $publishers_for_select = $publisherViewModel->getAll();
        require_once __DIR__ . '/views/books/edit.php';
        break;

    case 'delete_book':
        if ($id === null) { /* ... error handling ... */ }
        else {
            if ($bookViewModel->delete($id)) {
                set_flash_message("Buku berhasil dihapus.");
            } else {
                set_flash_message("Gagal menghapus buku atau ID tidak ditemukan.", "error");
            }
        }
        header("Location: " . BASE_URL . "index.php?action=books");
        exit;
        break;

    case 'home':
    default:
        $page_title = "Selamat Datang";
        // Memuat view untuk halaman utama
        require_once __DIR__ . '/views/home.php';
        break;
}

?>
