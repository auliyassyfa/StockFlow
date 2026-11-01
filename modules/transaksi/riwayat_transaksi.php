<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit;
}

require '../../config/database.php';

/*
|--------------------------------------------------------------------------
| Ambil Data Riwayat Transaksi
|--------------------------------------------------------------------------
*/

$query = mysqli_query(
    $conn,
    "SELECT
        st.*,
        b.nama_barang,
        b.kode_barang,
        u.nama_lengkap
    FROM stok_transaksi st
    LEFT JOIN barang b
        ON st.barang_id = b.id
    LEFT JOIN users u
        ON st.user_id = u.id
    ORDER BY st.tanggal DESC, st.id DESC"
);

include '../../templates/header.php';
include '../../templates/sidebar.php';

?>

<div class="main-content p-4">

    <div class="card shadow">

        <div class="card-header d-flex justify-content-between">

            <h4>Riwayat Transaksi</h4>

        </div>

        <div class="card-body">

            <table class="table table-bordered table-striped">

                <thead>

                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Kode</th>
                        <th>Barang</th>
                        <th>Jenis</th>
                        <th>Jumlah</th>
                        <th>User</th>
                        <th>Keterangan</th>
                    </tr>

                </thead>

                <tbody>

                    <?php
                    $no = 1;

                    while($row = mysqli_fetch_assoc($query)) :
                    ?>

                    <tr>

                        <td><?= $no++; ?></td>

                        <td><?= $row['tanggal']; ?></td>

                        <td><?= $row['kode_barang']; ?></td>

                        <td><?= $row['nama_barang']; ?></td>

                        <td>

                            <?php if($row['tipe_transaksi'] == 'masuk') : ?>

                                <span class="badge bg-success">
                                    Masuk
                                </span>

                            <?php else : ?>

                                <span class="badge bg-danger">
                                    Keluar
                                </span>

                            <?php endif; ?>

                        </td>

                        <td><?= $row['jumlah']; ?></td>

                        <td><?= $row['nama_lengkap']; ?></td>

                        <td><?= $row['keterangan']; ?></td>

                    </tr>

                    <?php endwhile; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php include '../../templates/footer.php'; ?>