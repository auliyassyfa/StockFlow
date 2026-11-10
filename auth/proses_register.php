<?php

session_start();

// Memanggil koneksi database
require '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: register.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| Mengambil dan membersihkan input form
|--------------------------------------------------------------------------
*/
$nama_lengkap = mysqli_real_escape_string($conn, trim($_POST['nama_lengkap'] ?? ''));
$username     = mysqli_real_escape_string($conn, trim($_POST['username'] ?? ''));
$password     = $_POST['password'] ?? '';
$confirm_pwd  = $_POST['confirm_password'] ?? '';

/*
|--------------------------------------------------------------------------
| Validasi Form
|--------------------------------------------------------------------------
*/

// Cek field kosong
if (empty($nama_lengkap) || empty($username) || empty($password)) {
    header("Location: register.php?error=empty_fields");
    exit;
}

// Cek format username (tidak boleh ada spasi)
if (preg_match('/\s/', $username)) {
    header("Location: register.php?error=invalid_username");
    exit;
}

// Cek panjang password minimal 4 karakter
if (strlen($password) < 4) {
    header("Location: register.php?error=password_short");
    exit;
}

// Cek konfirmasi password
if ($password !== $confirm_pwd) {
    header("Location: register.php?error=password_mismatch");
    exit;
}

// Cek apakah username sudah terdaftar
$check = mysqli_query($conn, "SELECT id FROM users WHERE username = '$username'");
if (mysqli_num_rows($check) > 0) {
    header("Location: register.php?error=username_exists");
    exit;
}

/*
|--------------------------------------------------------------------------
| Simpan Akun Baru ke Database
|--------------------------------------------------------------------------
*/
$password_hash = md5($password);

$insert = mysqli_query(
    $conn,
    "INSERT INTO users
    (
        nama_lengkap,
        username,
        password,
        created_at
    )
    VALUES
    (
        '$nama_lengkap',
        '$username',
        '$password_hash',
        NOW()
    )"
);

if ($insert) {
    // Registrasi sukses -> arahkan ke login dengan status sukses
    header("Location: login.php?registered=1");
    exit;
} else {
    header("Location: register.php?error=failed");
    exit;
}

