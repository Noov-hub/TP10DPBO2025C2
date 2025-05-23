<?php
// views/template/header.php

// Definisikan BASE_URL jika belum ada (berguna untuk path aset dan link)
// Ganti 'http://localhost/toko_buku_mvvm/' sesuai dengan URL root proyek Anda
if (!defined('BASE_URL')) {
    // Coba deteksi BASE_URL secara otomatis (mungkin perlu penyesuaian)
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'];
    // Hapus nama file skrip dari SCRIPT_NAME untuk mendapatkan direktori proyek
    $script_dir = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
    define('BASE_URL', $protocol . $host . $script_dir);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) : 'Toko Buku MVVM'; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    <style>
        /* CSS Dasar untuk tampilan yang lebih baik */
        body {
            font-family: sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }
        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
            padding: 0 20px;
        }
        header {
            background: #333;
            color: #fff;
            padding-top: 30px;
            min-height: 70px;
            border-bottom: #0779e4 3px solid;
        }
        header a {
            color: #fff;
            text-decoration: none;
            text-transform: uppercase;
            font-size: 16px;
        }
        header ul {
            padding: 0;
            list-style: none;
        }
        header li {
            float: left;
            display: inline;
            padding: 0 20px 0 20px;
        }
        header #branding {
            float: left;
        }
        header #branding h1 {
            margin: 0;
        }
        header nav {
            float: right;
            margin-top: 10px;
        }
        .main-content {
            padding: 20px;
            background: #fff;
            margin-top: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .action-links a {
            margin-right: 10px;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 3px;
        }
        .edit-link {
            background-color: #ffc107; /* Kuning */
            color: #333;
        }
        .delete-link {
            background-color: #dc3545; /* Merah */
            color: white;
        }
        .add-button {
            display: inline-block;
            background-color: #28a745; /* Hijau */
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box; /* Agar padding tidak menambah lebar total */
        }
        .form-group input[type="submit"] {
            background: #333;
            color: #fff;
            border: 0;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 5px;
        }
        .message {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div id="branding">
                <h1><a href="<?php echo BASE_URL; ?>">Toko Buku MVVM</a></h1>
            </div>
            <nav>
                <ul>
                    <li><a href="<?php echo BASE_URL; ?>index.php?action=home">Home</a></li>
                    <li><a href="<?php echo BASE_URL; ?>index.php?action=authors">Penulis</a></li>
                    <li><a href="<?php echo BASE_URL; ?>index.php?action=publishers">Penerbit</a></li>
                    <li><a href="<?php echo BASE_URL; ?>index.php?action=books">Buku</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <div class="container main-content">
        <?php
        // Menampilkan pesan flash jika ada
        if (session_status() == PHP_SESSION_NONE) { // Mulai session jika belum dimulai
            session_start();
        }
        if (isset($_SESSION['flash_message'])) {
            $message = $_SESSION['flash_message'];
            echo '<div class="message ' . htmlspecialchars($message['type']) . '">' . htmlspecialchars($message['text']) . '</div>';
            unset($_SESSION['flash_message']); // Hapus pesan setelah ditampilkan
        }
        ?>
