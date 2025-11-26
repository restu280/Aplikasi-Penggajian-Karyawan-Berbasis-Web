<?php
session_start();
if (!isset($_SESSION['session_username'])) {
    header("location: login.php");
    exit();
}

// Fungsi untuk membuat koneksi ke database
function connectToDatabase()
{
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "gajian";
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    return $conn;
}

// Membuat koneksi ke database
$conn = connectToDatabase();

// Mengambil id_karyawan, tanggal, dan jenis_kehadiran dari formulir
$id_karyawan = $_POST['id_karyawan'];
$tanggal = $_POST['tanggal'];
$jenis_kehadiran = $_POST['jenis_kehadiran'];

// Mengecek apakah entri dengan karyawan dan tanggal yang sama sudah ada
$sql_cek_absensi = "SELECT id FROM kehadiran WHERE id_karyawan = '$id_karyawan' AND tanggal = '$tanggal'";
$result_cek_absensi = $conn->query($sql_cek_absensi);

if ($result_cek_absensi->num_rows > 0) {
    // Jika entri sudah ada, update jenis kehadiran yang sudah ada
    $sql_update_kehadiran = "UPDATE kehadiran SET jenis_kehadiran = '$jenis_kehadiran' WHERE id_karyawan = '$id_karyawan' AND tanggal = '$tanggal'";

    if ($conn->query($sql_update_kehadiran) === TRUE) {
        header("Location: kehadiran.php");
        exit;
    } else {
        echo "Error: " . $sql_update_kehadiran . "<br>" . $conn->error;
    }
} else {
    // Jika entri belum ada, Anda dapat menyimpan entri kehadiran baru seperti sebelumnya
    // Mengambil nama karyawan berdasarkan id_karyawan yang dipilih
    $sql_get_nama = "SELECT nama_karyawan FROM karyawan WHERE id_karyawan = '$id_karyawan'";
    $result_nama = $conn->query($sql_get_nama);

    if ($result_nama->num_rows > 0) {
        $row = $result_nama->fetch_assoc();
        $nama_karyawan = $row['nama_karyawan'];

        // Simpan data kehadiran ke dalam tabel kehadiran
        $sql = "INSERT INTO kehadiran (id_karyawan, nama_karyawan, jenis_kehadiran, tanggal) 
            VALUES ('$id_karyawan', '$nama_karyawan', '$jenis_kehadiran', '$tanggal')";

        if ($conn->query($sql) === TRUE) {
            header("Location: kehadiran.php");
            exit;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Nama karyawan tidak ditemukan.";
    }
}

// Tutup koneksi
$conn->close();

?>
