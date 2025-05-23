<?php
// viewmodels/AuthorViewModel.php

// Memasukkan file konfigurasi database dan model Author
// Dalam aplikasi yang lebih besar, Anda mungkin menggunakan autoloader
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Author.php';

class AuthorViewModel {
    private PDO $conn; // Properti untuk menyimpan objek koneksi PDO
    private string $table_name = "authors"; // Nama tabel di database

    // Constructor akan membuat instance Database dan mendapatkan koneksinya
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        if ($this->conn === null) {
            // Penanganan jika koneksi gagal (misalnya, log error atau throw exception)
            // Untuk kesederhanaan, kita bisa die() di sini, tapi idealnya ada penanganan yang lebih baik
            die("Error: Tidak dapat terhubung ke database.");
        }
    }

    /**
     * Membuat author baru di database.
     * @param Author $author Objek Author yang akan disimpan.
     * @return bool True jika berhasil, false jika gagal.
     */
    public function create(Author $author): bool {
        // Query untuk memasukkan record baru
        $query = "INSERT INTO " . $this->table_name . " (name, country) VALUES (:name, :country)";

        // Mempersiapkan statement query
        $stmt = $this->conn->prepare($query);

        // Membersihkan data (meskipun PDO prepared statements menangani escaping,
        // htmlspecialchars bisa berguna untuk mencegah XSS jika data ini akan langsung ditampilkan kembali tanpa escaping di view)
        $author->name = htmlspecialchars(strip_tags($author->name));
        $author->country = $author->country ? htmlspecialchars(strip_tags($author->country)) : null;

        // Mengikat parameter ke statement
        $stmt->bindParam(':name', $author->name);
        $stmt->bindParam(':country', $author->country, $author->country === null ? PDO::PARAM_NULL : PDO::PARAM_STR);

        // Menjalankan query
        if ($stmt->execute()) {
            // Jika berhasil, set ID untuk objek author yang baru dibuat
            $author->id = (int)$this->conn->lastInsertId();
            return true;
        }
        // Cetak error jika eksekusi gagal (untuk debugging)
        // printf("Error: %s.\n", $stmt->errorInfo()[2]); 
        return false;
    }

    /**
     * Membaca semua data author dari database.
     * @return array Array objek Author atau array kosong jika tidak ada data.
     */
    public function getAll(): array {
        $query = "SELECT id, name, country FROM " . $this->table_name . " ORDER BY name ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $authors = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Membuat objek Author untuk setiap baris hasil query
            $authors[] = new Author($row['name'], $row['country'], (int)$row['id']);
        }
        return $authors;
    }

    /**
     * Mendapatkan satu author berdasarkan ID.
     * @param int $id ID dari author yang dicari.
     * @return Author|null Objek Author jika ditemukan, null jika tidak.
     */
    public function getById(int $id): ?Author {
        $query = "SELECT id, name, country FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            // Jika data ditemukan, buat dan kembalikan objek Author
            return new Author($row['name'], $row['country'], (int)$row['id']);
        }
        return null; // Kembalikan null jika tidak ada data yang cocok
    }

    /**
     * Memperbarui data author yang ada di database.
     * @param Author $author Objek Author dengan data yang sudah diperbarui.
     * @return bool True jika berhasil, false jika gagal.
     */
    public function update(Author $author): bool {
        // Pastikan ID author tidak null untuk operasi update
        if ($author->id === null) {
            return false; // Tidak bisa update tanpa ID
        }

        $query = "UPDATE " . $this->table_name . " SET name = :name, country = :country WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        // Membersihkan data
        $author->name = htmlspecialchars(strip_tags($author->name));
        $author->country = $author->country ? htmlspecialchars(strip_tags($author->country)) : null;

        // Mengikat parameter
        $stmt->bindParam(':name', $author->name);
        $stmt->bindParam(':country', $author->country, $author->country === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->bindParam(':id', $author->id, PDO::PARAM_INT);

        // Menjalankan query
        if ($stmt->execute()) {
            // Memeriksa apakah ada baris yang terpengaruh untuk memastikan update berhasil
            return $stmt->rowCount() > 0;
        }
        // printf("Error: %s.\n", $stmt->errorInfo()[2]);
        return false;
    }

    /**
     * Menghapus author dari database berdasarkan ID.
     * @param int $id ID dari author yang akan dihapus.
     * @return bool True jika berhasil, false jika gagal.
     */
    public function delete(int $id): bool {
        // Pertama, periksa apakah author ini digunakan di tabel books
        // Ini adalah contoh sederhana, idealnya penanganan relasi lebih kompleks
        // atau menggunakan ON DELETE SET NULL/CASCADE di level database.
        $checkQuery = "SELECT COUNT(*) as count FROM books WHERE author_id = :author_id";
        $checkStmt = $this->conn->prepare($checkQuery);
        $checkStmt->bindParam(':author_id', $id, PDO::PARAM_INT);
        $checkStmt->execute();
        $result = $checkStmt->fetch(PDO::FETCH_ASSOC);

        if ($result && $result['count'] > 0) {
            // Jika author masih memiliki buku terkait, Anda bisa memilih untuk tidak menghapus
            // atau menampilkan pesan error. Untuk contoh ini, kita akan batalkan penghapusan.
            // Atau, jika ON DELETE SET NULL sudah diatur di DB, ini tidak perlu.
            // echo "Tidak bisa menghapus author karena masih memiliki buku terkait.";
            // return false; 
            // Jika menggunakan ON DELETE SET NULL/CASCADE, baris di atas bisa diabaikan.
        }


        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        // Mengikat parameter ID
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Menjalankan query
        if ($stmt->execute()) {
            // Memeriksa apakah ada baris yang terpengaruh untuk memastikan delete berhasil
            return $stmt->rowCount() > 0;
        }
        // printf("Error: %s.\n", $stmt->errorInfo()[2]);
        return false;
    }

    // Anda bisa menambahkan metode lain di sini, misalnya untuk validasi data,
    // atau untuk mengambil data yang sudah diformat khusus untuk View.
}
?>
