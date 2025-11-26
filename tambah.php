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

// Mengambil data dari form tambah karyawan
$no_induk = $_POST['id_karyawan'];
$nama_karyawan = $_POST['nama_karyawan'];
$jabatan = $_POST['jabatan'];
$gaji_pokok = $_POST['gaji_pokok'];

// Memeriksa apakah ID memiliki 8 angka
if (strlen($no_induk) !== 8 || !is_numeric($no_induk)) {
    echo "<script>alert('ID harus terdiri dari 8 angka.')</script>";
    echo "<script>window.history.back();</script>";
    exit;
}

// Menghitung PPH
$pphPercentage = 0.05;
$pph = $gaji_pokok * $pphPercentage;

// Menghitung total gaji
$total_gaji = $gaji_pokok - $pph;

// Memeriksa apakah ID sudah ada dalam tabel 'karyawan'
$checkIdQuery = "SELECT * FROM karyawan WHERE id_karyawan = '$no_induk'";
$checkIdResult = $conn->query($checkIdQuery);

if ($checkIdResult->num_rows > 0) {
    echo "<script>alert('ID sudah digunakan. Silakan gunakan ID lain.')</script>";
    echo "<script>window.history.back();</script>";
    exit;
}


// Menambahkan data penggajian ke tabel 'penggajian'
$sql_penggajian = "INSERT INTO penggajian (id_karyawan, gaji_pokok, pph, total_gaji) VALUES ('$no_induk', '$gaji_pokok', '$pph', '$total_gaji')";

if ($conn->query($sql_penggajian) === true) {
    // Data penggajian berhasil ditambahkan
    // Anda dapat memasukkan data karyawan ke dalam tabel 'karyawan' terlebih dahulu

    $sql_karyawan = "INSERT INTO karyawan (id_karyawan, nama_karyawan, jabatan) VALUES ('$no_induk', '$nama_karyawan', '$jabatan')";

    if ($conn->query($sql_karyawan) === true) {
        // Data karyawan berhasil ditambahkan
        // Redirect ke halaman index.php setelah semua data berhasil ditambahkan
        header("Location: index.php");
        exit;
        } else {
            echo "Error: " . $sql_karyawan . "<br>" . $conn->error;
        }
    } else {
        echo "Error: " . $sql_penggajian . "<br>" . $conn->error;
    }

// Menutup koneksi
$conn->close();

?>
