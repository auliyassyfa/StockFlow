<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit;
}

require '../../config/database.php';

/*
|--------------------------------------------------------------------------
| Ambil data barang + kategori
|--------------------------------------------------------------------------
*/

$query = mysqli_query(
    $conn,
    "SELECT
        barang.*,
        kategori.nama AS nama_kategori
    FROM barang
    LEFT JOIN kategori
        ON barang.kategori_id = kategori.id
    ORDER BY barang.id DESC"
);

include '../../templates/header.php';
include '../../templates/sidebar.php';
?>

<div class="main-content p-4">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <h2>Daftar Barang</h2>

        <a
            href="tambah.php"
            class="btn btn-primary">

            <i class="bi bi-plus-circle"></i>
            Tambah Barang

        </a>

    </div>

    <div class="card shadow">

        <div class="card-body">

            <table class="table table-bordered table-striped">

                <thead>

                    <tr>

                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Satuan</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th>Aksi</th>

                    </tr>

                </thead>

                <tbody>

                    <?php
                    $no = 1;

                    while ($barang = mysqli_fetch_assoc($query)) :
                    ?>

                    <tr>

                        <td><?= $no++; ?></td>

                        <td><?= $barang['kode_barang']; ?></td>

                        <td><?= $barang['nama_barang']; ?></td>

                        <td><?= $barang['nama_kategori']; ?></td>

                        <td><?= $barang['satuan']; ?></td>

                        <td><?= $barang['stock']; ?></td>

                        <td>

                            <?php if ($barang['stock'] > 0) : ?>

                                <span class="badge bg-success">

                                    Tersedia

                                </span>

                            <?php else : ?>

                                <span class="badge bg-danger">

                                    Tidak Tersedia

                                </span>

                            <?php endif; ?>

                        </td>

                        <td>

                            <a
                                href="edit.php?id=<?= $barang['id']; ?>"
                                class="btn btn-warning btn-sm">

                                Edit

                            </a>

                            <a
                                href="hapus.php?id=<?= $barang['id']; ?>"
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Yakin hapus data?')">

                                Hapus

                            </a>

                        </td>

                    </tr>

                    <?php endwhile; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php
include '../../templates/footer.php';
?>