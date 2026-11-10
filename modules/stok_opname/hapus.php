<?php

session_start();

// Cek apakah user sudah login
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
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    /*
    |--------------------------------------------------------------------------
    | Hapus Data Stok Opname
    |--------------------------------------------------------------------------
    */
    mysqli_query(
        $conn,
        "DELETE FROM stok_opname WHERE id = '$id'"
    );

    header("Location: index.php?status=deleted");
    exit;
}

header("Location: index.php");
exit;

