<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit;
}

require '../../config/database.php';

$tahun = isset($_GET['tahun'])
    ? $_GET['tahun']
    : date('Y');

$query = mysqli_query(
    $conn,
    "SELECT
        st.*,
        b.nama_barang,
        u.nama_lengkap
    FROM stok_transaksi st
    JOIN barang b
        ON st.barang_id = b.id
    JOIN users u
        ON st.user_id = u.id
    WHERE YEAR(st.tanggal) = '$tahun'
    ORDER BY st.tanggal DESC"
);

include '../../templates/header.php';
include '../../templates/sidebar.php';

?>
<div class="main-content p-4">

<div class="card shadow">

<div class="card-header">
    <h4>Laporan Stok Barang</h4>
</div>

<div class="card-body">

<form method="GET" class="row mb-3">

    <div class="col-md-3">

        <select
            name="tahun"
            class="form-select">

            <?php

            for ($i = date('Y'); $i >= 2020; $i--) :

            ?>

                <option
                    value="<?= $i; ?>"
                    <?= ($tahun == $i) ? 'selected' : ''; ?>>

                    <?= $i; ?>

                </option>

            <?php endfor; ?>

        </select>

    </div>

    <div class="col-md-2">

        <button
            type="submit"
            class="btn btn-primary">

            Filter

        </button>

    </div>

</form>
<table class="table table-bordered">

    <thead>

        <tr>

            <th>No</th>
            <th>Tanggal</th>
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
<a
    href="cetak_laporan.php?tahun=<?= $tahun; ?>"
    target="_blank"
    class="btn btn-success mb-3">

    Cetak Laporan

</a>

<?php include '../../templates/footer.php'; ?>