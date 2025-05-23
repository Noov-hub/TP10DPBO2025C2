<?php
// models/Author.php

class Author {
    // Properti untuk tabel authors
    public ?int $id = null; // Nullable integer, bisa null
    public string $name;   // Nama penulis, wajib 
    public ?string $country = null; // Negara asal penulis

    /**
     * Constructor untuk kelas Author.
     *
     * @param string $name Nama penulis.
     * @param ?string $country Negara asal penulis
     * @param ?int $id ID penuli
     */
    public function __construct(string $name = '', ?string $country = null, ?int $id = null) {
        $this->id = $id;
        $this->name = $name;
        $this->country = $country;
    }



    public function getId(): ?int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getCountry(): ?string {
        return $this->country;
    }

    // Contoh setter (opsional)
    public function setId(?int $id): void {
        $this->id = $id;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function setCountry(?string $country): void {
        $this->country = $country;
    }
}
?>
