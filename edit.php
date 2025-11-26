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

// Mengambil ID karyawan dari parameter URL
$no_induk = $_GET['id'];

// Mengambil data karyawan berdasarkan ID
$query = "SELECT k.*, p.gaji_pokok, p.pph, p.total_gaji
            FROM karyawan k
            LEFT JOIN penggajian p ON k.id_karyawan = p.id_karyawan
            WHERE k.id_karyawan = '$no_induk'";

        $result = $conn->query($query);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $nama_karyawan = $row['nama_karyawan'];
    $position = $row['jabatan'];
    $salary = $row['gaji_pokok'];
} else {
    echo "Data karyawan tidak ditemukan.";
    exit;
}

if (isset($_POST['simpan'])) {
    $newnama = $_POST['nama_karyawan'];
    $newjabatan = $_POST['jabatan'];
    $newgaji = $_POST['gaji_pokok'];

    // Menghitung ulang PPH sesuai dengan gaji pokok yang baru diubah
    $pphPercentage = 0.05;
    $pph = $newgaji * $pphPercentage;

    // Menghitung ulang total gaji dengan mengurangkan PPH dari gaji pokok
    $totalGaji = $newgaji - $pph;

    // Mengupdate data karyawan dan penggajian berdasarkan ID
    $updateKaryawanSql = "UPDATE karyawan SET nama_karyawan = '$newnama', jabatan = '$newjabatan' WHERE id_karyawan = $no_induk";
    $updatePenggajianSql = "UPDATE penggajian SET gaji_pokok = '$newgaji', pph = '$pph', total_gaji = '$totalGaji' WHERE id_karyawan = $no_induk";
    $updateKehadiranSql = "UPDATE kehadiran SET nama_karyawan = '$newnama' WHERE id_karyawan = $no_induk";

    if ($conn->query($updateKaryawanSql) === true && $conn->query($updatePenggajianSql) === true && $conn->query($updateKehadiranSql) === true) {
        // Menampilkan alert menggunakan JavaScript
        echo "<script>alert('Data Berhasil Diubah');</script>";
        
        // Redirect ke halaman index.php
        echo "<script>window.location.href = 'index.php';</script>";
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}



// Menutup koneksi
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Data Karyawan</title>
    <style>
        body {
            background-image: url('assets/images/forest.jpg'); /* Ganti 'nama_gambar.jpg' dengan nama gambar Anda */
            background-size: cover; /* Untuk mengisi seluruh area latar belakang */
            background-repeat: no-repeat; /* Untuk menghindari pengulangan gambar */
            background-attachment: fixed; /* Untuk menjaga gambar tetap pada tempatnya saat menggulir halaman */
        }
        body {
            font-family: Arial, sans-serif;
        }

        h1 {
            text-align: center;
        }

        form {
            width: 300px;
            margin: 0 auto;
        }

        label, input {
            display: block;
            margin-bottom: 10px;
        }

        input[type="submit"] {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h1>Edit Data Karyawan</h1>

    <form method="POST">
        <label for="nama_karyawan">Nama:</label>
        <input type="text" name="nama_karyawan" value="<?php echo $nama_karyawan; ?>" required>

        <label for="jabatan">Jabatan:</label>
        <select name="jabatan" required>
            <option value="Dapur" <?php if ($position === "Dapur") echo "selected"; ?>>Dapur</option>
            <option value="Kasir" <?php if ($position === "Kasir") echo "selected"; ?>>Kasir</option>
            <option value="Pelayan" <?php if ($position === "Pelayan") echo "selected"; ?>>Pelayan</option>
        </select>

        <label for="gaji_pokok">Gaji Pokok:</label>
        <input type="number" name="gaji_pokok" value="<?php echo $salary; ?>" required>

        <input type="submit" name="simpan" value="Simpan">
        
        <input type="button" value="Batal" onclick="location.href='index.php';">
    </form>
    
</body>
</html>
