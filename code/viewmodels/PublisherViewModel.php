<?php
// viewmodels/PublisherViewModel.php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Publisher.php';

class PublisherViewModel {
    private PDO $conn;
    private string $table_name = "publishers";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        if ($this->conn === null) {
            die("Error: Tidak dapat terhubung ke database.");
        }
    }

    /**
     * Membuat publisher baru.
     * @param Publisher $publisher Objek Publisher.
     * @return bool True jika berhasil.
     */
    public function create(Publisher $publisher): bool {
        $query = "INSERT INTO " . $this->table_name . " (name, city) VALUES (:name, :city)";
        $stmt = $this->conn->prepare($query);

        $publisher->name = htmlspecialchars(strip_tags($publisher->name));
        $publisher->city = $publisher->city ? htmlspecialchars(strip_tags($publisher->city)) : null;

        $stmt->bindParam(':name', $publisher->name);
        $stmt->bindParam(':city', $publisher->city, $publisher->city === null ? PDO::PARAM_NULL : PDO::PARAM_STR);

        if ($stmt->execute()) {
            $publisher->id = (int)$this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    /**
     * Mengambil semua publisher.
     * @return array Array objek Publisher.
     */
    public function getAll(): array {
        $query = "SELECT id, name, city FROM " . $this->table_name . " ORDER BY name ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $publishers = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $publishers[] = new Publisher($row['name'], $row['city'], (int)$row['id']);
        }
        return $publishers;
    }

    /**
     * Mengambil publisher berdasarkan ID.
     * @param int $id ID Publisher.
     * @return Publisher|null Objek Publisher atau null.
     */
    public function getById(int $id): ?Publisher {
        $query = "SELECT id, name, city FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Publisher($row['name'], $row['city'], (int)$row['id']);
        }
        return null;
    }

    /**
     * Memperbarui data publisher.
     * @param Publisher $publisher Objek Publisher yang akan diupdate.
     * @return bool True jika berhasil.
     */
    public function update(Publisher $publisher): bool {
        if ($publisher->id === null) {
            return false;
        }

        $query = "UPDATE " . $this->table_name . " SET name = :name, city = :city WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $publisher->name = htmlspecialchars(strip_tags($publisher->name));
        $publisher->city = $publisher->city ? htmlspecialchars(strip_tags($publisher->city)) : null;

        $stmt->bindParam(':name', $publisher->name);
        $stmt->bindParam(':city', $publisher->city, $publisher->city === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->bindParam(':id', $publisher->id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return $stmt->rowCount() > 0;
        }
        return false;
    }

    /**
     * Menghapus publisher berdasarkan ID.
     * @param int $id ID Publisher.
     * @return bool True jika berhasil.
     */
    public function delete(int $id): bool {
        // Sama seperti AuthorViewModel, pertimbangkan relasi dengan tabel books
        // jika ON DELETE SET NULL/CASCADE tidak diatur di database.

        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return $stmt->rowCount() > 0;
        }
        return false;
    }
}
?>
