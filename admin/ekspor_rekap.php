<?php
session_start();

include_once '../cfgdb.php';

if (!isset($_SESSION['username'])) {
    header("Location: index");
    exit();
}

// Ambil data pengguna sesuai dengan parameter
$userid = '';
if (isset($_GET['nim'])) {
    $userid = $_GET['nim'];
}

$stmt = $conn->prepare("SELECT pengguna.nama, penempatan.penempatan_nama FROM pengguna INNER JOIN penempatan ON pengguna.penempatan_id = penempatan.penempatan_id WHERE nim = :userid");
$stmt->bindParam(':userid', $userid);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row) {
    $nama_pengguna = $row["nama"];
    $nama_penempatan = $row["penempatan_nama"];
} else {
    $nama_pengguna = 0;
    $nama_penempatan = 0;
}

// set default timezone
date_default_timezone_set('Asia/Jakarta');

// ambil tahun dan bulan dari parameter GET, atau gunakan tanggal hari ini
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');
$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');

require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// buat objek spreadsheet baru
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// buat heading
$sheet->setCellValue('A1', 'Tanggal')
    ->setCellValue('B1', 'Jam Masuk')
    ->setCellValue('C1', 'Jam Keluar')
    ->setCellValue('D1', 'Status')
    ->setCellValue('E1', 'Logbook') // Tambah kolom Logbook
    ->setCellValue('F1', 'Keterangan'); // Kolom Keterangan dipindah ke kolom F

// ambil jumlah hari pada bulan dan tahun yang dipilih
$jumlah_hari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
$nama_hari_arr = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu');
$nama_bulan_arr = array(
    'Januari',
    'Februari',
    'Maret',
    'April',
    'Mei',
    'Juni',
    'Juli',
    'Agustus',
    'September',
    'Oktober',
    'November',
    'Desember'
);

// atur style untuk header dan hari Sabtu serta Minggu
$style_header = array(
    'fill' => array(
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => array('rgb' => 'ffff00'),
    ),
    'font' => array(
        'bold' => true,
    ),
);
$style_minggu = array(
    'fill' => array(
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => array('rgb' => 'ffff00'),
    ),
);
$style_sabtu = $style_minggu; // Atur style Sabtu sama seperti Minggu

// atur style untuk header
$sheet->getStyle('A1:F1')->applyFromArray($style_header);

// buat variabel untuk menyimpan jumlah hadir, izin, dan sakit
$jumlah_hadir = 0;
$jumlah_izin = 0;
$jumlah_sakit = 0;

// looping tanggal dari 1 sampai jumlah hari pada bulan ini
for ($i = 1; $i <= $jumlah_hari; $i++) {
    // ambil nama hari dalam bahasa Indonesia
    $nama_hari = $nama_hari_arr[date('N', strtotime($tahun . '-' . $bulan . '-' . $i))];

    // atur style untuk hari Sabtu dan Minggu
    if ($nama_hari == 'Minggu') {
        $sheet->getStyle('A' . ($i + 1) . ':F' . ($i + 1))->applyFromArray($style_minggu);
    } elseif ($nama_hari == 'Sabtu') {
        $sheet->getStyle('A' . ($i + 1) . ':F' . ($i + 1))->applyFromArray($style_sabtu);
    }

    // ambil nama bulan dalam bahasa Indonesia
    $nama_bulan = $nama_bulan_arr[intval($bulan) - 1];

    // ambil data absen dari database berdasarkan tanggal dan nim
    $query = "SELECT absen.id_absen, absen.nim, absen.id_status, status_absen.nama_status, absen.tanggal_absen, absen.jam_masuk, absen.jam_keluar, absen.keterangan, absen.logbook 
    FROM absen 
    JOIN status_absen ON absen.id_status = status_absen.id_status 
    WHERE nim = ? AND tanggal_absen = ?
    ORDER BY absen.id_absen DESC";
    
    $stmt = $conn->prepare($query);
    $stmt->bindParam(1, $userid);
    $tanggal_absen = $tahun . '-' . $bulan . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);
    $stmt->bindParam(2, $tanggal_absen);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($result) > 0) {
        // jika data absen ditemukan, tampilkan status, logbook, dan keterangan
        $data_absen = $result[0];
        $jam_masuk = $data_absen['jam_masuk'];
        $jam_keluar = $data_absen['jam_keluar'];
        $status = $data_absen['nama_status'];
        $logbook = $data_absen['logbook'];
        $keterangan = $data_absen['keterangan'];

        // tambahkan 1 ke jumlah hadir, izin, atau sakit sesuai dengan status absen
        if ($status == 'Hadir') {
            $jumlah_hadir++;
        } elseif ($status == 'Izin') {
            $jumlah_izin++;
        } elseif ($status == 'Sakit') {
            $jumlah_sakit++;
        }
    } else {
        // jika data absen tidak ditemukan, tampilkan status kosong, logbook kosong, dan keterangan kosong
        $jam_masuk = '';
        $jam_keluar = '';
        $status = '';
        $logbook = ''; // Logbook kosong
        // tambahkan keterangan untuk hari Minggu dan Sabtu
        if ($nama_hari == 'Minggu') {
            $keterangan = 'Libur Akhir Pekan';
            $sheet->getStyle('A' . ($i + 1) . ':F' . ($i + 1))->applyFromArray($style_minggu);
        } elseif ($nama_hari == 'Sabtu') {
            $keterangan = 'Libur Akhir Pekan';
            $sheet->getStyle('A' . ($i + 1) . ':F' . ($i + 1))->applyFromArray($style_sabtu);
        } else {
            $keterangan = '';
        }
    }

    // tambahkan data ke baris spreadsheet
    $baris = $i + 1;
    $sheet->setCellValue('A' . $baris, $nama_hari . ', ' . $i . ' ' . $nama_bulan . ' ' . $tahun);
    $sheet->setCellValue('B' . $baris, $jam_masuk);
    $sheet->setCellValue('C' . $baris, $jam_keluar);
    $sheet->setCellValue('D' . $baris, $status);
    $sheet->setCellValue('E' . $baris, $logbook); // Logbook
    $sheet->setCellValue('F' . $baris, $keterangan); // Keterangan
}

