<?php

session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit;
}

require '../../config/database.php';

/*
|--------------------------------------------------------------------------
| Filter & Data Stok Opname
|--------------------------------------------------------------------------
*/

$filter_keyword = isset($_GET['cari']) ? mysqli_real_escape_string($conn, trim($_GET['cari'])) : '';
$filter_tanggal = isset($_GET['tanggal']) ? mysqli_real_escape_string($conn, trim($_GET['tanggal'])) : '';

$where_clauses = [];

if (!empty($filter_keyword)) {
    $where_clauses[] = "(b.nama_barang LIKE '%$filter_keyword%' OR b.kode_barang LIKE '%$filter_keyword%')";
}

if (!empty($filter_tanggal)) {
    $where_clauses[] = "so.tanggal_opname = '$filter_tanggal'";
}

$where_sql = '';
if (count($where_clauses) > 0) {
    $where_sql = "WHERE " . implode(" AND ", $where_clauses);
}

// Query data stok opname
$query = mysqli_query(
    $conn,
    "SELECT
        so.*,
        b.kode_barang,
        b.nama_barang,
        b.satuan,
        u.nama_lengkap
    FROM stok_opname so
    LEFT JOIN barang b
        ON so.barang_id = b.id
    LEFT JOIN users u
        ON so.user_id = u.id
    $where_sql
    ORDER BY so.tanggal_opname DESC, so.id DESC"
);

// Statistik Ringkas Stok Opname
$stat_query = mysqli_query(
    $conn,
    "SELECT
        COUNT(*) as total_opname,
        SUM(CASE WHEN selisih = 0 THEN 1 ELSE 0 END) as total_cocok,
        SUM(CASE WHEN selisih < 0 THEN 1 ELSE 0 END) as total_kurang,
        SUM(CASE WHEN selisih > 0 THEN 1 ELSE 0 END) as total_lebih
    FROM stok_opname"
);
$stat = mysqli_fetch_assoc($stat_query);

include '../../templates/header.php';
include '../../templates/sidebar.php';
?>

<div class="main-content p-4">

    <!-- Header & Tombol Tambah -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Stok Opname</h2>
            <p class="text-muted mb-0">Pemeriksaan dan penyesuaian stok fisik dengan stok sistem</p>
        </div>

        <div class="d-flex gap-2">
            <a href="cetak.php?tanggal=<?= urlencode($filter_tanggal); ?>&cari=<?= urlencode($filter_keyword); ?>" target="_blank" class="btn btn-outline-secondary">
                <i class="bi bi-printer me-1"></i>
                Cetak Laporan
            </a>
            <a href="tambah.php" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>
                Tambah Stok Opname
            </a>
        </div>
    </div>

    <!-- Alert Notifikasi -->
    <?php if (isset($_GET['status']) && $_GET['status'] == 'success') : ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            Data stok opname berhasil disimpan!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php elseif (isset($_GET['status']) && $_GET['status'] == 'deleted') : ?>
        <div class="alert alert-warning alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-trash-fill me-2"></i>
            Data stok opname berhasil dihapus!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Kartu Statistik -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-3 bg-primary bg-opacity-10 text-primary p-3 me-3 fs-3">
                        <i class="bi bi-clipboard-data"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Total Opname</div>
                        <h4 class="fw-bold mb-0"><?= number_format($stat['total_opname'] ?? 0); ?></h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-3 bg-success bg-opacity-10 text-success p-3 me-3 fs-3">
                        <i class="bi bi-check2-circle"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Stok Sesuai (0)</div>
                        <h4 class="fw-bold mb-0 text-success"><?= number_format($stat['total_cocok'] ?? 0); ?></h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-3 bg-danger bg-opacity-10 text-danger p-3 me-3 fs-3">
                        <i class="bi bi-dash-circle"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Selisih Kurang (-)</div>
                        <h4 class="fw-bold mb-0 text-danger"><?= number_format($stat['total_kurang'] ?? 0); ?></h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-3 bg-warning bg-opacity-10 text-warning p-3 me-3 fs-3">
                        <i class="bi bi-plus-circle"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Selisih Lebih (+)</div>
                        <h4 class="fw-bold mb-0 text-warning"><?= number_format($stat['total_lebih'] ?? 0); ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Tabel Data -->
    <div class="card shadow border-0">
        <div class="card-body">

            <!-- Form Pencarian & Filter -->
            <form method="GET" class="row g-2 mb-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" name="cari" class="form-control" placeholder="Cari kode atau nama barang..." value="<?= htmlspecialchars($filter_keyword); ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <input type="date" name="tanggal" class="form-control" value="<?= htmlspecialchars($filter_tanggal); ?>">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-outline-primary me-2">
                        <i class="bi bi-filter"></i> Filter
                    </button>
                    <a href="index.php" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" width="50">No</th>
                            <th>Tanggal</th>
                            <th>Barang</th>
                            <th class="text-center">Stok Sistem</th>
                            <th class="text-center">Stok Fisik</th>
                            <th class="text-center">Selisih</th>
                            <th>Keterangan</th>
                            <th>Petugas</th>
                            <th class="text-center" width="100">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($query) > 0) : ?>
                            <?php
                            $no = 1;
                            while ($row = mysqli_fetch_assoc($query)) :
                                $selisih = (int)$row['selisih'];
                                $satuan = htmlspecialchars($row['satuan'] ?? '');
                            ?>
                            <tr>
                                <td class="text-center"><?= $no++; ?></td>
                                <td><?= date('d/m/Y', strtotime($row['tanggal_opname'])); ?></td>
                                <td>
                                    <strong><?= htmlspecialchars($row['nama_barang'] ?? '-'); ?></strong>
                                    <br>
                                    <small class="text-muted"><i class="bi bi-upc-scan"></i> <?= htmlspecialchars($row['kode_barang'] ?? '-'); ?></small>
                                </td>
                                <td class="text-center fw-semibold"><?= number_format($row['stok_sistem']) . ' ' . $satuan; ?></td>
                                <td class="text-center fw-bold text-primary"><?= number_format($row['stok_fisik']) . ' ' . $satuan; ?></td>
                                <td class="text-center">
                                    <?php if ($selisih == 0) : ?>
                                        <span class="badge bg-success px-2 py-1">
                                            <i class="bi bi-check-lg"></i> Cocok (0)
                                        </span>
                                    <?php elseif ($selisih > 0) : ?>
                                        <span class="badge bg-warning text-dark px-2 py-1">
                                            <i class="bi bi-arrow-up-right"></i> +<?= number_format($selisih); ?> (Lebih)
                                        </span>
                                    <?php else : ?>
                                        <span class="badge bg-danger px-2 py-1">
                                            <i class="bi bi-arrow-down-right"></i> <?= number_format($selisih); ?> (Kurang)
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($row['keterangan'])) : ?>
                                        <?= nl2br(htmlspecialchars($row['keterangan'])); ?>
                                    <?php else : ?>
                                        <span class="text-muted fst-italic">- Tidak ada -</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small><i class="bi bi-person me-1"></i><?= htmlspecialchars($row['nama_lengkap'] ?? 'User'); ?></small>
                                </td>
                                <td class="text-center">
                                    <a href="hapus.php?id=<?= $row['id']; ?>"
                                       class="btn btn-sm btn-outline-danger"
                                       title="Hapus Opname"
                                       onclick="return confirm('Apakah Anda yakin ingin menghapus riwayat stok opname ini?');">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                    Belum ada data stok opname.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>

<?php
include '../../templates/footer.php';
?>

