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

    <div class="card shadow">

        <div class="card-header d-flex justify-content-between">

            <h4>Manajemen Pengguna</h4>

            <a href="tambah.php" class="btn btn-primary">
                Tambah User
            </a>

        </div>

        <div class="card-body">

            <table class="table table-bordered">

                <thead>

                    <tr>
                        <th>No</th>
                        <th>Nama Lengkap</th>
                        <th>Username</th>
                        <th>Aksi</th>
                    </tr>

                </thead>

                <tbody>

                <?php
                $no = 1;

                while($user = mysqli_fetch_assoc($query)) :
                ?>

                    <tr>

                        <td><?= $no++; ?></td>

                        <td><?= $user['nama_lengkap']; ?></td>

                        <td><?= $user['username']; ?></td>

                        <td>

                            <a href="detail.php?id=<?= $user['id']; ?>"
                               class="btn btn-info btn-sm">
                               Detail
                            </a>

                            <a href="edit.php?id=<?= $user['id']; ?>"
                               class="btn btn-warning btn-sm">
                               Edit
                            </a>

                            <a href="hapus.php?id=<?= $user['id']; ?>"
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Yakin hapus user?')">
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

<?php include '../../templates/footer.php'; ?>