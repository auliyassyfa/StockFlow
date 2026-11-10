<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit;
}

require '../../config/database.php';

$query = mysqli_query(
    $conn,
    "SELECT * FROM users
     ORDER BY id DESC"
);

include '../../templates/header.php';
include '../../templates/sidebar.php';

?>

<div class="main-content p-4">

    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Manajemen Pengguna</h2>
            <p class="text-muted mb-0">Kelola akun dan hak akses pengguna sistem inventory</p>
        </div>

        <a href="tambah.php" class="btn btn-primary">
            <i class="bi bi-person-plus me-1"></i>
            Tambah User
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
                            <th>Nama Lengkap</th>
                            <th width="200">Username</th>
                            <th class="text-center" width="220">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        if (mysqli_num_rows($query) > 0) :
                            while ($user = mysqli_fetch_assoc($query)) :
                        ?>
                        <tr>
                            <td class="text-center"><?= $no++; ?></td>
                            <td class="fw-semibold">
                                <i class="bi bi-person-circle me-1 text-primary"></i>
                                <?= htmlspecialchars($user['nama_lengkap']); ?>
                            </td>
                            <td><code>@<?= htmlspecialchars($user['username']); ?></code></td>
                            <td class="text-center">
                                <div class="d-flex gap-1 justify-content-center">
                                    <a href="detail.php?id=<?= $user['id']; ?>" class="btn btn-secondary btn-sm" title="Lihat Detail">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                    <a href="edit.php?id=<?= $user['id']; ?>" class="btn btn-warning btn-sm" title="Edit User">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    <a href="hapus.php?id=<?= $user['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus user ini?')" title="Hapus User">
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
                                <i class="bi bi-people fs-2 d-block mb-2"></i>
                                Belum ada data pengguna.
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
