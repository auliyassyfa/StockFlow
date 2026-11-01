<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit;
}

require '../../config/database.php';

if (isset($_POST['simpan'])) {

    $nama_lengkap = $_POST['nama_lengkap'];
    $username = $_POST['username'];

    // Password di-hash demi keamanan
    $password = md5($_POST['password']);

    mysqli_query(
        $conn,
        "INSERT INTO users
        (
            nama_lengkap,
            username,
            password
        )
        VALUES
        (
            '$nama_lengkap',
            '$username',
            '$password'
        )"
    );

    header("Location: index.php");
    exit;
}


include '../../templates/header.php';
include '../../templates/sidebar.php';
?>

<div class="main-content p-4">

    <div class="card shadow">

        <div class="card-header">
            <h4>Tambah User</h4>
        </div>

        <div class="card-body">

            <form method="POST">

                <!-- Nama Lengkap -->

                <div class="mb-3">

                    <label class="form-label">
                        Nama Lengkap
                    </label>

                    <input
                        type="text"
                        name="nama_lengkap"
                        class="form-control"
                        required>

                </div>

                <!-- Username -->

                <div class="mb-3">

                    <label class="form-label">
                        Username
                    </label>

                    <input
                        type="text"
                        name="username"
                        class="form-control"
                        required>

                </div>

                <!-- Password -->

                <div class="mb-3">

                    <label class="form-label">
                        Password
                    </label>

                    <input
                        type="password"
                        name="password"
                        class="form-control"
                        required>

                </div>

                <button
                    type="submit"
                    name="simpan"
                    class="btn btn-success">

                    Simpan

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