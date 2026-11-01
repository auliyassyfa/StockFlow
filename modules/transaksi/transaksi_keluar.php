<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit;
}

require '../../config/database.php';

/*
|--------------------------------------------------------------------------
| Ambil Data Barang
|--------------------------------------------------------------------------
*/

$barang = mysqli_query(
    $conn,
    "SELECT * FROM barang
     ORDER BY nama_barang ASC"
);

/*
|--------------------------------------------------------------------------
| Proses Simpan Transaksi
|--------------------------------------------------------------------------
*/

if (isset($_POST['simpan'])) {

    $barang_id = $_POST['barang_id'];
    $jumlah = $_POST['jumlah'];
    $tanggal = $_POST['tanggal'];
    $keterangan = $_POST['keterangan'];

    // User yang sedang login
    $user_id = $_SESSION['user_id'];

    /*
|--------------------------------------------------------------------------
| Cek Stok Saat Ini
|--------------------------------------------------------------------------
*/

$queryBarang = mysqli_query(
    $conn,
    "SELECT stock
     FROM barang
     WHERE id = '$barang_id'"
);

$dataBarang = mysqli_fetch_assoc($queryBarang);

$stokSaatIni = $dataBarang['stock'];

/*
|--------------------------------------------------------------------------
| Validasi Stok
|--------------------------------------------------------------------------
*/

if ($jumlah > $stokSaatIni) {

    echo "
        <script>
            alert('Stok tidak mencukupi!');
            window.history.back();
        </script>
    ";

    exit;
}

    /*
    |--------------------------------------------------------------------------
    | Simpan Transaksi
    |--------------------------------------------------------------------------
    */

    mysqli_query(
        $conn,
        "INSERT INTO stok_transaksi
        (
            barang_id,
            user_id,
            tipe_transaksi,
            jumlah,
            tanggal,
            keterangan,
            created_at
        )
        VALUES
        (
            '$barang_id',
            '$user_id',
            'keluar',
            '$jumlah',
            '$tanggal',
            '$keterangan',
            NOW()
        )"
    );

    /*
    |--------------------------------------------------------------------------
    | Tambah Stok Barang
    |--------------------------------------------------------------------------
    */

    mysqli_query(
        $conn,
        "UPDATE barang
         SET stock = stock - $jumlah
         WHERE id = '$barang_id'"
    );

    header("Location: riwayat_transaksi.php");
    exit;
}

include '../../templates/header.php';
include '../../templates/sidebar.php';

?>

<div class="main-content p-4">

    <div class="card shadow">

        <div class="card-header">
            <h4>Barang Masuk</h4>
        </div>

        <div class="card-body">

            <form method="POST">

                <!-- Barang -->

                <div class="mb-3">

                    <label class="form-label">
                        Pilih Barang
                    </label>

                    <select
                        name="barang_id"
                        class="form-select"
                        required>

                        <option value="">
                            -- Pilih Barang --
                        </option>

                        <?php while($b = mysqli_fetch_assoc($barang)) : ?>

                            <option value="<?= $b['id']; ?>">

                                <?= $b['kode_barang']; ?>
                                -
                                <?= $b['nama_barang']; ?>

                            </option>

                        <?php endwhile; ?>

                    </select>

                </div>

                <!-- Jumlah -->

                <div class="mb-3">

                    <label class="form-label">
                        Jumlah Keluar
                    </label>

                    <input
                        type="number"
                        name="jumlah"
                        class="form-control"
                        min="1"
                        required>

                </div>

                <!-- Tanggal -->

                <div class="mb-3">

                    <label class="form-label">
                        Tanggal Transaksi
                    </label>

                    <input
                        type="date"
                        name="tanggal"
                        class="form-control"
                        value="<?= date('Y-m-d'); ?>"
                        required>

                </div>

                <!-- Keterangan -->

                <div class="mb-3">

                    <label class="form-label">
                        Keterangan
                    </label>

                    <textarea
                        name="keterangan"
                        class="form-control"
                        rows="3"></textarea>

                </div>

                <button
                    type="submit"
                    name="simpan"
                    class="btn btn-success">

                    Simpan

                </button>

                <a
                    href="riwayat_transaksi.php"
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