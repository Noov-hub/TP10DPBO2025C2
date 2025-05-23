-- Membuat Database (Jika belum ada)
CREATE DATABASE IF NOT EXISTS toko_buku_mvvm_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE toko_buku_mvvm_db;

-- Tabel Authors
CREATE TABLE IF NOT EXISTS `authors` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `country` VARCHAR(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel Publishers
CREATE TABLE IF NOT EXISTS `publishers` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `city` VARCHAR(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel Books
CREATE TABLE IF NOT EXISTS `books` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `author_id` INT DEFAULT NULL,
  `publisher_id` INT DEFAULT NULL,
  `year_published` INT DEFAULT NULL,
  `isbn` VARCHAR(20) UNIQUE DEFAULT NULL,
  `stock` INT DEFAULT 0,
  CONSTRAINT `fk_book_author` FOREIGN KEY (`author_id`) REFERENCES `authors` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_book_publisher` FOREIGN KEY (`publisher_id`) REFERENCES `publishers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Contoh Data Awal (Opsional)
INSERT INTO `authors` (`name`, `country`) VALUES
('Andrea Hirata', 'Indonesia'),
('Tere Liye', 'Indonesia'),
('J.K. Rowling', 'United Kingdom');

INSERT INTO `publishers` (`name`, `city`) VALUES
('Bentang Pustaka', 'Yogyakarta'),
('Gramedia Pustaka Utama', 'Jakarta'),
('Bloomsbury', 'London');

INSERT INTO `books` (`title`, `author_id`, `publisher_id`, `year_published`, `isbn`, `stock`) VALUES
('Laskar Pelangi', 1, 1, 2005, '979-3062-79-7', 10),
('Negeri Para Bedebah', 2, 2, 2012, '978-979-22-8552-9', 5),
('Harry Potter and the Philosopher\'s Stone', 3, 3, 1997, '0-7475-3269-9', 7);
