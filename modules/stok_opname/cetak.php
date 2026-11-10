<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit;
}

require '../../config/database.php';

$tanggal = isset($_GET['tanggal']) ? mysqli_real_escape_string($conn, trim($_GET['tanggal'])) : '';
$cari = isset($_GET['cari']) ? mysqli_real_escape_string($conn, trim($_GET['cari'])) : '';

$where_clauses = [];

if (!empty($cari)) {
    $where_clauses[] = "(b.nama_barang LIKE '%$cari%' OR b.kode_barang LIKE '%$cari%')";
}

if (!empty($tanggal)) {
    $where_clauses[] = "so.tanggal_opname = '$tanggal'";
}

$where_sql = '';
if (count($where_clauses) > 0) {
    $where_sql = "WHERE " . implode(" AND ", $where_clauses);
}

$query = mysqli_query(
    $conn,
    "SELECT
        so.*,
        b.kode_barang,
        b.nama_barang,
        b.satuan,
        u.nama_lengkap
    FROM stok_opname so
    LEFT JOIN barang b
        ON so.barang_id = b.id
    LEFT JOIN users u
        ON so.user_id = u.id
    $where_sql
    ORDER BY so.tanggal_opname DESC, so.id DESC"
);

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan Stok Opname</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0 0 5px 0;
            text-transform: uppercase;
        }
        .header p {
            margin: 0;
            color: #666;
            font-size: 14px;
        }
        .meta-info {
            margin-bottom: 15px;
            font-size: 13px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        th, td {
            border: 1px solid #444;
            padding: 7px 10px;
        }
        th {
            background-color: #f2f2f2;
            text-align: center;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .footer-ttd {
            margin-top: 40px;
            display: flex;
            justify-content: flex-end;
        }
        .ttd-box {
            text-align: center;
            width: 200px;
        }
        .ttd-space {
            height: 60px;
        }
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>

    <div class="no-print" style="margin-bottom: 15px;">
        <button onclick="window.print();" style="padding: 8px 16px; cursor: pointer; background: #0d6efd; color: white; border: none; border-radius: 4px;">
            🖨️ Cetak Dokumen
        </button>
        <button onclick="window.close();" style="padding: 8px 16px; cursor: pointer; background: #6c757d; color: white; border: none; border-radius: 4px; margin-left: 5px;">
            Tutup
        </button>
    </div>

    <div class="header">
        <h2>Laporan Hasil Stok Opname</h2>
        <p>Sistem Informasi Inventory & Persediaan Barang</p>
    </div>

    <div class="meta-info">
        <div><strong>Waktu Cetak:</strong> <?= date('d/m/Y H:i'); ?></div>
        <?php if (!empty($tanggal)) : ?>
            <div><strong>Filter Tanggal Opname:</strong> <?= date('d/m/Y', strtotime($tanggal)); ?></div>
        <?php endif; ?>
    </div>

    <table>
        <thead>
            <tr>
                <th width="30">No</th>
                <th>Tanggal</th>
                <th>Kode</th>
                <th>Nama Barang</th>
                <th>Stok Sistem</th>
                <th>Stok Fisik</th>
                <th>Selisih</th>
                <th>Keterangan</th>
                <th>Petugas</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            if (mysqli_num_rows($query) > 0) :
                while ($row = mysqli_fetch_assoc($query)) :
                    $selisih = (int)$row['selisih'];
                    $satuan = htmlspecialchars($row['satuan'] ?? '');
            ?>
            <tr>
                <td class="text-center"><?= $no++; ?></td>
                <td class="text-center"><?= date('d/m/Y', strtotime($row['tanggal_opname'])); ?></td>
                <td class="text-center"><?= htmlspecialchars($row['kode_barang'] ?? '-'); ?></td>
                <td><?= htmlspecialchars($row['nama_barang'] ?? '-'); ?></td>
                <td class="text-center"><?= number_format($row['stok_sistem']) . ' ' . $satuan; ?></td>
                <td class="text-center"><?= number_format($row['stok_fisik']) . ' ' . $satuan; ?></td>
                <td class="text-center">
                    <?php if ($selisih == 0) : ?>
                        Cocok (0)
                    <?php elseif ($selisih > 0) : ?>
                        +<?= number_format($selisih); ?> (Lebih)
                    <?php else : ?>
                        <?= number_format($selisih); ?> (Kurang)
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($row['keterangan'] ?? '-'); ?></td>
                <td class="text-center"><?= htmlspecialchars($row['nama_lengkap'] ?? '-'); ?></td>
            </tr>
            <?php
                endwhile;
            else :
            ?>
            <tr>
                <td colspan="9" class="text-center">Tidak ada data stok opname.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer-ttd">
        <div class="ttd-box">
            <div>Dicetak Oleh,</div>
            <div class="ttd-space"></div>
            <div><strong>( <?= htmlspecialchars($_SESSION['nama_lengkap'] ?? 'Petugas'); ?> )</strong></div>
        </div>
    </div>

    <script>
        window.print();
    </script>
</body>
</html>

