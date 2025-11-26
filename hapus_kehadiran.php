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

// Query untuk menghapus semua data kehadiran
$sql_hapus_kehadiran = "DELETE FROM kehadiran";

if ($conn->query($sql_hapus_kehadiran) === TRUE) {
    header("Location: kehadiran.php");
        exit;
} else {
    echo "Error: " . $sql_hapus_kehadiran . "<br>" . $conn->error;
}

// Tutup koneksi
$conn->close();
?>
