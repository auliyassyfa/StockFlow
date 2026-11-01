<?php

// Menjalankan session
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit;
}

// Memanggil koneksi database
require '../../config/database.php';

/*
|--------------------------------------------------------------------------
| Mengambil data kategori
|--------------------------------------------------------------------------
| Data ini digunakan untuk mengisi dropdown kategori
|
*/

$kategori = mysqli_query(
    $conn,
    "SELECT * FROM kategori ORDER BY nama ASC"
);

/*
|--------------------------------------------------------------------------
| Proses Simpan Barang
|--------------------------------------------------------------------------
*/

if (isset($_POST['simpan'])) {

    // Mengambil data dari form
    $kategori_id = $_POST['kategori_id'];
    $kode_barang = $_POST['kode_barang'];
    $nama_barang = $_POST['nama_barang'];
    $satuan = $_POST['satuan'];
    $stock = $_POST['stock'];
    $minimum_stock = $_POST['minimum_stock'];
    $deskripsi = $_POST['deskripsi'];

    // Simpan ke database
    $insert = mysqli_query(
        $conn,
        "INSERT INTO barang
        (
            kategori_id,
            kode_barang,
            nama_barang,
            satuan,
            stock,
            minimum_stock,
            deskripsi,
            created_at
        )
        VALUES
        (
            '$kategori_id',
            '$kode_barang',
            '$nama_barang',
            '$satuan',
            '$stock',
            '$minimum_stock',
            '$deskripsi',
            NOW()
        )"
    );

    // Jika berhasil
    if ($insert) {

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

            <h4>Tambah Barang</h4>

        </div>

        <div class="card-body">

            <form method="POST">

                <div class="row">
                    <!-- Kategori -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Kategori Barang
                        </label>
                        <select
                            name="kategori_id"
                            class="form-select"
                            required>
                            <option value="">
                                -- Pilih Kategori --
                            </option>
                            <?php while($k = mysqli_fetch_assoc($kategori)) : ?>
                                <option value="<?= $k['id']; ?>">
                                    <?= $k['nama']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <!-- Kode Barang -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Kode Barang
                        </label>
                        <input
                            type="text"
                            name="kode_barang"
                            class="form-control"
                            required>
                    </div>
                </div>

                <div class="row">
                    <!-- Nama Barang -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Nama Barang
                        </label>
                        <input
                            type="text"
                            name="nama_barang"
                            class="form-control"
                            required>
                    </div>

                    <!-- Satuan -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Satuan
                        </label>
                        <input
                            type="text"
                            name="satuan"
                            class="form-control"
                            placeholder="Contoh: Sak, Kg, Liter"
                            required>
                    </div>
                </div>

                <div class="row">
                    <!-- Stock -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Stock Awal
                        </label>
                        <input
                            type="number"
                            name="stock"
                            class="form-control"
                            min="0"
                            required>
                    </div>

                    <!-- Minimum Stock -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Minimum Stock
                        </label>
                        <input
                            type="number"
                            name="minimum_stock"
                            class="form-control"
                            min="0"
                            required>
                    </div>
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
                <div class="mt-4">
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
                </div>

            </form>

        </div>

    </div>

</div>

<?php
include '../../templates/footer.php';
?>