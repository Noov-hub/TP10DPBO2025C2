# TP10DPBO2025C2
isinya TP10 DPBO Sem 4

# Manajemen Inventaris Toko Buku Sederhana (MVVM PHP)

## 1. Desain Program

### 1.1. Tujuan Aplikasi

* Memungkinkan pengguna untuk mengelola data buku, termasuk informasi penulis dan penerbit.
* Menyediakan fungsionalitas CRUD (Create, Read, Update, Delete) untuk entitas utama: Buku, Penulis, dan Penerbit.
* Mengimplementasikan pola desain MVVM untuk memisahkan logika bisnis, presentasi, dan antarmuka pengguna.
* Menggunakan PDO (PHP Data Objects) dengan prepared statements untuk interaksi database yang aman.
* Mengimplementasikan fitur data binding sederhana antara View dan ViewModel.

### 1.2. Struktur Database

1.  **`authors`**: Menyimpan data penulis.
    * `id` (INT, Primary Key, Auto Increment)
    * `name` (VARCHAR(100), Not Null)
    * `country` (VARCHAR(50))
2.  **`publishers`**: Menyimpan data penerbit.
    * `id` (INT, Primary Key, Auto Increment)
    * `name` (VARCHAR(100), Not Null)
    * `city` (VARCHAR(50))
3.  **`books`**: Menyimpan data buku dan relasinya dengan penulis serta penerbit.
    * `id` (INT, Primary Key, Auto Increment)
    * `title` (VARCHAR(255), Not Null)
    * `author_id` (INT, Foreign Key ke `authors(id)`)
    * `publisher_id` (INT, Foreign Key ke `publishers(id)`)
    * `year_published` (INT)
    * `isbn` (VARCHAR(20), Unique)
    * `stock` (INT, Default 0)

**Relasi Antar Tabel:**

* `books.author_id` --- `authors.id` (Satu penulis bisa memiliki banyak buku)
* `books.publisher_id` --- `publishers.id` (Satu penerbit bisa menerbitkan banyak buku)

### 1.3. Pola Arsitektur MVVM 

Aplikasi ini mengikuti pola arsitektur Model-View-ViewModel (MVVM)

* **Model (`models/`)**: Merepresentasikan data dan struktur tabel dari database. Berisi kelas-kelas PHP sederhana (POPO) seperti `Author.php`, `Publisher.php`, dan `Book.php` yang propertinya mencerminkan kolom tabel.
* **View (`views/`)**: Bertanggung jawab untuk menampilkan antarmuka pengguna (UI) dalam format HTML. View mendapatkan data dari ViewModel dan juga mengirimkan input pengguna (misalnya, melalui form) ke ViewModel (melalui controller/router).Terdiri dari file-file PHP yang merender data dan template.
* **ViewModel (`viewmodels/`)**: Bertindak sebagai perantara antara Model dan View. ViewModel berisi logika presentasi, mengambil data dari database (dan memetakannya ke Model jika perlu), memproses input pengguna, melakukan operasi CRUD, dan menyediakan data yang siap ditampilkan untuk View. Contohnya `AuthorViewModel.php`, `PublisherViewModel.php`, dan `BookViewModel.php`.

### 1.4. Struktur Folder Proyek 
```
toko_buku_mvvm/
├── config/
│   └── Database.php         # Konfigurasi koneksi PDO ke database 
├── database/
│   └── toko_buku.sql        # File SQL untuk membuat struktur tabel 
├── models/                  # Berisi kelas-kelas Model 
│   ├── Author.php
│   ├── Publisher.php
│   └── Book.php
├── viewmodels/              # Berisi kelas-kelas ViewModel 
│   ├── AuthorViewModel.php
│   ├── PublisherViewModel.php
│   └── BookViewModel.php
├── views/                   # Berisi file-file View (HTML + PHP untuk display) 
│   ├── template/            # Template header dan footer
│   │   ├── header.php
│   │   └── footer.php
│   ├── authors/             # View untuk manajemen penulis
│   │   ├── index.php
│   │   └── edit.php
│   ├── publishers/          # View untuk manajemen penerbit
│   │   ├── index.php
│   │   └── edit.php
│   ├── books/               # View untuk manajemen buku
│   │   ├── index.php
│   │   └── edit.php
│   └── home.php             # Halaman utama aplikasi
├── assets/                  # (Opsional) Untuk file statis seperti CSS, JS, (saya g pake)
│   └── css/
│       └── style.css
├── index.php                # Router utama dan entry point aplikasi
└── README.md                # File ini
```
### 1.5. Fitur Data Binding

Data binding dalam aplikasi PHP native ini diimplementasikan secara manual:

* **ViewModel ke View (One-way)**: ViewModel mengambil data dari database, memprosesnya, dan mengirimkannya sebagai variabel ke file View. File View kemudian menggunakan variabel ini untuk menampilkan data dalam HTML. Perubahan pada data di ViewModel akan tercermin di View saat halaman di-render ulang.
* **View ke ViewModel (Input Pengguna)**: Input dari pengguna melalui form HTML (misalnya, saat menambah atau mengedit data) dikirimkan melalui metode POST atau GET. Router (`index.php`) menangkap data ini dan meneruskannya ke metode yang sesuai di ViewModel. ViewModel kemudian memvalidasi dan memproses data ini untuk melakukan operasi CRUD ke database. Setelah operasi, biasanya dilakukan redirect untuk menampilkan data terbaru (mensimulasikan pembaruan View).

## 2. Penjelasan Alur Program

### 2.1. Alur Umum Permintaan (Request Flow)

