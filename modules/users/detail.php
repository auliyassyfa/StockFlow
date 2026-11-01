<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit;
}

require '../../config/database.php';

$id = $_GET['id'];

$user = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT *
         FROM users
         WHERE id='$id'"
    )
);

include '../../templates/header.php';
include '../../templates/sidebar.php';

?>

<div class="main-content p-4">

    <div class="card shadow">

        <div class="card-header">
            <h4>Detail User</h4>
        </div>

        <div class="card-body">

            <table class="table">

                <tr>
                    <th>Nama Lengkap</th>
                    <td><?= $user['nama_lengkap']; ?></td>
                </tr>

                <tr>
                    <th>Username</th>
                    <td><?= $user['username']; ?></td>
                </tr>

                <tr>
                    <th>Dibuat</th>
                    <td><?= $user['created_at']; ?></td>
                </tr>

            </table>

            <a href="index.php"
               class="btn btn-secondary">
                Kembali
            </a>

        </div>

    </div>

</div>

<?php include '../../templates/footer.php'; ?>