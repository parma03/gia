<?php
include '../../config/koneksi.php';

$id_kelas = $_POST['id_kelas'];
$id_murid = $_POST['id_murid'];
$status = $_POST['status'];
$tanggal = date("Y-m-d");

// Cek apakah presensi hari ini sudah ada
$queryCek = "SELECT * FROM tb_presensi WHERE id_kelas_assesment = ? AND id_murid = ? AND tanggal = ?";
$stmtCek = $conn->prepare($queryCek);
$stmtCek->bind_param("iis", $id_kelas, $id_murid, $tanggal);
$stmtCek->execute();
$resultCek = $stmtCek->get_result();

if ($resultCek->num_rows == 0) {
    // Tambahkan presensi
    $queryInsert = "INSERT INTO tb_presensi (id_kelas_assesment, id_murid, tanggal, status) VALUES (?, ?, ?, ?)";
    $stmtInsert = $conn->prepare($queryInsert);
    $stmtInsert->bind_param("iiss", $id_kelas, $id_murid, $tanggal, $status);
    $stmtInsert->execute();
}

header("Location: data_attend_kelas.php?id_kelas=$id_kelas");
exit();
?>