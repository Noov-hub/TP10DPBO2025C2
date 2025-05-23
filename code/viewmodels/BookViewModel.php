<?php
// viewmodels/BookViewModel.php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Book.php';
require_once __DIR__ . '/../models/Author.php';    // Diperlukan untuk data Author terkait
require_once __DIR__ . '/../models/Publisher.php'; // Diperlukan untuk data Publisher terkait

class BookViewModel {
    private PDO $conn;
    private string $table_name = "books";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        if ($this->conn === null) {
            die("Error: Tidak dapat terhubung ke database.");
        }
    }

    /**
     * Membuat buku baru.
     * @param Book $book Objek Book.
     * @return bool True jika berhasil.
     */
    public function create(Book $book): bool {
        $query = "INSERT INTO " . $this->table_name . 
                 " (title, author_id, publisher_id, year_published, isbn, stock) " .
                 " VALUES (:title, :author_id, :publisher_id, :year_published, :isbn, :stock)";
        
        $stmt = $this->conn->prepare($query);

        // Membersihkan data
        $book->title = htmlspecialchars(strip_tags($book->title));
        $book->isbn = $book->isbn ? htmlspecialchars(strip_tags($book->isbn)) : null;
        // Untuk foreign key dan angka, pastikan tipenya benar
        $book->author_id = $book->author_id ? (int)$book->author_id : null;
        $book->publisher_id = $book->publisher_id ? (int)$book->publisher_id : null;
        $book->year_published = $book->year_published ? (int)$book->year_published : null;
        $book->stock = (int)$book->stock;

        // Mengikat parameter
        $stmt->bindParam(':title', $book->title);
        $stmt->bindParam(':author_id', $book->author_id, $book->author_id === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
        $stmt->bindParam(':publisher_id', $book->publisher_id, $book->publisher_id === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
        $stmt->bindParam(':year_published', $book->year_published, $book->year_published === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
        $stmt->bindParam(':isbn', $book->isbn, $book->isbn === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->bindParam(':stock', $book->stock, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $book->id = (int)$this->conn->lastInsertId();
            return true;
        }
        // printf("Error create book: %s.\n", implode(", ",$stmt->errorInfo()));
        return false;
    }

    /**
     * Mengambil semua buku dengan informasi penulis dan penerbit.
     * @return array Array objek Book, masing-masing bisa berisi objek Author dan Publisher.
     */
    public function getAllWithDetails(): array {
        // Query dengan JOIN untuk mendapatkan nama penulis dan penerbit
        $query = "SELECT 
                    b.id, b.title, b.year_published, b.isbn, b.stock,
                    b.author_id, a.name as author_name, a.country as author_country,
                    b.publisher_id, p.name as publisher_name, p.city as publisher_city
                  FROM 
                    " . $this->table_name . " b
                  LEFT JOIN 
                    authors a ON b.author_id = a.id
                  LEFT JOIN 
                    publishers p ON b.publisher_id = p.id
                  ORDER BY 
                    b.title ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $books = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $book = new Book(
                $row['title'],
                $row['author_id'] ? (int)$row['author_id'] : null,
                $row['publisher_id'] ? (int)$row['publisher_id'] : null,
                $row['year_published'] ? (int)$row['year_published'] : null,
                $row['isbn'],
                (int)$row['stock'],
                (int)$row['id']
            );

            // Jika ada data penulis, buat objek Author dan set ke buku
            if ($row['author_id'] && $row['author_name']) {
                $author = new Author($row['author_name'], $row['author_country'], (int)$row['author_id']);
                $book->setAuthor($author);
            }

            // Jika ada data penerbit, buat objek Publisher dan set ke buku
            if ($row['publisher_id'] && $row['publisher_name']) {
                $publisher = new Publisher($row['publisher_name'], $row['publisher_city'], (int)$row['publisher_id']);
                $book->setPublisher($publisher);
            }
            $books[] = $book;
        }
        return $books;
    }

    /**
     * Mengambil satu buku berdasarkan ID dengan detail penulis dan penerbit.
     * @param int $id ID Buku.
     * @return Book|null Objek Book atau null.
     */
    public function getByIdWithDetails(int $id): ?Book {
        $query = "SELECT 
                    b.id, b.title, b.year_published, b.isbn, b.stock,
                    b.author_id, a.name as author_name, a.country as author_country,
                    b.publisher_id, p.name as publisher_name, p.city as publisher_city
                  FROM 
                    " . $this->table_name . " b
                  LEFT JOIN 
                    authors a ON b.author_id = a.id
                  LEFT JOIN 
                    publishers p ON b.publisher_id = p.id
                  WHERE 
                    b.id = :id
                  LIMIT 1";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $book = new Book(
                $row['title'],
                $row['author_id'] ? (int)$row['author_id'] : null,
                $row['publisher_id'] ? (int)$row['publisher_id'] : null,
                $row['year_published'] ? (int)$row['year_published'] : null,
                $row['isbn'],
                (int)$row['stock'],
                (int)$row['id']
            );

            if ($row['author_id'] && $row['author_name']) {
                $author = new Author($row['author_name'], $row['author_country'], (int)$row['author_id']);
                $book->setAuthor($author);
            }

            if ($row['publisher_id'] && $row['publisher_name']) {
                $publisher = new Publisher($row['publisher_name'], $row['publisher_city'], (int)$row['publisher_id']);
                $book->setPublisher($publisher);
            }
            return $book;
        }
        return null;
    }

    /**
     * Memperbarui data buku.
     * @param Book $book Objek Book yang akan diupdate.
     * @return bool True jika berhasil.
     */
    public function update(Book $book): bool {
        if ($book->id === null) {
            return false;
        }

        $query = "UPDATE " . $this->table_name . " SET " .
                 "title = :title, author_id = :author_id, publisher_id = :publisher_id, " .
                 "year_published = :year_published, isbn = :isbn, stock = :stock " .
                 "WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);

        $book->title = htmlspecialchars(strip_tags($book->title));
        $book->isbn = $book->isbn ? htmlspecialchars(strip_tags($book->isbn)) : null;
        $book->author_id = $book->author_id ? (int)$book->author_id : null;
        $book->publisher_id = $book->publisher_id ? (int)$book->publisher_id : null;
        $book->year_published = $book->year_published ? (int)$book->year_published : null;
        $book->stock = (int)$book->stock;

        $stmt->bindParam(':title', $book->title);
        $stmt->bindParam(':author_id', $book->author_id, $book->author_id === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
        $stmt->bindParam(':publisher_id', $book->publisher_id, $book->publisher_id === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
        $stmt->bindParam(':year_published', $book->year_published, $book->year_published === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
        $stmt->bindParam(':isbn', $book->isbn, $book->isbn === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $stmt->bindParam(':stock', $book->stock, PDO::PARAM_INT);
        $stmt->bindParam(':id', $book->id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return $stmt->rowCount() > 0;
        }
        // printf("Error update book: %s.\n", implode(", ",$stmt->errorInfo()));
        return false;
    }

    /**
     * Menghapus buku berdasarkan ID.
     * @param int $id ID Buku.
     * @return bool True jika berhasil.
     */
    public function delete(int $id): bool {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return $stmt->rowCount() > 0;
        }
        return false;
    }

    // Helper untuk mengambil semua penulis (untuk dropdown di form buku)
    public function getAllAuthorsForSelect(): array {
        $authorVM = new AuthorViewModel(); // Bisa juga di-inject dependency-nya
        return $authorVM->getAll();
    }

    // Helper untuk mengambil semua penerbit (untuk dropdown di form buku)
    public function getAllPublishersForSelect(): array {
        $publisherVM = new PublisherViewModel(); // Bisa juga di-inject dependency-nya
        return $publisherVM->getAll();
    }
}
?>
