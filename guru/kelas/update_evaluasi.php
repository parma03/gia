<?php
include '../../config/koneksi.php';

// Ambil data dari form
$id_murid = $_POST['id_murid'];
$id_kelas = $_POST['id_kelas'];
$evaluasi_tugas = $_POST['evaluasi_tugas'];  // Nilai evaluasi tugas yang diinputkan
$id_jawaban = $_POST['id_jawaban'];

// Menghitung jumlah pertemuan dan kehadiran
$queryPresensi = "SELECT COUNT(*) AS jumlah_pertemuan, 
                          SUM(CASE WHEN status = 'Hadir' THEN 1 ELSE 0 END) AS jumlah_hadir,
                          SUM(CASE WHEN status = 'Tidak Hadir' THEN 1 ELSE 0 END) AS jumlah_tidak_hadir
                  FROM tb_presensi
                  WHERE id_kelas_assesment = ? AND id_murid = ?";
$stmtPresensi = $conn->prepare($queryPresensi);
$stmtPresensi->bind_param("ii", $id_kelas, $id_murid);
$stmtPresensi->execute();
$resultPresensi = $stmtPresensi->get_result();
$dataPresensi = $resultPresensi->fetch_assoc();

$jumlah_pertemuan = $dataPresensi['jumlah_pertemuan'];
$jumlah_hadir = $dataPresensi['jumlah_hadir'];
$jumlah_tidak_hadir = $dataPresensi['jumlah_tidak_hadir'];

// Evaluasi kehadiran dihitung berdasarkan jumlah hadir dan total pertemuan
$evaluasi_kehadiran = ($jumlah_hadir / $jumlah_pertemuan) * 100; // Persentase kehadiran

// Menghitung total evaluasi dengan memasukkan nilai tugas dan kehadiran
// Anda bisa menentukan bobot tugas dan kehadiran, misalnya tugas 70% dan kehadiran 30%
$bobot_tugas = 0.7;
$bobot_kehadiran = 0.3;

$evaluasi_total = ($evaluasi_tugas * $bobot_tugas) + ($evaluasi_kehadiran * $bobot_kehadiran);

$tanggal = date('Y-m-d');
// Cek apakah data evaluasi sudah ada
$queryEvaluasi = "SELECT * FROM tb_evaluasi WHERE id_kelas_assesment = ? AND id_murid = ?";
$stmtEvaluasi = $conn->prepare($queryEvaluasi);
$stmtEvaluasi->bind_param("ii", $id_kelas, $id_murid);
$stmtEvaluasi->execute();
$resultEvaluasi = $stmtEvaluasi->get_result();

if ($resultEvaluasi->num_rows > 0) {
    // Data evaluasi sudah ada, lakukan update
    $queryUpdate = "INSERT INTO tb_evaluasi (id_kelas_assesment, id_murid, evaluasi_tugas, evaluasi_kehadiran, evaluasi_total, tanggal, id_jawaban) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmtUpdate = $conn->prepare($queryUpdate);
    $stmtUpdate->bind_param("iiiddsi", $id_kelas, $id_murid, $evaluasi_tugas, $evaluasi_kehadiran, $evaluasi_total, $tanggal, $id_jawaban);
    $stmtUpdate->execute();
} else {
    // Data evaluasi belum ada, lakukan insert
    $queryInsert = "INSERT INTO tb_evaluasi (id_kelas_assesment, id_murid, evaluasi_tugas, evaluasi_kehadiran, evaluasi_total, tanggal, id_jawaban) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmtInsert = $conn->prepare($queryInsert);
    $stmtInsert->bind_param("iiiddsi", $id_kelas, $id_murid, $evaluasi_tugas, $evaluasi_kehadiran, $evaluasi_total, $tanggal, $id_jawaban);
    $stmtInsert->execute();
}

header("Location: data_attend_kelas.php?id_kelas=$id_kelas");
exit();
?>