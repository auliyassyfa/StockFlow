<?php

session_start();

if (!isset($_SESSION['user_id'])) {

    header("Location: ../auth/login.php");
    exit;
}

require '../config/database.php';

//Statistik Dashboard

// Total Barang
$totalBarang = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) as total
         FROM barang"
    )
);

// Total Stok
$totalStok = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT SUM(stock) as total
         FROM barang"
    )
);

// Barang Hampir Habis
$hampirHabis = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) as total
         FROM barang
         WHERE stock <= minimum_stock"
    )
);

// Stok Terendah
$stokTerendah = mysqli_query(
    $conn,
    "SELECT *
     FROM barang
     ORDER BY stock ASC
     LIMIT 5"
);

// Stok Tertinggi
$stokTertinggi = mysqli_query(
    $conn,
    "SELECT *
     FROM barang
     ORDER BY stock DESC
     LIMIT 5"
);

// Data Grafik
$chart = mysqli_query(
    $conn,
    "SELECT nama_barang, stock
     FROM barang
     ORDER BY stock DESC
     LIMIT 10"
);

$labels = [];
$data = [];

while ($row = mysqli_fetch_assoc($chart)) {

    $labels[] = $row['nama_barang'];
    $data[] = $row['stock'];
}


include '../templates/header.php';
include '../templates/sidebar.php';
?>

<!-- Content -->

<div class="main-content">

    <!-- Page Header -->
    <div style="margin-bottom: 28px;">
        <h2 style="margin-bottom: 4px;">Dashboard</h2>
        <p style="color: var(--text-muted); font-size: 0.875rem; margin: 0;">
            Selamat datang kembali, <strong style="color: var(--accent-2); -webkit-text-fill-color: unset;"><?= $_SESSION['nama_lengkap']; ?></strong> 👋
        </p>
    </div>

    <!-- Stat Cards -->
    <div class="row g-3" style="margin-bottom: 24px;">

        <!-- Total Barang -->
        <div class="col-md-4">
            <div class="card dashboard-card card-gradient-top">
                <div class="card-body">
                    <div class="stat-icon purple">
                        <i class="bi bi-box-seam-fill"></i>
                    </div>
                    <h6>Total Jenis Barang</h6>
                    <h3><?= $totalBarang['total']; ?></h3>
                    <small style="color: var(--text-muted); font-size: 0.75rem;">Item terdaftar di sistem</small>
                </div>
            </div>
        </div>

        <!-- Total Stok -->
        <div class="col-md-4">
            <div class="card dashboard-card" style="border-top: 2px solid var(--accent-green) !important;">
                <div class="card-body">
                    <div class="stat-icon green">
                        <i class="bi bi-layers-fill"></i>
                    </div>
                    <h6>Total Stok</h6>
                    <h3 style="color: #10b981;"><?= number_format($totalStok['total'] ?? 0); ?></h3>
                    <small style="color: var(--text-muted); font-size: 0.75rem;">Unit tersedia di gudang</small>
                </div>
            </div>
        </div>

        <!-- Barang Hampir Habis -->
        <div class="col-md-4">
            <div class="card dashboard-card" style="border-top: 2px solid var(--accent-red) !important;">
                <div class="card-body">
                    <div class="stat-icon red">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                    <h6>Stok Hampir Habis</h6>
                    <h3 style="color: #ef4444;"><?= $hampirHabis['total']; ?></h3>
                    <small style="color: var(--text-muted); font-size: 0.75rem;">Barang perlu restok segera</small>
                </div>
            </div>
        </div>

    </div>

    <!-- Tables & Chart -->
    <div class="row g-3">

        <!-- Stok Terendah -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header" style="display: flex; align-items: center; gap: 10px;">
                    <span style="width: 8px; height: 8px; background: var(--accent-red); border-radius: 50%; display: inline-block;"></span>
                    <h5 style="margin: 0; font-size: 0.9rem;">5 Stok Terendah</h5>
                </div>
                <div class="card-body" style="padding: 0 !important;">
                    <table class="table" style="margin: 0;">
                        <thead>
                            <tr>
                                <th>Nama Barang</th>
                                <th style="text-align: right;">Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = mysqli_fetch_assoc($stokTerendah)) : ?>
                            <tr>
                                <td><?= htmlspecialchars($row['nama_barang']); ?></td>
                                <td style="text-align: right;">
                                    <span class="badge bg-danger"><?= $row['stock']; ?></span>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Stok Tertinggi -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header" style="display: flex; align-items: center; gap: 10px;">
                    <span style="width: 8px; height: 8px; background: var(--accent-green); border-radius: 50%; display: inline-block;"></span>
                    <h5 style="margin: 0; font-size: 0.9rem;">5 Stok Tertinggi</h5>
                </div>
                <div class="card-body" style="padding: 0 !important;">
                    <table class="table" style="margin: 0;">
                        <thead>
                            <tr>
                                <th>Nama Barang</th>
                                <th style="text-align: right;">Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = mysqli_fetch_assoc($stokTertinggi)) : ?>
                            <tr>
                                <td><?= htmlspecialchars($row['nama_barang']); ?></td>
                                <td style="text-align: right;">
                                    <span class="badge bg-success"><?= $row['stock']; ?></span>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Grafik Stok Barang -->
        <div class="col-md-12">
            <div class="card dashboard-card">
                <div class="card-header">
                    <h5 style="font-size: 0.9rem;">Grafik Stok Barang (Top 10)</h5>
                </div>
                <div class="card-body">
                    <canvas id="stokChart" style="max-height: 300px;"></canvas>
                </div>
            </div>
        </div>

    </div>

</div>

<?php
include '../templates/footer.php';
?>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const ctx = document.getElementById('stokChart').getContext('2d');

Chart.defaults.color = '#475569';
Chart.defaults.font.family = 'Inter, sans-serif';

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= json_encode($labels); ?>,
        datasets: [{
            label: 'Jumlah Stok',
            data: <?= json_encode($data); ?>,
            backgroundColor: 'rgba(108, 99, 255, 0.18)',
            borderColor: 'rgba(108, 99, 255, 0.8)',
            borderWidth: 2,
            borderRadius: 6,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                labels: {
                    color: '#475569',
                    font: { size: 12, weight: '500' }
                }
            },
            tooltip: {
                backgroundColor: '#1e293b',
                titleColor: '#f1f5f9',
                bodyColor: '#94a3b8',
                borderColor: 'rgba(108,99,255,0.3)',
                borderWidth: 1,
                padding: 12,
                cornerRadius: 8,
            }
        },
        scales: {
            x: {
                ticks: { color: '#64748b', font: { size: 11 } },
                grid: { color: '#f1f5f9' }
            },
            y: {
                beginAtZero: true,
                ticks: { color: '#64748b', font: { size: 11 } },
                grid: { color: '#f1f5f9' }
            }
        }
    }
});
</script>
