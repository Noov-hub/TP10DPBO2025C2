<?php
// config/Database.php

class Database {
    // Properti untuk koneksi database
    private $host = 'localhost';        
    private $db_name = 'toko_buku_mvvm_db'; 
    private $username = 'root';         
    private $password = '';             
    private $conn;

    // Metode untuk mendapatkan koneksi database
    public function getConnection() {
        $this->conn = null; // Set koneksi awal ke null

        try {
            // Membuat instance PDO baru
            $this->conn = new PDO(
                'mysql:host=' . $this->host . ';dbname=' . $this->db_name . ';charset=utf8mb4',
                $this->username,
                $this->password
            );
            // Mengatur mode error PDO ke exception untuk penanganan error yang lebih baik
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Mengatur fetch mode default ke associative array
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            // Menonaktifkan emulasi prepared statements untuk keamanan yang lebih baik dengan beberapa driver MySQL
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch(PDOException $exception) {
            // Menampilkan pesan error jika koneksi gagal
            
            echo 'Connection error: ' . $exception->getMessage();

            return null;
        }

        return $this->conn; // Mengembalikan objek koneksi
    }
}
?>
