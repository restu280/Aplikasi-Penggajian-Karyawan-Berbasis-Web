<?php
require_once('TCPDF-main/tcpdf.php');

// Mendapatkan tanggal saat ini
$date = new DateTime();
$month = $date->format('m');
$year = $date->format('Y');

// Menentukan nama file laporan
$fileName = "laporan_gaji_" . $month . "_" . $year . ".pdf";

// Membuat objek TCPDF
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Menambahkan halaman baru
$pdf->AddPage();

// Menulis teks pada halaman PDF
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, "WARUNG PECEL SAMBEL DOBLEH", 0, 1, 'C');

// Menambahkan garis
$pdf->Cell(0, 0, '', 'T', 1, 'C');

// Menulis teks pada halaman PDF
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, "Laporan Gaji Bulan " . $month . " Tahun " . $year, 0, 1, 'C');

// Mengambil data karyawan dari database
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

// Mengambil data karyawan bulan ini
$sql = "SELECT * FROM karyawan, penggajian
WHERE karyawan.id_karyawan = penggajian.id_karyawan";
$result = $conn->query($sql);

// Inisialisasi variabel total gaji
$totalGajiSeluruhKaryawan = 0;

// Menampilkan data karyawan bulan ini pada halaman PDF
if ($result->num_rows > 0) {
    $pdf->SetFont('helvetica', '', 12);
    $pdf->Ln(10);
    $pdf->SetLineWidth(0.3); // Mengatur tebal garis tabel

    $pdf->Cell(25, 10, 'No. Induk', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Nama', 1, 0, 'C');
    $pdf->Cell(25, 10, 'Jabatan', 1, 0, 'C');
    $pdf->Cell(35, 10, 'Gaji Pokok', 1, 0, 'C');
    $pdf->Cell(25, 10, 'PPH 5%', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Total Gaji', 1, 1, 'C');

    while ($row = $result->fetch_assoc()) {
        $totalGaji = $row['gaji_pokok'] - $row['pph'];
        $pdf->Cell(25, 10, $row['id_karyawan'], 1, 0, 'C');
        $pdf->Cell(40, 10, $row['nama_karyawan'], 1, 0, 'C');
        $pdf->Cell(25, 10, $row['jabatan'], 1, 0, 'C');
        $pdf->Cell(35, 10, $row['gaji_pokok'], 1, 0, 'C');
        $pdf->Cell(25, 10, $row['pph'], 1, 0, 'C');
        $pdf->Cell(40, 10, $totalGaji, 1, 1, 'C');

         // Menambahkan total gaji ke variabel total gaji seluruh karyawan
         $totalGajiSeluruhKaryawan += $totalGaji;
    }
} else {
    $pdf->Cell(0, 10, 'Data karyawan tidak ditemukan.', 0, 1, 'C');
}

// Menampilkan total gaji dari seluruh karyawan bulan ini
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'Total Gaji Bulan Ini: Rp.' . $totalGajiSeluruhKaryawan, 0, 1, 'R');

// Output file PDF ke browser
$pdf->Output($fileName, 'D');

// Menutup koneksi
$conn->close();

// Menghapus objek PDF dari memori
$pdf->Close();
?>
