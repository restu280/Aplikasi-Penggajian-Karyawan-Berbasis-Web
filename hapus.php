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

// Mengambil ID karyawan yang akan dihapus dari parameter URL
if (isset($_GET['id'])) {
    $id_karyawan = $_GET['id'];

    // Menghapus data penggajian terlebih dahulu
$sql_karyawan = "DELETE FROM karyawan WHERE id_karyawan = $id_karyawan";
if ($conn->query($sql_karyawan) === true) {
    // Jika data penggajian berhasil dihapus, lanjutkan ke penghapusan data karyawan
    $sql_penggajian = "DELETE FROM penggajian WHERE id_karyawan = $id_karyawan";
    if ($conn->query($sql_penggajian) === true) {
        // Data karyawan berhasil dihapus
        header("Location: index.php"); // Redirect ke halaman utama
        exit;
    } else {
        echo "Error menghapus data karyawan: " . $conn->error;
    }
} else {
    echo "Error menghapus data penggajian: " . $conn->error;
}

} else {
    echo "ID karyawan tidak ditemukan.";
}

// Tutup koneksi
$conn->close();
?>