1.  Pengguna mengakses URL aplikasi, misalnya `http://localhost/toko_buku_mvvm/index.php?action=authors`.
2.  File `index.php` (router utama) menerima permintaan.
3.  Router menganalisis parameter `action` (misalnya, `authors`).
4.  Berdasarkan `action`, router menginisialisasi ViewModel yang sesuai (misalnya, `AuthorViewModel`).
5.  **Untuk Operasi Read (Menampilkan Data):**
    * Router memanggil metode pada ViewModel untuk mengambil data (misalnya, `$authorViewModel->getAll()`).
    * ViewModel berinteraksi dengan `config/Database.php` untuk koneksi, menjalankan query SQL (menggunakan PDO dan prepared statements) untuk mengambil data dari tabel yang relevan.
    * ViewModel dapat memetakan hasil query ke objek-objek Model (misalnya, array objek `Author`).
    * ViewModel mengembalikan data (array objek Model) ke router.
    * Router menyertakan (require) file View yang sesuai (misalnya, `views/authors/index.php`) dan meneruskan data yang diterima dari ViewModel ke View tersebut.
    * View menggunakan data yang diterima untuk merender halaman HTML yang akan ditampilkan ke pengguna.
6.  **Untuk Operasi Create/Update/Delete (Input Pengguna):**
    * Pengguna mengisi form di View dan mengirimkannya (misalnya, menambah penulis baru).
    * Permintaan (biasanya POST) dikirim ke `index.php` dengan `action` yang sesuai.
    * Router mendeteksi metode request (POST) dan data yang dikirim.
    * Router memanggil metode pada ViewModel yang sesuai (misalnya, `$authorViewModel->create(new Author($_POST['name'], $_POST['country']))`).
    * ViewModel menerima data (bisa dalam bentuk objek Model), melakukan validasi jika perlu.
    * ViewModel menjalankan query SQL (INSERT, UPDATE, atau DELETE) menggunakan PDO dan prepared statements.
    * Setelah operasi database selesai, ViewModel mengembalikan status keberhasilan ke router.
    * Router biasanya melakukan redirect kembali ke halaman daftar (misalnya, `index.php?action=authors`) untuk menampilkan data terbaru dan mencegah resubmission form. Pesan status (flash message) dapat ditampilkan.

### 2.2. Alur CRUD (Contoh: Manajemen Penulis)

* **Create (Tambah Penulis):**
    1.  Pengguna mengakses `index.php?action=authors`.
    2.  View `views/authors/index.php` menampilkan form tambah penulis.
    3.  Pengguna mengisi form dan submit.
    4.  `index.php` menerima data POST, membuat objek `Author`, dan memanggil `$authorViewModel->create($author)`.
    5.  `AuthorViewModel` menyimpan data ke tabel `authors`.
    6.  `index.php` redirect ke `index.php?action=authors` dengan pesan sukses/gagal.
* **Read (Lihat Daftar Penulis):**
    1.  Pengguna mengakses `index.php?action=authors`.
    2.  `index.php` memanggil `$authorViewModel->getAll()`.
    3.  `AuthorViewModel` mengambil semua data dari tabel `authors`.
    4.  `index.php` meneruskan data penulis ke `views/authors/index.php`.
    5.  View menampilkan daftar penulis dalam tabel.
* **Update (Edit Penulis):**
    1.  Pengguna mengklik link "Edit" pada salah satu penulis di daftar. Link mengarah ke `index.php?action=edit_author&id=[id_penulis]`.
    2.  `index.php` memanggil `$authorViewModel->getById($id)`.
    3.  `AuthorViewModel` mengambil data penulis spesifik.
    4.  `index.php` meneruskan data penulis ke `views/authors/edit.php`.
    5.  View menampilkan form edit yang sudah terisi data penulis.
    6.  Pengguna mengubah data dan submit form.
    7.  `index.php` menerima data POST, membuat objek `Author` dengan ID, dan memanggil `$authorViewModel->update($author)`.
    8.  `AuthorViewModel` memperbarui data di tabel `authors`.
    9.  `index.php` redirect ke `index.php?action=authors` dengan pesan sukses/gagal.
* **Delete (Hapus Penulis):**
    1.  Pengguna mengklik link "Hapus" pada salah satu penulis. Link mengarah ke `index.php?action=delete_author&id=[id_penulis]` (biasanya dengan konfirmasi JavaScript).
    2.  `index.php` memanggil `$authorViewModel->delete($id)`.
    3.  `AuthorViewModel` menghapus data dari tabel `authors`.
    4.  `index.php` redirect ke `index.php?action=authors` dengan pesan sukses/gagal.

Alur serupa berlaku untuk manajemen Penerbit dan Buku, dengan penyesuaian pada ViewModel dan View yang digunakan. Untuk Buku, prosesnya melibatkan foreign key ke Author dan Publisher.

## 4. Dokumentasi Saat Program Dijalankan (Contoh)

*(Bagian ini sebaiknya diisi dengan screenshot atau screen recording saat program dijalankan, menunjukkan fungsionalitas utama seperti yang diminta pada tugas.)*

### 4.1. Halaman Utama

![image](https://github.com/user-attachments/assets/9f7ba9f3-935a-4257-990d-37eefd1ae8cc)


### 4.2. Manajemen Penulis

![image](https://github.com/user-attachments/assets/a358a205-5b2d-4ad9-904a-57c29e3019ef)

### 4.3. Manajemen Penerbit

![image](https://github.com/user-attachments/assets/3eb4e56b-acca-4607-9846-933c0e8c88ec)

### 4.4. Manajemen Buku

![image](https://github.com/user-attachments/assets/b93c239c-30ed-4e14-a630-76e639e5e125)


---
