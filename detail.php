<!DOCTYPE html>
<html>
<head>
  <title>Detail Karyawan</title>
  <link rel="stylesheet" type="text/css" href="style.css">
  <style>
    body {
            background-image: url('assets/images/forest.jpg'); /* Ganti 'nama_gambar.jpg' dengan nama gambar Anda */
            background-size: cover; /* Untuk mengisi seluruh area latar belakang */
            background-repeat: no-repeat; /* Untuk menghindari pengulangan gambar */
            background-attachment: fixed; /* Untuk menjaga gambar tetap pada tempatnya saat menggulir halaman */
        }

    h1, h2 {
      text-align: center;
      margin-top: 20px;
    }

    .container {
      width: 400px;
      margin: 0 auto;
      background-color: rgba(255, 255, 255, 0.8);
      padding: 20px;
      border-radius: 10px;
    }

    strong {
      font-size: 18px;
    }

    button {
      display: block;
      margin: 20px auto;
    }
  </style>
</head>
<body>
  <h1>Detail Karyawan</h1>
  <div class="container">
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

    // Memeriksa apakah parameter id diterima
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // Mengambil data karyawan berdasarkan id
        $query = "SELECT k.*, p.gaji_pokok, p.pph, p.total_gaji
                  FROM karyawan k
                  LEFT JOIN penggajian p ON k.id_karyawan = p.id_karyawan
                  WHERE k.id_karyawan = '$id'";

        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo "<h2>Data Karyawan</h2>";
            echo "<div style='text-align: center;'>";
            echo "<strong>No. Induk:</strong> " . $row['id_karyawan'] . "<br>";
            echo "<strong>Nama:</strong> " . $row['nama_karyawan'] . "<br>";
            echo "<strong>Jabatan:</strong> " . $row['jabatan'] . "<br>";
            echo "<strong>Gaji Pokok:</strong> " . $row['gaji_pokok'] . "<br>";
            echo "<strong>PPH 5%:</strong> " . $row['pph'] . "<br>";
            echo "<strong>Total Gaji:</strong> " . $row['total_gaji'] . "<br>";
            echo "</div>";
        } else {
            echo "<p>Data karyawan tidak ditemukan.</p>";
        }
    } else {
        echo "<p>Parameter id tidak ditemukan.</p>";
    }

    // Menutup koneksi
    $conn->close();
    ?>
    <br>
    <button onclick="window.history.back();">Kembali</button>
  </div>
</body>
</html>
