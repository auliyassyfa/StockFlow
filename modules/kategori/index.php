<?php

// Memulai session
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit;
}

// Koneksi database
require '../../config/database.php';

// Mengambil seluruh data kategori
$query = mysqli_query(
    $conn,
    "SELECT * FROM kategori
     ORDER BY id DESC"
);

include '../../templates/header.php';
include '../../templates/sidebar.php';
?>

<div class="main-content p-4">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <h2>Kategori Barang</h2>

        <a
            href="tambah.php"
            class="btn btn-primary">

            <i class="bi bi-plus-circle"></i>
            Tambah Kategori

        </a>

    </div>

    <div class="card shadow">

        <div class="card-body">

            <table class="table table-bordered table-striped">

                <thead>

                    <tr>

                        <th width="5%">No</th>
                        <th>Nama Kategori</th>
                        <th>Deskripsi</th>
                        <th width="25%">Aksi</th>

                    </tr>

                </thead>

                <tbody>

                    <?php
                    $no = 1;

                    while ($kategori = mysqli_fetch_assoc($query)) :
                    ?>

                    <tr>

                        <td><?= $no++; ?></td>

                        <td>
                            <?= $kategori['nama']; ?>
                        </td>

                        <td>
                            <?= $kategori['deskripsi']; ?>
                        </td>

                        <td>

            

                            <a
                                href="edit.php?id=<?= $kategori['id']; ?>"
                                class="btn btn-warning btn-sm">

                                Edit

                            </a>

                            <a
                                href="hapus.php?id=<?= $kategori['id']; ?>"
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Yakin ingin menghapus kategori ini?')">

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