<?php

session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit;
}

require '../../config/database.php';

/*
|--------------------------------------------------------------------------
| Ambil Data Barang
|--------------------------------------------------------------------------
*/
$barang_query = mysqli_query(
    $conn,
    "SELECT id, kode_barang, nama_barang, stock, satuan
     FROM barang
     ORDER BY nama_barang ASC"
);

$error_msg = '';

/*
|--------------------------------------------------------------------------
| Proses Simpan Stok Opname
|--------------------------------------------------------------------------
*/
if (isset($_POST['simpan'])) {

    $barang_id      = (int)$_POST['barang_id'];
    $tanggal_opname = mysqli_real_escape_string($conn, $_POST['tanggal_opname']);
    $stok_fisik     = (int)$_POST['stok_fisik'];
    $keterangan     = mysqli_real_escape_string($conn, trim($_POST['keterangan'] ?? ''));
    $update_stok    = isset($_POST['update_stok_master']) ? 1 : 0;
    $user_id        = (int)$_SESSION['user_id'];

    if (empty($barang_id) || empty($tanggal_opname) || $stok_fisik < 0) {
        $error_msg = "Harap lengkapi semua data wajib dengan benar!";
    } else {
        // Ambil stok sistem terkini dari database untuk konsistensi
        $check_barang = mysqli_query($conn, "SELECT stock FROM barang WHERE id = '$barang_id'");
        $b_data = mysqli_fetch_assoc($check_barang);

        if (!$b_data) {
            $error_msg = "Barang yang dipilih tidak ditemukan!";
        } else {
            $stok_sistem = (int)$b_data['stock'];
            $selisih = $stok_fisik - $stok_sistem;

            // Simpan ke tabel stok_opname
            $insert_query = "INSERT INTO stok_opname (
                barang_id,
                user_id,
                tanggal_opname,
                stok_sistem,
                stok_fisik,
                selisih,
                keterangan,
                created_at
            ) VALUES (
                '$barang_id',
                '$user_id',
                '$tanggal_opname',
                '$stok_sistem',
                '$stok_fisik',
                '$selisih',
                '$keterangan',
                NOW()
            )";

            if (mysqli_query($conn, $insert_query)) {

                // Jika dicentang, perbarui stok di tabel barang sesuai stok fisik
                if ($update_stok == 1) {
                    mysqli_query(
                        $conn,
                        "UPDATE barang
                         SET stock = '$stok_fisik'
                         WHERE id = '$barang_id'"
                    );
                }

                header("Location: index.php?status=success");
                exit;
            } else {
                $error_msg = "Gagal menyimpan data stok opname: " . mysqli_error($conn);
            }
        }
    }
}

include '../../templates/header.php';
include '../../templates/sidebar.php';
?>

