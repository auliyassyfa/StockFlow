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


$id = $_GET['id'];

// Ambil data kategori berdasarkan ID
$query = mysqli_query(
    $conn,
    "SELECT * FROM kategori WHERE id = '$id'"
);

$kategori = mysqli_fetch_assoc($query);

/*
|--------------------------------------------------------------------------
| Proses Update
|--------------------------------------------------------------------------
*/

if (isset($_POST['update'])) {

    $nama = $_POST['nama'];
    $deskripsi = $_POST['deskripsi'];

    $update = mysqli_query(
        $conn,
        "UPDATE kategori
        SET
            nama = '$nama',
            deskripsi = '$deskripsi',
            updated_at = NOW()
        WHERE id = '$id'"
    );

    if ($update) {

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
            <h4>Edit Kategori</h4>
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
                        value="<?= $kategori['nama']; ?>"
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
                        rows="4"><?= $kategori['deskripsi']; ?></textarea>

                </div>

                <!-- Tombol -->

                <button
                    type="submit"
                    name="update"
                    class="btn btn-warning">

                    Update

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