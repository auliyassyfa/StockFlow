<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit;
}

require '../../config/database.php';

// Ambil ID pengguna dari session
$user_id = $_SESSION['user_id'];

// Ambil data pengguna berdasarkan ID
$user = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT * FROM users WHERE id='$user_id'"
    )
);

include '../../templates/header.php';
include '../../templates/sidebar.php';
?>

    <div class="main-content p-4">

    <div class="card shadow">

        <div class="card-header">
            <h4>Profile</h4>
        </div>

        <div class="card-body">

            <p><strong>Nama Lengkap:</strong> <?= $user['nama_lengkap']; ?></p>
            <p><strong>Username:</strong> <?= $user['username']; ?></p>

        </div>

        <div class="card-footer">

            <a href="ubah_password.php?id=<?= $user['id']; ?>"
               class="btn btn-primary">
                Ubah Password
            </a>

        </div>

    </div>

</div>
<?php include '../../templates/footer.php'; ?>