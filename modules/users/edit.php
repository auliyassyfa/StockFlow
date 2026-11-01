<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit;
}

require '../../config/database.php';

// Ambil ID dari URL
$id = $_GET['id'];

// Ambil data user berdasarkan ID
$user = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT * FROM users WHERE id='$id'"
    )
);

// Proses Update
if (isset($_POST['update'])) {
    $nama_lengkap = $_POST['nama_lengkap'];
    $username     = $_POST['username'];

    $update = mysqli_query(
        $conn,
        "UPDATE users
         SET
            nama_lengkap = '$nama_lengkap',
            username     = '$username',
            updated_at   = NOW()
         WHERE id='$id'"
    );

    if ($update) {
        header("Location: index.php");
        exit;
    }
}

include '../../templates/header.php';
include '../../templates/sidebar.php';
?>

    <div class="main-content p-4">

    <div class="card shadow">

        <div class="card-header">
            <h4>Edit User</h4>
        </div>

        <div class="card-body">

            <form method="POST">

                <!-- Nama Lengkap -->
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input
                        type="text"
                        name="nama_lengkap"
                        class="form-control"
                        value="<?= $user['nama_lengkap']; ?>"
                        required>
                </div>

                <!-- Username -->
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input
                        type="text"
                        name="username"
                        class="form-control"
                        value="<?= $user['username']; ?>"
                        required>
                </div>

                <!-- Tombol -->
                <button
                    type="submit"
                    name="update"
                    class="btn btn-warning">
                    Update
                </button>

                <a
                    href="index.php"
                    class="btn btn-secondary">
                    Kembali
                </a>

            </form>

        </div>

    </div>

</div>
<?php include '../../templates/footer.php'; ?>