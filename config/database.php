<?php

/*
|--------------------------------------------------------------------------
| Koneksi Database
|--------------------------------------------------------------------------
| File ini digunakan untuk menghubungkan aplikasi ke database MySQL.
| Nantinya file ini akan dipanggil di halaman lain menggunakan:
|
| require '../config/database.php';
|
*/

// Informasi database
$host = "localhost";
$username = "root";
$password = "";
$database = "db_inventory_jwp";

// Membuat koneksi ke database
$conn = mysqli_connect(
    $host,
    $username,
    $password,
    $database
);

// Mengecek apakah koneksi berhasil
if (!$conn) {

    // Jika gagal, tampilkan pesan error
    die(
        "Koneksi database gagal : " .
        mysqli_connect_error()
    );
}