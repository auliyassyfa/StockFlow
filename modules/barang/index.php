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

    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Daftar Barang</h2>
            <p class="text-muted mb-0">Kelola informasi seluruh stok dan persediaan barang</p>
        </div>

        <a href="tambah.php" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>
            Tambah Barang
        </a>
    </div>

    <!-- Data Table Card -->
    <div class="card shadow border-0">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" width="50">No</th>
                            <th width="120">Kode</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th class="text-center" width="90">Satuan</th>
                            <th class="text-center" width="90">Stok</th>
                            <th class="text-center" width="130">Status</th>
                            <th class="text-center" width="160">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        if (mysqli_num_rows($query) > 0) :
                            while ($barang = mysqli_fetch_assoc($query)) :
                        ?>
                        <tr>
                            <td class="text-center"><?= $no++; ?></td>
                            <td><span class="badge bg-secondary"><?= htmlspecialchars($barang['kode_barang']); ?></span></td>
                            <td class="fw-semibold"><?= htmlspecialchars($barang['nama_barang']); ?></td>
                            <td><?= htmlspecialchars($barang['nama_kategori'] ?? '-'); ?></td>
                            <td class="text-center"><?= htmlspecialchars($barang['satuan']); ?></td>
                            <td class="text-center fw-bold"><?= number_format($barang['stock']); ?></td>
                            <td class="text-center">
                                <?php if ($barang['stock'] > 0) : ?>
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle me-1"></i>Tersedia
                                    </span>
                                <?php else : ?>
                                    <span class="badge bg-danger">
                                        <i class="bi bi-x-circle me-1"></i>Habis
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="d-flex gap-1 justify-content-center">
                                    <a href="edit.php?id=<?= $barang['id']; ?>" class="btn btn-warning btn-sm" title="Edit Barang">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    <a href="hapus.php?id=<?= $barang['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus barang ini?')" title="Hapus Barang">
                                        <i class="bi bi-trash"></i> Hapus
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php
                            endwhile;
                        else :
                        ?>
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="bi bi-box-seam fs-2 d-block mb-2"></i>
                                Belum ada data barang.
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
