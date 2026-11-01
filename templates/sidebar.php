<?php
$current_script = $_SERVER['SCRIPT_NAME'];

function isActive($pattern) {
    global $current_script;
    return strpos($current_script, $pattern) !== false ? 'active' : '';
}
?>
<div class="d-flex">

    <!-- Sidebar -->
    <div class="sidebar">

        <!-- Brand -->
        <div class="sidebar-brand">
            <h4>StockFlow</h4>
        </div>

        <!-- Navigation -->
        <ul class="nav flex-column">

            <!-- Dashboard -->
            <li class="nav-item">
                <a href="<?= $pathToRoot; ?>dashboard/index.php"
                   class="nav-link <?= isActive('/dashboard/') ?>">
                    <i class="bi bi-speedometer2"></i>
                    Dashboard
                </a>
            </li>

            <!-- Persediaan -->
            <li class="nav-section">Persediaan Barang</li>

            <li class="nav-item">
                <a href="<?= $pathToRoot; ?>modules/barang/index.php"
                   class="nav-link <?= isActive('/modules/barang/') ?>">
                    <i class="bi bi-box-seam"></i>
                    Daftar Barang
                </a>
            </li>

            <li class="nav-item">
                <a href="<?= $pathToRoot; ?>modules/transaksi/transaksi_masuk.php"
                   class="nav-link <?= isActive('/modules/transaksi/transaksi_masuk.php') ?>">
                    <i class="bi bi-box-arrow-in-down"></i>
                    Barang Masuk
                </a>
            </li>

            <li class="nav-item">
                <a href="<?= $pathToRoot; ?>modules/transaksi/transaksi_keluar.php"
                   class="nav-link <?= isActive('/modules/transaksi/transaksi_keluar.php') ?>">
                    <i class="bi bi-box-arrow-up"></i>
                    Barang Keluar
                </a>
            </li>

            <li class="nav-item">
                <a href="<?= $pathToRoot; ?>modules/transaksi/riwayat_transaksi.php"
                   class="nav-link <?= isActive('/modules/transaksi/riwayat_transaksi.php') ?>">
                    <i class="bi bi-clock-history"></i>
                    Riwayat Transaksi
                </a>
            </li>

            <!-- Master Data -->
            <li class="nav-section">Master Data</li>

            <li class="nav-item">
                <a href="<?= $pathToRoot; ?>modules/kategori/index.php"
                   class="nav-link <?= isActive('/modules/kategori/') ?>">
                    <i class="bi bi-tags"></i>
                    Kategori Barang
                </a>
            </li>

            <li class="nav-item">
                <a href="<?= $pathToRoot; ?>modules/users/index.php"
                   class="nav-link <?= isActive('/modules/users/') ?>">
                    <i class="bi bi-people"></i>
                    Manajemen User
                </a>
            </li>

            <!-- Laporan -->
            <li class="nav-section">Laporan</li>

            <li class="nav-item">
                <a href="<?= $pathToRoot; ?>modules/laporan/index.php"
                   class="nav-link <?= isActive('/modules/laporan/') ?>">
                    <i class="bi bi-file-earmark-bar-graph"></i>
                    Laporan
                </a>
            </li>

            <!-- Profil & Logout -->
            <li class="nav-section">Akun</li>

            <li class="nav-item">
                <a href="<?= $pathToRoot; ?>modules/profile/profile.php"
                   class="nav-link <?= isActive('/modules/profile/') ?>">
                    <i class="bi bi-person-circle"></i>
                    Profil Saya
                </a>
            </li>

            <li class="nav-item" style="margin-top: 8px;">
                <a href="<?= $pathToRoot; ?>auth/logout.php"
                   class="nav-link text-danger">
                    <i class="bi bi-box-arrow-right"></i>
                    Logout
                </a>
            </li>

        </ul>

    </div>