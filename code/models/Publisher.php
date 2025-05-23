<?php
// models/Publisher.php

class Publisher {
    // Properti untuk tabel publishers
    public ?int $id = null;
    public string $name;
    public ?string $city = null;

    /**
     * Constructor untuk kelas Publisher.
     *
     * @param string $name Nama penerbit.
     * @param ?string $city Kota penerbit (opsional).
     * @param ?int $id ID penerbit (opsional).
     */
    public function __construct(string $name = '', ?string $city = null, ?int $id = null) {
        $this->id = $id;
        $this->name = $name;
        $this->city = $city;
    }

    // Getter dan Setter (opsional, bisa ditambahkan sesuai kebutuhan)
    public function getId(): ?int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getCity(): ?string {
        return $this->city;
    }

    public function setId(?int $id): void {
        $this->id = $id;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function setCity(?string $city): void {
        $this->city = $city;
    }
}
?>