// atur lebar kolom, termasuk kolom Logbook
$sheet->getColumnDimension('A')->setWidth(25);
$sheet->getColumnDimension('B')->setWidth(15);
$sheet->getColumnDimension('C')->setWidth(15);
$sheet->getColumnDimension('D')->setWidth(10);
$sheet->getColumnDimension('E')->setWidth(75); // Logbook sama seperti keterangan
$sheet->getColumnDimension('F')->setWidth(75); // Keterangan

// Menambahkan garis tepi pada setiap baris dan kolom pada tabel, dimulai dari baris ke-2 dan kolom A sampai E
$lastRow = $sheet->getHighestRow();
$lastColumn = $sheet->getHighestColumn();
$range = 'A1:' . $lastColumn . $lastRow;
$styleBorder = [
    'borders' => [
        'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['rgb' => '000000'],
        ],
    ],
];
$sheet->getStyle($range)->applyFromArray($styleBorder);

$total = $jumlah_hadir + $jumlah_izin + $jumlah_sakit;
// tambahkan total hadir, izin, dan sakit ke dalam sheet
$sheet->setCellValue('A' . ($jumlah_hari + 3), 'Hadir');
$sheet->setCellValue('A' . ($jumlah_hari + 4), 'Izin');
$sheet->setCellValue('A' . ($jumlah_hari + 5), 'Sakit');
$sheet->setCellValue('A' . ($jumlah_hari + 6), 'Jumlah Keseluruhan');

$sheet->setCellValue('B' . ($jumlah_hari + 3), $jumlah_hadir);
$sheet->setCellValue('B' . ($jumlah_hari + 4), $jumlah_izin);
$sheet->setCellValue('B' . ($jumlah_hari + 5), $jumlah_sakit);
$sheet->setCellValue('B' . ($jumlah_hari + 6), $total);

$info = "Rekap untuk " . $nama_bulan . " " . $tahun;
$baris_hadir = $jumlah_hari + 4; // Baris untuk "Hadir" adalah tiga baris setelah baris terakhir data
$kolom_nama = 'E'; // Kolom E

// Set nilai teks "Indra Setiawan" di barisan "Hadir" dan kolom E
$sheet->setCellValue($kolom_nama . $baris_hadir, $info);

$baris_hadir = $jumlah_hari + 5; // Baris untuk "Hadir" adalah tiga baris setelah baris terakhir data
$kolom_nama = 'E'; // Kolom E

$infopengguna = $userid . " - " . $nama_pengguna . " - " . $nama_penempatan;

// Set nilai teks "Indra Setiawan" di barisan "Hadir" dan kolom E
$sheet->setCellValue($kolom_nama . $baris_hadir, $infopengguna);

// tambahkan garis tepi pada sel-sel yang diinginkan
$sheet->getStyle('A' . ($jumlah_hari + 3) . ':B' . ($jumlah_hari + 6))->applyFromArray($styleBorder);

// simpan file spreadsheet ke folder
$writer = new Xlsx($spreadsheet);
$saatini = $nama_bulan . '_' . $tahun;
$filename = 'Rekap_' . $userid . '_' . $nama_pengguna . '_' . $saatini . '.xlsx';
$savePath = '' . $filename; // Ganti '/path/to/user/specified/directory/' dengan direktori yang diinginkan oleh pengguna
$writer->save($savePath);

// download file spreadsheet
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Length: ' . filesize($savePath));
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate');
header('Pragma: public');
readfile($savePath);

// hapus file asli setelah diunduh
unlink($savePath);
exit;
?>