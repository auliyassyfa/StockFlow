<?php

// Menjalankan session
session_start();

// Cek login
if (!isset($_SESSION['user_id'])) {

    header("Location: ../../auth/login.php");
    exit;
}

// Koneksi database
require '../../config/database.php';

/*
|--------------------------------------------------------------------------
| Ambil ID dari URL
|--------------------------------------------------------------------------
*/

$id = $_GET['id'];

/*
|--------------------------------------------------------------------------
| Hapus Data
|--------------------------------------------------------------------------
*/

$hapus = mysqli_query(
    $conn,
    "DELETE FROM barang
     WHERE id = '$id'"
);

/*
|--------------------------------------------------------------------------
| Kembali ke halaman kategori
|--------------------------------------------------------------------------
*/

header("Location: index.php");
exit;