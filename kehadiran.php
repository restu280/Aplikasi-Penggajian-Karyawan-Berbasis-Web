<?php
session_start();
if (!isset($_SESSION['session_username'])) {
    header("location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Aplikasi Penggajian Karyawan</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        body {
            background-image: url('assets/images/forest.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
    </style>
</head>

<body>
    <div class="navbar">
        <a href="index.html">Kembali</a>
    </div>

    <h1>WARUNG PECEL SAMBEL DOBLEH</h1>
    <div class="container">
        <h2>Tambah Data Kehadiran Karyawan</h2>
        <!-- Form tambah data kehadiran -->
        <form action="proses_kehadiran.php" method="POST">
            <!-- Dropdown list untuk memilih karyawan -->
            <select name="id_karyawan" required>
                <option value="">Pilih Karyawan</option>
                <?php
                // Membuat koneksi ke database (gunakan koneksi Anda sendiri)
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "gajian";
                $conn = new mysqli($servername, $username, $password, $dbname);

                // Memeriksa koneksi
                if ($conn->connect_error) {
                    die("Koneksi gagal: " . $conn->connect_error);
                }

                // Query untuk mengambil data karyawan yang sudah ditambahkan
                $sql = "SELECT id_karyawan, nama_karyawan FROM karyawan";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id_karyawan'] . "'>" . $row['id_karyawan'] . " - " . $row['nama_karyawan'] . "</option>";
                    }
                }
                ?>
            </select>
            <input type="date" name="tanggal" required>
            <input type="text" name="jenis_kehadiran" placeholder="Keterangan" required>
            <!-- Submit button -->
            <button type="submit">Tambah Kehadiran</button>
        </form>
        <br>

        <!-- Tabel untuk menampilkan status kehadiran dan tanggal karyawan -->
        <h2>Status Kehadiran Karyawan</h2>
        <button type="button" onclick="konfirmasiHapus()">Hapus Semua Data Kehadiran
            <script>
                function konfirmasiHapus() {
                    var konfirmasi = confirm("Apakah Anda yakin ingin menghapus semua data kehadiran?");
                    if (konfirmasi) {
                        // Jika pengguna mengonfirmasi, arahkan ke halaman yang akan menghapus data
                        window.location.href = "hapus_kehadiran.php";
                    }
                }
            </script>
        </button>

        <?php
        // Query untuk mengambil data kehadiran karyawan
        $sql_kehadiran = "SELECT id_karyawan, nama_karyawan, jenis_kehadiran, tanggal FROM kehadiran ORDER BY tanggal";
        $result_kehadiran = $conn->query($sql_kehadiran);

        $current_date = null; // Untuk melacak tanggal saat ini

        if ($result_kehadiran->num_rows > 0) {
            while ($row_kehadiran = $result_kehadiran->fetch_assoc()) {
                $tanggal = $row_kehadiran['tanggal'];

                // Jika tanggal berubah, buat tabel baru
                if ($tanggal != $current_date) {
                    // Menampilkan tanggal sebagai judul tabel
                    echo "<table>";
                    echo "<tr><th>No.Induk</th><th>Nama Karyawan</th><th>Kehadiran</th><th>Tanggal</th></tr>";
                    $current_date = $tanggal;
                }

                // Menampilkan data kehadiran
                echo "<tr>";
                echo "<td>" . $row_kehadiran['id_karyawan'] . "</td>";
                echo "<td>" . $row_kehadiran['nama_karyawan'] . "</td>";
                echo "<td>" . $row_kehadiran['jenis_kehadiran'] . "</td>";
                echo "<td>" . $tanggal . "</td>";
                echo "</tr>";
            }

            // Menutup tabel terakhir
            echo "</table>";
        }
        ?>

    </div>
</body>

</html>
