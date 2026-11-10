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

    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Kategori Barang</h2>
            <p class="text-muted mb-0">Kelola kelompok dan klasifikasi produk persediaan</p>
        </div>

        <a href="tambah.php" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>
            Tambah Kategori
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
                            <th width="240">Nama Kategori</th>
                            <th>Deskripsi</th>
                            <th class="text-center" width="160">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        if (mysqli_num_rows($query) > 0) :
                            while ($kategori = mysqli_fetch_assoc($query)) :
                        ?>
                        <tr>
                            <td class="text-center"><?= $no++; ?></td>
                            <td class="fw-semibold">
                                <i class="bi bi-tag me-1 text-primary"></i>
                                <?= htmlspecialchars($kategori['nama']); ?>
                            </td>
                            <td>
                                <?= !empty($kategori['deskripsi']) ? nl2br(htmlspecialchars($kategori['deskripsi'])) : '<span class="text-muted fst-italic">- Tidak ada deskripsi -</span>'; ?>
                            </td>
                            <td class="text-center">
                                <div class="d-flex gap-1 justify-content-center">
                                    <a href="edit.php?id=<?= $kategori['id']; ?>" class="btn btn-warning btn-sm" title="Edit Kategori">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    <a href="hapus.php?id=<?= $kategori['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus kategori ini?')" title="Hapus Kategori">
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
                            <td colspan="4" class="text-center py-4 text-muted">
                                <i class="bi bi-tags fs-2 d-block mb-2"></i>
                                Belum ada data kategori.
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
