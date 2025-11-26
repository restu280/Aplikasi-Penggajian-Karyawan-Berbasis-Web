<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gajian";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Mengambil data karyawan dan penggajian dengan JOIN
$sql = "SELECT karyawan.id_karyawan, karyawan.nama_karyawan, karyawan.jabatan, penggajian.gaji_pokok
        FROM karyawan
        JOIN penggajian ON karyawan.id_karyawan = penggajian.id_karyawan";



$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Menampilkan data karyawan dan penggajian
    while ($row = $result->fetch_assoc()) {
        $gaji_pokok = $row['gaji_pokok'];
        $total_gaji = 0;

        // Menghitung PPH
        $pphPercentage = 0.05;
        $pph = $gaji_pokok * $pphPercentage;

        // Menghitung total gaji
        $total_gaji = $gaji_pokok - $pph;

        echo "<tr>";
        echo "<td>" . $row['id_karyawan'] . "</td>";
        echo "<td>" . $row['nama_karyawan'] . "</td>";
        echo "<td>" . $row['jabatan'] . "</td>";
        echo "<td>" . $gaji_pokok . "</td>";
        echo "<td>" . $pph . "</td>";
        echo "<td>" . $total_gaji . "</td>";
        echo "<td>";
        echo "<a href='detail.php?id=" . $row['id_karyawan'] . "' class='btn btn-info'><i class='fas fa-info-circle'></i>         </a>";
        echo "<a href='edit.php?id=" . $row['id_karyawan'] . "' class='btn btn-warning'><i class='fas fa-edit'></i>        </a>";
        echo "<a href='hapus.php?id=" . $row['id_karyawan'] . "' class='btn btn-danger' onclick=\"return confirm('Apakah Anda yakin ingin menghapus?')\"><i class='fas fa-trash-alt'></i></a>";

        echo "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='5'>Tidak ada data karyawan.</td></tr>";
}

// ...
?>
<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

</head>
<body>
    
</body>
</html>