<div class="main-content p-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Tambah Stok Opname</h2>
            <p class="text-muted mb-0">Input hasil pengecekan fisik barang gudang</p>
        </div>

        <a href="index.php" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <?php if (!empty($error_msg)) : ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <?= $error_msg; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow border-0">
        <div class="card-body p-4">

            <form method="POST" id="formOpname">

                <div class="row">
                    <!-- Tanggal Opname -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">
                            Tanggal Opname <span class="text-danger">*</span>
                        </label>
                        <input
                            type="date"
                            name="tanggal_opname"
                            class="form-control"
                            value="<?= date('Y-m-d'); ?>"
                            required>
                    </div>

                    <!-- Petugas / User -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Petugas Pemeriksa</label>
                        <input
                            type="text"
                            class="form-control bg-light"
                            value="<?= htmlspecialchars($_SESSION['nama_lengkap'] ?? $_SESSION['username']); ?>"
                            readonly>
                    </div>
                </div>

                <!-- Pilih Barang -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Pilih Barang <span class="text-danger">*</span>
                    </label>
                    <select
                        name="barang_id"
                        id="selectBarang"
                        class="form-select"
                        required>
                        <option value="">-- Pilih Barang yang Diperiksa --</option>
                        <?php while ($b = mysqli_fetch_assoc($barang_query)) : ?>
                            <option
                                value="<?= $b['id']; ?>"
                                data-stock="<?= (int)$b['stock']; ?>"
                                data-satuan="<?= htmlspecialchars($b['satuan'] ?? ''); ?>">
                                [<?= htmlspecialchars($b['kode_barang']); ?>] <?= htmlspecialchars($b['nama_barang']); ?> (Stok Sistem: <?= $b['stock']; ?> <?= htmlspecialchars($b['satuan']); ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="row">
                    <!-- Stok di Sistem -->
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Stok di Sistem</label>
                        <div class="input-group">
                            <input
                                type="number"
                                id="stokSistemDisplay"
                                class="form-control bg-light fw-bold"
                                value="0"
                                readonly>
                            <span class="input-group-text satuan-badge">-</span>
                        </div>
                        <small class="text-muted">Jumlah tercatat di database saat ini</small>
                    </div>

                    <!-- Stok Fisik Nyata -->
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">
                            Stok Fisik Nyata <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input
                                type="number"
                                name="stok_fisik"
                                id="stokFisikInput"
                                class="form-control fw-bold"
                                min="0"
                                placeholder="Masukkan hitungan fisik"
                                required>
                            <span class="input-group-text satuan-badge">-</span>
                        </div>
                        <small class="text-muted">Jumlah riil hasil hitungan di gudang</small>
                    </div>

                    <!-- Selisih Terhitung -->
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Selisih (Fisik - Sistem)</label>
                        <div class="input-group">
                            <input
                                type="text"
                                id="selisihDisplay"
                                class="form-control fw-bold bg-light"
                                value="0"
                                readonly>
                            <span class="input-group-text satuan-badge">-</span>
                        </div>
                        <div id="selisihStatus" class="mt-1 small fw-semibold text-secondary">
                            Pilih barang dan masukkan stok fisik
                        </div>
                    </div>
                </div>

                <!-- Opsi Update Stok Master -->
                <div class="p-3 mb-3 rounded-3 bg-light border">
                    <div class="form-check form-switch">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            role="switch"
                            name="update_stok_master"
                            id="updateStokMaster"
                            value="1"
                            checked>
                        <label class="form-check-label fw-semibold" for="updateStokMaster">
                            Sesuaikan Stok Sistem dengan Stok Fisik
                        </label>
                    </div>
                    <small class="text-muted d-block ms-4">
                        Jika diaktifkan, jumlah stok di master data barang akan otomatis disesuaikan dengan stok fisik yang diinputkan.
                    </small>
                </div>

                <!-- Keterangan -->
                <div class="mb-4">
                    <label class="form-label fw-semibold">Keterangan / Alasan Selisih</label>
                    <textarea
                        name="keterangan"
                        class="form-control"
                        rows="3"
                        placeholder="Contoh: 2 barang rusak karena kemasan bocor, 1 barang salah hitung, atau stok cocok."></textarea>
                </div>

                <!-- Tombol Aksi -->
                <div class="d-flex gap-2">
                    <button
                        type="submit"
                        name="simpan"
                        class="btn btn-primary px-4">
                        <i class="bi bi-save me-1"></i> Simpan Stok Opname
                    </button>
                    <a href="index.php" class="btn btn-outline-secondary">
                        Batal
                    </a>
                </div>

            </form>

        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectBarang = document.getElementById('selectBarang');
    const stokSistemDisplay = document.getElementById('stokSistemDisplay');
    const stokFisikInput = document.getElementById('stokFisikInput');
    const selisihDisplay = document.getElementById('selisihDisplay');
    const selisihStatus = document.getElementById('selisihStatus');
    const satuanBadges = document.querySelectorAll('.satuan-badge');

    function hitungSelisih() {
        const selectedOption = selectBarang.options[selectBarang.selectedIndex];
        if (!selectedOption || !selectedOption.value) {
            stokSistemDisplay.value = 0;
            selisihDisplay.value = 0;
            selisihStatus.className = 'mt-1 small fw-semibold text-secondary';
            selisihStatus.innerText = 'Pilih barang terlebih dahulu';
            satuanBadges.forEach(b => b.innerText = '-');
            return;
        }

        const stokSistem = parseInt(selectedOption.getAttribute('data-stock')) || 0;
        const satuan = selectedOption.getAttribute('data-satuan') || '';
        stokSistemDisplay.value = stokSistem;

        satuanBadges.forEach(b => b.innerText = satuan ? satuan : '-');

        if (stokFisikInput.value === '') {
            selisihDisplay.value = '-';
            selisihStatus.className = 'mt-1 small fw-semibold text-secondary';
            selisihStatus.innerText = 'Masukkan stok fisik';
            return;
        }

        const stokFisik = parseInt(stokFisikInput.value) || 0;
        const selisih = stokFisik - stokSistem;

        if (selisih === 0) {
            selisihDisplay.value = '0';
            selisihDisplay.style.color = '#10b981';
            selisihStatus.className = 'mt-1 small fw-semibold text-success';
            selisihStatus.innerHTML = '<i class="bi bi-check-circle-fill"></i> Stok Sesuai / Cocok';
        } else if (selisih > 0) {
            selisihDisplay.value = '+' + selisih;
            selisihDisplay.style.color = '#f59e0b';
            selisihStatus.className = 'mt-1 small fw-semibold text-warning';
            selisihStatus.innerHTML = '<i class="bi bi-arrow-up-circle-fill"></i> Stok Fisik Lebih Banyak (+ ' + selisih + ')';
        } else {
            selisihDisplay.value = selisih;
            selisihDisplay.style.color = '#ef4444';
            selisihStatus.className = 'mt-1 small fw-semibold text-danger';
            selisihStatus.innerHTML = '<i class="bi bi-arrow-down-circle-fill"></i> Stok Fisik Kurang (' + selisih + ')';
        }
    }

    selectBarang.addEventListener('change', hitungSelisih);
    stokFisikInput.addEventListener('input', hitungSelisih);
});
</script>

<?php
include '../../templates/footer.php';
?>

