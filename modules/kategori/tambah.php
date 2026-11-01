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
| Simpan Data
|--------------------------------------------------------------------------
| Jika tombol submit ditekan maka data akan disimpan ke database
|
*/

if (isset($_POST['simpan'])) {

    // Mengambil data dari form
    $nama = $_POST['nama'];
    $deskripsi = $_POST['deskripsi'];

    // Query insert data
    $query = mysqli_query(
        $conn,
        "INSERT INTO kategori
        (
            nama,
            deskripsi,
            created_at
        )
        VALUES
        (
            '$nama',
            '$deskripsi',
            NOW()
        )"
    );

    // Jika berhasil
    if ($query) {

        header("Location: index.php");
        exit;
    }
}

include '../../templates/header.php';
include '../../templates/sidebar.php';
?>

<div class="main-content p-4">

    <div class="card shadow">

        <div class="card-header">

            <h4>Tambah Kategori</h4>

        </div>

        <div class="card-body">

            <form method="POST">

                <!-- Nama Kategori -->

                <div class="mb-3">

                    <label class="form-label">

                        Nama Kategori

                    </label>

                    <input
                        type="text"
                        name="nama"
                        class="form-control"
                        required>

                </div>

                <!-- Deskripsi -->

                <div class="mb-3">

                    <label class="form-label">

                        Deskripsi

                    </label>

                    <textarea
                        name="deskripsi"
                        class="form-control"
                        rows="4"></textarea>

                </div>

                <!-- Tombol -->

                <button
                    type="submit"
                    name="simpan"
                    class="btn btn-primary">

                    Simpan

                </button>

                <a
                    href="index.php"
                    class="btn btn-secondary">

                    Kembali

                </a>

            </form>

        </div>

    </div>

</div>

<?php
include '../../templates/footer.php';
?>