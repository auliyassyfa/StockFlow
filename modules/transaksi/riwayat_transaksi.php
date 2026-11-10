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
        b.satuan,
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

    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Riwayat Transaksi</h2>
            <p class="text-muted mb-0">Catatan mutasi barang masuk dan barang keluar</p>
        </div>

        <div class="d-flex gap-2">
            <a href="transaksi_masuk.php" class="btn btn-success">
                <i class="bi bi-box-arrow-in-down me-1"></i>
                Barang Masuk
            </a>
            <a href="transaksi_keluar.php" class="btn btn-danger">
                <i class="bi bi-box-arrow-up me-1"></i>
                Barang Keluar
            </a>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="card shadow border-0">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" width="50">No</th>
                            <th width="110">Tanggal</th>
                            <th width="120">Kode</th>
                            <th>Nama Barang</th>
                            <th class="text-center" width="100">Jenis</th>
                            <th class="text-center" width="110">Jumlah</th>
                            <th width="160">Petugas</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        if (mysqli_num_rows($query) > 0) :
                            while ($row = mysqli_fetch_assoc($query)) :
                        ?>
                        <tr>
                            <td class="text-center"><?= $no++; ?></td>
                            <td><?= date('d/m/Y', strtotime($row['tanggal'])); ?></td>
                            <td><span class="badge bg-secondary"><?= htmlspecialchars($row['kode_barang'] ?? '-'); ?></span></td>
                            <td class="fw-semibold"><?= htmlspecialchars($row['nama_barang'] ?? '-'); ?></td>
                            <td class="text-center">
                                <?php if ($row['tipe_transaksi'] == 'masuk') : ?>
                                    <span class="badge bg-success">
                                        <i class="bi bi-arrow-down-left me-1"></i>Masuk
                                    </span>
                                <?php else : ?>
                                    <span class="badge bg-danger">
                                        <i class="bi bi-arrow-up-right me-1"></i>Keluar
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center fw-bold">
                                <?= number_format($row['jumlah']); ?> <small class="text-muted fw-normal"><?= htmlspecialchars($row['satuan'] ?? ''); ?></small>
                            </td>
                            <td>
                                <small><i class="bi bi-person me-1"></i><?= htmlspecialchars($row['nama_lengkap'] ?? 'User'); ?></small>
                            </td>
                            <td>
                                <?= !empty($row['keterangan']) ? htmlspecialchars($row['keterangan']) : '<span class="text-muted fst-italic">-</span>'; ?>
                            </td>
                        </tr>
                        <?php
                            endwhile;
                        else :
                        ?>
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="bi bi-clock-history fs-2 d-block mb-2"></i>
                                Belum ada riwayat transaksi barang.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>

<?php include '../../templates/footer.php'; ?>
