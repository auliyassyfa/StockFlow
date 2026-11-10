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
        b.kode_barang,
        b.satuan,
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

    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Laporan Stok Barang</h2>
            <p class="text-muted mb-0">Rekapitulasi tahunan pergerakan barang masuk dan keluar</p>
        </div>

        <a href="cetak_laporan.php?tahun=<?= $tahun; ?>" target="_blank" class="btn btn-outline-secondary">
            <i class="bi bi-printer me-1"></i>
            Cetak Laporan
        </a>
    </div>

    <div class="card shadow border-0">
        <div class="card-body">

            <!-- Filter Tahun -->
            <form method="GET" class="row g-2 align-items-center mb-3">
                <div class="col-auto">
                    <label class="form-label mb-0 fw-semibold text-muted">Pilih Tahun:</label>
                </div>
                <div class="col-auto">
                    <select name="tahun" class="form-select form-select-sm" style="width: 140px;">
                        <?php for ($i = date('Y'); $i >= 2020; $i--) : ?>
                            <option value="<?= $i; ?>" <?= ($tahun == $i) ? 'selected' : ''; ?>>
                                <?= $i; ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-filter"></i> Filter
                    </button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" width="50">No</th>
                            <th width="110">Tanggal</th>
                            <th>Barang</th>
                            <th class="text-center" width="100">Jenis</th>
                            <th class="text-center" width="100">Jumlah</th>
                            <th width="160">User</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        if (mysqli_num_rows($query) > 0) :
                            while($row = mysqli_fetch_assoc($query)) :
                        ?>
                        <tr>
                            <td class="text-center"><?= $no++; ?></td>
                            <td><?= date('d/m/Y', strtotime($row['tanggal'])); ?></td>
                            <td class="fw-semibold">
                                <?= htmlspecialchars($row['nama_barang']); ?>
                                <small class="text-muted d-block">[<?= htmlspecialchars($row['kode_barang']); ?>]</small>
                            </td>
                            <td class="text-center">
                                <?php if($row['tipe_transaksi'] == 'masuk') : ?>
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
                            <td><small><i class="bi bi-person me-1"></i><?= htmlspecialchars($row['nama_lengkap']); ?></small></td>
                            <td><?= !empty($row['keterangan']) ? htmlspecialchars($row['keterangan']) : '<span class="text-muted fst-italic">-</span>'; ?></td>
                        </tr>
                        <?php
                            endwhile;
                        else :
                        ?>
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="bi bi-file-earmark-bar-graph fs-2 d-block mb-2"></i>
                                Tidak ada data transaksi untuk tahun <?= $tahun; ?>.
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
