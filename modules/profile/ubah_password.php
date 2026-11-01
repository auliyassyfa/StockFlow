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

// Proses Update Password
if (isset($_POST['update_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Verifikasi password lama
    if (md5($current_password) === $user['password']) {
        // Cek kesesuaian password baru dan konfirmasi
        if ($new_password === $confirm_password) {
            // Hash password baru
            $hashed_password = md5($new_password);

            // Update password di database
            $update = mysqli_query(
                $conn,
                "UPDATE users
                 SET
                    password = '$hashed_password',
                    updated_at = NOW()
                 WHERE id='$user_id'"
            );

            if ($update) {
                $success_message = "Password berhasil diubah.";
            } else {
                $error_message = "Gagal mengubah password.";
            }
        } else {
            $error_message = "Password baru dan konfirmasi password tidak cocok.";
        }
    } else {
        $error_message = "Password lama salah.";
    }
}

include '../../templates/header.php';
include '../../templates/sidebar.php';
?>

    <div class="main-content p-4">

    <div class="card shadow">

        <div class="card-header">
            <h4>Ubah Password</h4>
        </div>

        <div class="card-body">

            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger">
                    <?= $error_message; ?>
                </div>
            <?php endif; ?>

            <?php if (isset($success_message)): ?>
                <div class="alert alert-success">
                    <?= $success_message; ?>
                </div>
            <?php endif; ?>

            <form method="POST">

                <!-- Password Lama -->
                <div class="mb-3">
                    <label class="form-label">Password Lama</label>
                    <input
                        type="password"
                        name="current_password"
                        class="form-control"
                        required>
                </div>

                <!-- Password Baru -->
                <div class="mb-3">
                    <label class="form-label">Password Baru</label>
                    <input
                        type="password"
                        name="new_password"
                        class="form-control"
                        required>
                </div>

                <!-- Konfirmasi Password Baru -->
                <div class="mb-3">
                    <label class="form-label">Konfirmasi Password Baru</label>
                    <input
                        type="password"
                        name="confirm_password"
                        class="form-control"
                        required>
                </div>

                <!-- Tombol -->
                <button
                    type="submit"
                    name="update_password"
                    class="btn btn-warning">
                    Update Password
                </button>

                <a
                    href="profile.php"
                    class="btn btn-secondary">
                    Kembali
                </a>

            </form>

        </div>

    </div>

</div>
<?php include '../../templates/footer.php'; ?>