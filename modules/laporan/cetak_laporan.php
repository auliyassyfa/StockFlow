<?php

require '../../config/database.php';

$tahun = $_GET['tahun'];

$query = mysqli_query(
    $conn,
    "SELECT
        st.*,
        b.nama_barang
    FROM stok_transaksi st
    JOIN barang b
        ON st.barang_id = b.id
    WHERE YEAR(st.tanggal) = '$tahun'
    ORDER BY st.tanggal DESC"
);

?>
<!DOCTYPE html>
<html>

<head>

<title>Cetak Laporan</title>

<style>

table {

    width:100%;
    border-collapse: collapse;

}

th, td {

    border:1px solid black;
    padding:8px;

}

</style>

</head>

<body>

<h2>

Laporan Stok Barang Tahun <?= $tahun; ?>

</h2>

<table>

<tr>

<th>No</th>
<th>Tanggal</th>
<th>Barang</th>
<th>Jenis</th>
<th>Jumlah</th>

</tr>

<?php

$no = 1;

while($row = mysqli_fetch_assoc($query)) :

?>

<tr>

<td><?= $no++; ?></td>

<td><?= $row['tanggal']; ?></td>

<td><?= $row['nama_barang']; ?></td>

<td><?= ucfirst($row['tipe_transaksi']); ?></td>

<td><?= $row['jumlah']; ?></td>

</tr>

<?php endwhile; ?>

</table>

<script>

window.print();

</script>

</body>
</html>