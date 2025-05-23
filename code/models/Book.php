<?php
// models/Book.php

// Kita mungkin memerlukan model Author dan Publisher di sini jika ingin mengambil objek terkait
// require_once 'Author.php'; // Jika belum menggunakan autoloader
// require_once 'Publisher.php'; // Jika belum menggunakan autoloader

class Book {
    // Properti untuk tabel books
    public ?int $id = null;
    public string $title;
    public ?int $author_id = null; // Foreign key ke tabel authors
    public ?int $publisher_id = null; // Foreign key ke tabel publishers
    public ?int $year_published = null;
    public ?string $isbn = null;
    public int $stock = 0;

    // Properti tambahan untuk menampung objek Author dan Publisher terkait (opsional, untuk kemudahan)
    public ?Author $author = null;
    public ?Publisher $publisher = null;

    /**
     * Constructor untuk kelas Book.
     *
     * @param string $title Judul buku.
     * @param ?int $author_id ID penulis.
     * @param ?int $publisher_id ID penerbit.
     * @param ?int $year_published Tahun terbit.
     * @param ?string $isbn Nomor ISBN.
     * @param int $stock Jumlah stok.
     * @param ?int $id ID buku (opsional).
     */
    public function __construct(
        string $title = '',
        ?int $author_id = null,
        ?int $publisher_id = null,
        ?int $year_published = null,
        ?string $isbn = null,
        int $stock = 0,
        ?int $id = null
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->author_id = $author_id;
        $this->publisher_id = $publisher_id;
        $this->year_published = $year_published;
        $this->isbn = $isbn;
        $this->stock = $stock;
    }

    // Getter dan Setter (opsional)

    public function getId(): ?int {
        return $this->id;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function getAuthorId(): ?int {
        return $this->author_id;
    }

    public function getPublisherId(): ?int {
        return $this->publisher_id;
    }

    public function getYearPublished(): ?int {
        return $this->year_published;
    }

    public function getIsbn(): ?string {
        return $this->isbn;
    }

    public function getStock(): int {
        return $this->stock;
    }

    // Setter untuk objek terkait (jika Anda mengambil data dengan join dan ingin mengisinya)
    public function setAuthor(Author $author): void {
        $this->author = $author;
        if ($author->getId() !== null) { // Sinkronkan author_id jika objek Author punya ID
            $this->author_id = $author->getId();
        }
    }

    public function setPublisher(Publisher $publisher): void {
        $this->publisher = $publisher;
         if ($publisher->getId() !== null) { // Sinkronkan publisher_id jika objek Publisher punya ID
            $this->publisher_id = $publisher->getId();
        }
    }
}
?>
