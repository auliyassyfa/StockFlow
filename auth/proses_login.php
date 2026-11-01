<?php

// Menjalankan session
session_start();

// Memanggil koneksi database
require '../config/database.php';

/*
|--------------------------------------------------------------------------
| Mengambil data dari form login
|--------------------------------------------------------------------------
*/

$username = $_POST['username'];
$password = md5($_POST['password']);

/*
|--------------------------------------------------------------------------
| Mencari user berdasarkan username dan password
|--------------------------------------------------------------------------
*/

$query = mysqli_query(
    $conn,
    "SELECT * FROM users
     WHERE username='$username'
     AND password='$password'"
);

/*
|--------------------------------------------------------------------------
| Mengambil hasil query
|--------------------------------------------------------------------------
*/

$user = mysqli_fetch_assoc($query);

/*
|--------------------------------------------------------------------------
| Jika user ditemukan
|--------------------------------------------------------------------------
*/

if ($user) {

    // Menyimpan data user ke session

    $_SESSION['user_id'] = $user['id'];

    $_SESSION['username'] = $user['username'];

    $_SESSION['nama_lengkap'] = $user['nama_lengkap'];

    // Redirect ke dashboard

    header(
        "Location: ../dashboard/index.php"
    );

} else {

    // Jika gagal login

    header(
        "Location: login.php?error=1"
    );
}